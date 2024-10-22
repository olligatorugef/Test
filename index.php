<?php
session_start();

// Étapes de l'installation
$steps = ['Choix de l\'installation', 'Domaine', 'URL de PhpMyAdmin', 'Infos du panel', 'Infos Admin PhpMyAdmin', 'Téléchargement'];

// Initialiser l'étape courante
$current_step = isset($_SESSION['step']) ? $_SESSION['step'] : 0;

// Sauvegarder les données de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        $_SESSION[$key] = $value;
    }
    $current_step++;
    $_SESSION['step'] = $current_step;
}

// Retour à l'étape précédente
if (isset($_POST['previous'])) {
    $current_step--;
    $_SESSION['step'] = $current_step;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation de Daemonix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .step {
            margin-bottom: 20px;
        }
        .progress {
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .progress-bar {
            background-color: #4CAF50;
            height: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Installation de Daemonix</h1>
    <div class="progress">
        <p>Étape <?= $current_step + 1 ?> sur <?= count($steps) ?> : <?= $steps[$current_step] ?></p>
        <div class="progress-bar" style="width:<?= ($current_step + 1) / count($steps) * 100 ?>%"></div>
    </div>

    <form method="post">
        <?php if ($current_step == 0) : ?>
            <!-- Étape 1 : Choix de l'installation -->
            <div class="step">
                <label for="install_type">Type d'installation :</label>
                <select name="install_type" id="install_type" required>
                    <option value="panel" <?= isset($_SESSION['install_type']) && $_SESSION['install_type'] == 'panel' ? 'selected' : '' ?>>Panel</option>
                    <option value="wings" <?= isset($_SESSION['install_type']) && $_SESSION['install_type'] == 'wings' ? 'selected' : '' ?>>Wings</option>
                    <option value="panel_wings" <?= isset($_SESSION['install_type']) && $_SESSION['install_type'] == 'panel_wings' ? 'selected' : '' ?>>Panel + Wings</option>
                </select>
            </div>
        <?php elseif ($current_step == 1) : ?>
            <!-- Étape 2 : Domaine -->
            <div class="step">
                <?php if ($_SESSION['install_type'] == 'panel') : ?>
                    <label for="panel_domain">Nom de domaine pour le Panel :</label>
                    <input type="text" name="panel_domain" id="panel_domain" required value="<?= isset($_SESSION['panel_domain']) ? $_SESSION['panel_domain'] : '' ?>">
                <?php elseif ($_SESSION['install_type'] == 'wings') : ?>
                    <label for="wings_domain">Nom de domaine pour Wings :</label>
                    <input type="text" name="wings_domain" id="wings_domain" required value="<?= isset($_SESSION['wings_domain']) ? $_SESSION['wings_domain'] : '' ?>">
                <?php elseif ($_SESSION['install_type'] == 'panel_wings') : ?>
                    <label for="panel_domain">Nom de domaine pour le Panel :</label>
                    <input type="text" name="panel_domain" id="panel_domain" required value="<?= isset($_SESSION['panel_domain']) ? $_SESSION['panel_domain'] : '' ?>">

                    <label for="wings_domain">Nom de domaine pour Wings :</label>
                    <input type="text" name="wings_domain" id="wings_domain" required value="<?= isset($_SESSION['wings_domain']) ? $_SESSION['wings_domain'] : '' ?>">
                <?php endif; ?>
            </div>
        <?php elseif ($current_step == 2) : ?>
            <!-- Étape 3 : URL pour PhpMyAdmin -->
            <div class="step">
                <label for="phpmyadmin_url">URL pour installer PhpMyAdmin :</label>
                <input type="text" name="phpmyadmin_url" id="phpmyadmin_url" required value="<?= isset($_SESSION['phpmyadmin_url']) ? $_SESSION['phpmyadmin_url'] : '' ?>">
            </div>
        <?php elseif ($current_step == 3) : ?>
            <!-- Étape 4 : Infos du panel -->
            <div class="step">
                <label for="panel_name">Nom du panel :</label>
                <input type="text" name="panel_name" id="panel_name" required value="<?= isset($_SESSION['panel_name']) ? $_SESSION['panel_name'] : '' ?>">
                <label for="admin_email">Email de l'admin :</label>
                <input type="email" name="admin_email" id="admin_email" required value="<?= isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : '' ?>">
            </div>
        <?php elseif ($current_step == 4) : ?>
            <!-- Étape 5 : Infos Admin PhpMyAdmin -->
            <div class="step">
                <label for="admin_username">Nom d'utilisateur Admin PhpMyAdmin :</label>
                <input type="text" name="admin_username" id="admin_username" required value="<?= isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : '' ?>">
                <label for="admin_password">Mot de passe Admin PhpMyAdmin :</label>
                <input type="password" name="admin_password" id="admin_password" required>
            </div>
        <?php elseif ($current_step == 5) : ?>
            <!-- Étape 6 : Téléchargement du panel -->
            <div class="step">
                <p>Téléchargement et installation du panel en cours...</p>
                <progress id="downloadProgress" value="0" max="100"></progress>
                <script>
                    let progress = 0;
                    const interval = setInterval(() => {
                        progress += 10;
                        document.getElementById('downloadProgress').value = progress;
                        if (progress >= 100) {
                            clearInterval(interval);
                            alert('Installation terminée !');
                            window.location.href = '/';
                        }
                    }, 1000);
                </script>
            </div>
        <?php endif; ?>

        <div class="actions">
            <?php if ($current_step > 0) : ?>
                <input type="submit" name="previous" value="Précédent">
            <?php endif; ?>
            <?php if ($current_step < count($steps) - 1) : ?>
                <input type="submit" value="Suivant">
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
