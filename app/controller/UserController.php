<?php

namespace App\controller;

use App\model\User;
use Exception;
use App\utils\View;

class UserController
{
    // Página inicial
    public static function index()
    {
        return View::render('index');
    }

    // Registro de usuário
    public static function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?? '',
                'senha' => $_POST['senha'] ?? '',
                'role' => $_POST['role'] ?? 'user' // padrão user
            ];

            // Valida campos obrigatórios
            if (!empty($dados['nome']) && !empty($dados['email']) && !empty($dados['senha'])) {
                $created = $userModel->create($dados);
                if ($created) {
                    echo "Usuário registrado com sucesso!";
                    header('Location: /sistema/user/login');
                    exit;
                } else {
                    echo "Erro ao registrar usuário.";
                }
            } else {
                echo "Por favor, preencha todos os campos obrigatórios.";
            }
        }

        return View::render('register'); // Renderiza formulário
    }

    // Login de usuário
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $dados = [
                'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?? '',
                'senha' => $_POST['senha'] ?? ''
            ];

            // Inicia sessão se ainda não tiver
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!empty($dados['email']) && !empty($dados['senha'])) {
                $user = $userModel->login($dados);
                if ($user) {
                    session_start(); // Inicia sessão
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nome'];
                    $_SESSION['user_role'] = $user['role'];
                    header('Location: /sistema/'); // Redireciona
                    exit;
                } else {
                    session_start();
                    $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
                    $_SESSION['tipo_mensagem'] = "warning";
                    header('Location: /sistema/user/login');
                }
            } else {
                session_start();
                $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
                $_SESSION['tipo_mensagem'] = "warning";
                header('Location: /sistema/user/login');
                exit; // 🔹 MUITO IMPORTANTE
            }
        }
        return View::render('login'); // Renderiza formulário
    }

    // Admin: lista todos os usuários
    public static function getAll()
    {
        $userModel = new User();
        $users = $userModel->all();
        return View::render('dashboard', ['users' => $users]);
    }

    // Admin: atualizar usuário
    public static function update($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userModel = new User();
        $user = $userModel->find((int)$id);

        if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $nome = $_POST['nome'];

            // Valida campos
            if (empty($nome) || empty($email)) {
                $_SESSION['mensagem'] = "Nome e email não podem ser vazios.";
                $_SESSION['tipo_mensagem'] = "danger";
                header('Location: /sistema/user/update/' . $id);
                exit;
            }

            // Verifica email duplicado
            $existente = $userModel->findByEmail($email);
            if ($existente && $existente['id'] != $id) {
                $_SESSION['mensagem'] = "O email já está em uso por outro usuário.";
                $_SESSION['tipo_mensagem'] = "warning";
                header('Location: /sistema/user/update/' . $id);
                exit;
            }

            // Se houve alteração, atualiza
            if ($nome !== $user['nome'] || $email !== $user['email']) {
                $dados = [
                    'id' => $id,
                    'nome' => $nome,
                    'email' => $email,
                ];

                $atualizou = $userModel->update($dados);

                if ($atualizou) {
                    $_SESSION['mensagem'] = "Usuário atualizado com sucesso.";
                    $_SESSION['tipo_mensagem'] = "success";
                    header('Location: /sistema/');
                    exit;
                } else {
                    $_SESSION['mensagem'] = "Erro ao atualizar usuário no banco de dados.";
                    $_SESSION['tipo_mensagem'] = "danger";
                    echo "Erro ao atualizar usuário.";
                    header('Location: /sistema/user/update/' . $id);
                    exit;
                }
            } else {
                $_SESSION['mensagem'] = "Nenhuma alteração detectada.";
                $_SESSION['tipo_mensagem'] = "info";
                header('Location: /sistema/user/update/' . $id);
                exit;
            }
        }

        return View::render('edit', ['user' => $user]); // Renderiza formulário de edição
    }

    // Admin: deletar usuário
    public static function delete($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userModel = new User();
        $deleted = $userModel->delete((int)$id);

        if ($deleted) {
            $_SESSION['mensagem'] = "Usuário excluído com sucesso.";
            $_SESSION['tipo_mensagem'] = "success";
        } else {
            $_SESSION['mensagem'] = "Erro ao excluir usuário.";
            $_SESSION['tipo_mensagem'] = "danger";
        }

        header('Location: /sistema/'); // Redireciona
        exit;
    }

    // Usuário padrão: pegar dados (vazio por enquanto)
    public static function dados($id) {}

    // Logout
    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /sistema/user/login'); // Redireciona para login
        exit;
    }
}
