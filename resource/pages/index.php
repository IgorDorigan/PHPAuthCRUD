<?php
$user = authUser();
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-custom {
            transition: transform 0.2s;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .card-custom:hover {
            transform: scale(1.02);
            border-color: var(--bs-primary);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-5 border-bottom">
        <div class="container">
            <a class="navbar-brand" href="#">MeuSistema</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i> <?= $user['nome'] ?>
                </span>
                <a href="/sistema/user/logout" class="btn btn-sm btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="text-center mb-5">
            <h1>O que você deseja fazer?</h1>
            <p class="text-muted">Selecione uma opção abaixo.</p>
        </div>

        <div class="row justify-content-center g-4">
            
            <div class="col-md-5 col-lg-4">
                <div class="card h-100 card-custom text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-person-vcard display-4 text-success mb-3"></i>
                        <h3 class="card-title">Meus Dados</h3>
                        <p class="card-text text-muted">Atualize suas informações de perfil.</p>
                        <a href="/sistema/user/update/<?= $user['id'] ?>" class="btn btn-success w-100 stretched-link">
                            Acessar Perfil
                        </a>
                    </div>
                </div>
            </div>

            <?php if (isAdmin()): ?>
            <div class="col-md-5 col-lg-4">
                <div class="card h-100 card-custom text-center p-4 border-primary">
                    <div class="card-body">
                        <i class="bi bi-speedometer2 display-4 text-primary mb-3"></i>
                        <h3 class="card-title">Dashboard</h3>
                        <p class="card-text text-muted">Gerenciamento completo de usuários.</p>
                        <a href="/sistema/dashboard" class="btn btn-primary w-100 stretched-link">
                            Gerenciar Sistema
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>