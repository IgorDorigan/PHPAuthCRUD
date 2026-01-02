<?php

namespace App\controller;

// Importa a model de usuário
use App\model\User;
// Importa utilitário para padronizar respostas de API
use App\utils\ApiResponse;
// Serviço para gerar tokens JWT
use App\service\JwtService;
// Middleware de autenticação da API
use App\middleware\AuthApiMiddleware;

class ApiUserController
{
    public static function index()
    {
        // Busca todos os usuários
        $users = (new User())->all();
        // Retorna os usuários em JSON
        ApiResponse::send($users);
        exit;
    }

    // Obtém um usuário específico por ID
    public static function show($id)
    {
        // Autentica usuário logado
        $user = AuthApiMiddleware::handle();

        // Apenas admin pode acessar
        if ($user['role'] !== 'admin') {
            ApiResponse::send(null, false, 403, "Acesso negado");
            exit;
        }

        // Busca usuário pelo ID
        $userBuscado = (new User())->find($id);

        // Se não existir, retorna erro
        if (! $userBuscado) {
            ApiResponse::send(null, false, 400, 'Usuario não econtrado');
            exit;
        }

        // Prepara dados filtrados para retorno
        $resultado = [
            'id' => $userBuscado['id'],
            'nome' => $userBuscado['nome'],
            'email' => $userBuscado['email'],
            'role' => $userBuscado['role']
        ];

        // Retorna os dados do usuário
        ApiResponse::send($resultado, true, 200, 'Dados do usuário');
        exit;
    }

    // Cria um novo usuário
    public static function store()
    {
        $data = ApiResponse::receive(); // Recebe dados da requisição

        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';
        $role = $data['role'] ?? '';

        // Verifica se todos os campos foram preenchidos
        if (empty($nome) || empty($email) || empty($senha) || empty($role)) {
            ApiResponse::send(null, false, 400, 'Preencha todos os dados');
            exit;
        }

        // Valida email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ApiResponse::send(null, false, 400, 'Email inválido');
            exit;
        }

        $auth = AuthApiMiddleware::handle(); // Autentica

        if ($auth['role'] !== 'admin') { // Apenas admin pode criar
            ApiResponse::send(null, false, 403, 'Acesso negado');
            exit;
        }

        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'role' => $role
        ];

        // Cria o usuário
        $user = (new User())->create($dados);

        if ($user) {
            // Retorna dados filtrados
            $filtrado = [
                'nome' => $user['nome'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            ApiResponse::send($filtrado, true, 201, 'Usuário criado com sucesso');
            exit;
        } else {
            ApiResponse::send(null, false, 400, 'Falha ao registrar usuário');
            exit;
        }
    }

    // Autenticação de login
    public static function login()
    {
        $data = ApiResponse::receive();

        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        if (empty($email) || empty($senha)) { // Campos obrigatórios
            ApiResponse::send(null, false, 401, "Preencha os dados");
            exit;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) { // Valida email
            ApiResponse::send(null, false, 401, "Digite um email valido");
            exit;
        }

        $dados = [
            'email' => $email,
            'senha' => $senha
        ];

        $user = (new User())->login($dados); // Tenta login

        if ($user) {
            // Gera token JWT
            $token = JwtService::generate([
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            ApiResponse::send(['token' => $token], true, 200, 'Login realizado com sucesso');
            exit;
        } else {
            ApiResponse::send(null, false, 400, 'Não foi possivel realizar o login');
            exit;
        }
    }

    // Atualização completa do usuário (todos os campos)
    public static function updateFull($id)
    {
        $user = AuthApiMiddleware::handle();

        if ($user['role'] !== 'admin') {
            ApiResponse::send(null, false, 403, "Acesso negado");
            exit;
        }

        $data = ApiResponse::receive();

        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $role = $data['role'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ApiResponse::send(null, false, 400, "Digite um email válido");
            exit;
        }

        if (empty($nome) || empty($email) || empty($role)) {
            ApiResponse::send(null, false, 400, "Preencha todos os dados");
            exit;
        }

        $userModel = new User();
        $userBuscado = $userModel->find($id);

        if (!$userBuscado) {
            ApiResponse::send(null, false, 404, "Usuário não encontrado");
            exit;
        }

        // Verifica se email já existe em outro usuário
        $existente = $userModel->findByEmail($email);
        if ($existente && $existente['id'] != $id) {
            ApiResponse::send(null, false, 400, "Usuário já existe");
            exit;
        }

        // Se houve alterações, atualiza
        if ($nome !== $userBuscado['nome'] || $email !== $userBuscado['email'] || $role !== $userBuscado['role']) {
            $dados = [
                'id' => $id,
                'nome' => $nome,
                'email' => $email,
                'role' => $role
            ];

            $atualizou = $userModel->update($dados);

            if ($atualizou) {
                ApiResponse::send($dados, true, 200, "Usuário atualizado com sucesso");
                exit;
            } else {
                ApiResponse::send(null, false, 500, "Falha ao atualizar usuário");
                exit;
            }
        } else {
            ApiResponse::send(null, false, 400, "Nenhuma alteração detectada");
            exit;
        }
    }

    // Atualização parcial do usuário (um ou alguns campos)
    public static function updateParcial($id)
    {
        $user = AuthApiMiddleware::handle(); // Autentica

        if ($user['role'] !== 'admin') {
            ApiResponse::send(null, false, 403, "Acesso negado");
            exit;
        }

        $data = ApiResponse::receive(); // Recebe dados da requisição

        $userModel = new User();
        $userBuscado = $userModel->find($id);

        if (!$userBuscado) {
            ApiResponse::send(null, false, 404, "Usuário não encontrado");
            exit;
        }

        $dados = ['id' => $id];
        $alterou = false;

        // Verifica quais campos foram enviados e se houve alteração
        if (isset($data['nome'])) {
            $dados['nome'] = trim($data['nome']);
            if ($dados['nome'] !== $userBuscado['nome']) $alterou = true;
        }

        if (isset($data['email'])) {
            $dados['email'] = trim($data['email']);
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                ApiResponse::send(null, false, 400, "Digite um email válido");
                exit;
            }
            $existente = $userModel->findByEmail($dados['email']);
            if ($existente && $existente['id'] != $id) {
                ApiResponse::send(null, false, 400, "Email já está em uso");
                exit;
            }
            if ($dados['email'] !== $userBuscado['email']) $alterou = true;
        }

        if (isset($data['role'])) {
            $dados['role'] = trim($data['role']);
            if ($dados['role'] !== $userBuscado['role']) $alterou = true;
        }

        if (!$alterou) { // Nenhuma alteração detectada
            ApiResponse::send(null, false, 400, "Nenhuma alteração detectada");
            exit;
        }

        // Atualiza usuário
        $atualizou = $userModel->update($dados);

        if ($atualizou) {
            ApiResponse::send($atualizou, true, 200, "Usuário atualizado com sucesso");
        } else {
            ApiResponse::send(null, false, 500, "Falha ao atualizar usuário");
        }

        exit;
    }

    // Deleta usuário
    public static function delete($id)
    {
        $user = AuthApiMiddleware::handle(); // Autentica

        if ($user['role'] !== 'admin') {
            ApiResponse::send(null, false, 403, "Acesso negado");
            exit;
        }

        $userModel = new User();
        $deleted = $userModel->delete($id);

        if ($deleted) {
            ApiResponse::send(null, true, 200, "Usuario deletado com sucesso");
        }
        else {
            ApiResponse::send(null, false, 400, "Falha ao tentar deletar usuario");
        }
    }
}
