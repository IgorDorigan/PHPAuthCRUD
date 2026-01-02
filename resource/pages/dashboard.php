<?php
// Certifique-se de que a variável $users venha do seu Controller
// Exemplo: $users = User::all();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 border-bottom">
        <div class="container">
            <a class="navbar-brand" href="/sistema/">MeuSistema</a>
            
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">Painel Administrativo</span>
                <a href="/sistema/user/logout" class="btn btn-sm btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container">

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['tipo_mensagem']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensagem']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['mensagem']);
            unset($_SESSION['tipo_mensagem']);
            ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Gerenciar Usuários</h2>
                <a href="/sistema/" class="text-decoration-none small text-muted">&larr; Voltar para o início</a>
            </div>
            <a href="/sistema/user/register" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Novo Usuário
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <th scope="row"><?php echo $user['id']; ?></th>
                                        <td><?php echo $user['nome']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td class="text-end">
                                            <a href="/sistema/user/update/<?= $user['id'] ?>" class="btn btn-sm btn-outline-info me-1">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>

                                            <form action="/sistema/user/delete/<?= $user['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Excluir
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum usuário encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>