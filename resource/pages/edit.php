<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 border-bottom">
    <div class="container">
        <a class="navbar-brand" href="/sistema/">MeuSistema</a>
    </div>
</nav>

<div class="container">
    
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-<?= $_SESSION['tipo_mensagem']; ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['mensagem']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php 
            // Limpa as mensagens após exibir para não aparecerem novamente ao recarregar
            unset($_SESSION['mensagem']);
            unset($_SESSION['tipo_mensagem']);
        ?>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Editar Perfil</h4>
                    <span class="badge bg-warning text-dark">ID: <?= htmlspecialchars($user['id']); ?></span>
                </div>
                <div class="card-body">
                    <form action="/sistema/user/update/<?= $user['id'] ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $user['id']; ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   value="<?= htmlspecialchars($user['nome']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <hr>

                        <div class="text-end">
                            <a href="/sistema/" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>