<?php

namespace App\model;

use App\database\Database;
use PDO;

class User
{
    private $nome;
    private $email;
    private $senha;
    private $role;

    private PDO $conn;

    public function __construct()
    {
        // Conecta ao banco de dados
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Cria um novo usuário
    public function create(array $dados)
    {
        $senha_hash = password_hash($dados['senha'], PASSWORD_DEFAULT); // Criptografa senha

        $sql = "INSERT INTO users (nome, email, senha, role) VALUES (:nome, :email, :senha, :role)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':nome' => $dados['nome'],
                ':email' => $dados['email'],
                ':senha' => $senha_hash,
                ':role' => $dados['role']
            ]);

            // Pega o usuário recém-criado
            $id = $this->conn->lastInsertId();
            $stmt2 = $this->conn->prepare("SELECT id, nome, email, role FROM users WHERE id = :id");
            $stmt2->execute([':id' => $id]);
            return $stmt2->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Login de usuário
    public function login(array $dados)
    {
        $user = $this->findByEmail($dados['email']);
        if ($user && password_verify($dados['senha'], $user['senha'])) {
            return $user; // Retorna dados do usuário
        }
        error_log("Falha no login para o email: " . $dados['email']);
        return false;
    }

    // Busca usuário pelo ID
    public function find(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }

    // Atualiza usuário (nome, email ou role)
    public function update(array $dados)
    {
        if (!isset($dados['id'])) return false; // id é obrigatório

        $fields = [];
        $params = [':id' => $dados['id']];

        if (isset($dados['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $dados['nome'];
        }

        if (isset($dados['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $dados['email'];
        }

        if (isset($dados['role'])) {
            $fields[] = "role = :role";
            $params[':role'] = $dados['role'];
        }

        if (empty($fields)) return false; // Nenhum campo para atualizar

        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute($params);
            // Retorna dados atualizados
            $stmt2 = $this->conn->prepare("SELECT id, nome, email, role FROM users WHERE id = :id");
            $stmt2->execute([':id' => $dados['id']]);
            return $stmt2->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Deleta usuário
    public function delete(int $id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            return true;
        } catch (\PDOException $e) {
            error_log("Erro ao deletar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Retorna todos os usuários
    public function all()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao buscar todos os usuários: " . $e->getMessage());
            return false;
        }
    }

    // Busca usuário pelo email
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }
}
