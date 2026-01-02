<?php

use App\controller\UserController;
use App\http\Router;
use App\middleware\AuthMiddleware;
use App\middleware\RoleMiddleware;
use App\middleware\AdminMiddleware;
use App\controller\ApiUserController;

// Rota inicial protegida por autenticação 
$obRouter->get('/', [UserController::class, 'index'], [
    AuthMiddleware::class,
]);

// Dashboard: lista todos os usuários
$obRouter->get('/dashboard', [UserController::class, 'getAll']);

// Registro de usuário (GET exibe formulário, POST envia)
$obRouter->get('/user/register', [UserController::class, 'register']);
$obRouter->post('/user/register', [UserController::class, 'register']);

// Login de usuário (GET exibe formulário, POST envia)
$obRouter->get('/user/login', [UserController::class, 'login']);
$obRouter->post('/user/login', [UserController::class, 'login']);

// Atualizar usuário (GET exibe formulário, POST envia)
$obRouter->get('/user/update/{id}', [UserController::class, 'update']);
$obRouter->post('/user/update/{id}', [UserController::class, 'update']);

// Deletar usuário
$obRouter->delete('/user/delete/{id}', [UserController::class, 'delete']);

// Logout
$obRouter->get('/user/logout', [UserController::class, 'logout']);

// =====================
// Rotas da API
// =====================

// Lista todos os usuários
$obRouter->get('/api/users', [ApiUserController::class, 'index']);

// Pega um usuário pelo ID
$obRouter->get('/api/user/{id}', [ApiUserController::class, 'show']);

// Registrar usuário via API
$obRouter->post('/api/user/register', [ApiUserController::class, 'store']);

// Login via API
$obRouter->post('/api/user/login', [ApiUserController::class, 'login']);

// Atualiza usuário completo via API (PUT)
$obRouter->put('/api/user/update/{id}', [ApiUserController::class, 'updateFull']);

// Atualiza usuário parcialmente via API (PATCH)
$obRouter->patch('/api/user/update/{id}', [ApiUserController::class, 'updateParcial']);

// Deletar usuário via API
$obRouter->delete('/api/user/delete/{id}', [ApiUserController::class, 'delete']);
