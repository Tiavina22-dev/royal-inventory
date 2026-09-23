<?php

require __DIR__ . '/app/bootstrap.php';

if ($auth->check()) {
    redirect('index.php');
}

$dbError = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    try {
        if ($auth->login($username, $password)) {
            flash('success', 'Connexion reussie.');
            redirect('index.php');
        }
        flash('danger', 'Nom utilisateur ou mot de passe incorrect.');
    } catch (Throwable $e) {
        $dbError = $e->getMessage();
        flash('danger', 'Base de donnees indisponible. Verifie MySQL/MariaDB et la configuration.');
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Royal Inventory V1</title>
    <link rel="stylesheet" href="public/assets/css/app.css">
</head>
<body class="login-screen">
    <main class="login-panel">
        <div class="login-brand">
            <span class="brand-mark"><span class="ui-icon ui-icon-crown" aria-hidden="true"></span></span>
            <div>
                <h1>Royal Inventory</h1>
                <p>Nouvelle interface de migration</p>
            </div>
        </div>

        <?php foreach (flashes() as $message): ?>
            <div class="alert alert-<?= e($message['type']) ?>"><?= e($message['message']) ?></div>
        <?php endforeach; ?>

        <?php if ($dbError): ?>
            <p class="text-muted small">Detail technique: <?= e($dbError) ?></p>
        <?php endif; ?>

        <form method="post" class="stack">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <label>
                Nom utilisateur
                <input name="username" autocomplete="username" required autofocus>
            </label>
            <label>
                Mot de passe
                <input name="password" type="password" autocomplete="current-password" required>
            </label>
            <button class="button button-primary" type="submit"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Se connecter</button>
        </form>
    </main>
</body>
</html>
