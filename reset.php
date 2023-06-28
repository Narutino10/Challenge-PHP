<?php
$host = 'localhost';
$port = '5432';
$db   = 'five';
$user = 'postgres';
$pass = 'toto';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer le token et le nouveau mot de passe soumis
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Vérifier si le token existe dans la base de données
    if (tokenExists($token, $pdo)) {
        // Mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
        updatePassword($token, $newPassword, $pdo);

        // Supprimer le token de la base de données
        deleteToken($token, $pdo);

        // Afficher un message de succès
        echo 'Votre mot de passe a été réinitialisé avec succès.';

        // Rediriger l'utilisateur vers la page de connexion
        header('Location: login.php');
        exit();
    } else {
        // Le token est invalide ou a expiré
        echo 'Le lien de réinitialisation du mot de passe est invalide ou a expiré.';
    }
}

// Fonction pour vérifier si le token existe dans la base de données
function tokenExists($token, $pdo) {
    $stmt = $pdo->prepare('SELECT * FROM tokens WHERE token = ?');
    $stmt->execute([$token]);
    return $stmt->rowCount() > 0;
}

// Fonction pour mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
function updatePassword($token, $newPassword, $pdo) {
    $stmt = $pdo->prepare('UPDATE Client SET MDP = ? WHERE Mail = (SELECT Mail FROM tokens WHERE token = ?)');
    $stmt->execute([$newPassword, $token]);
}

// Fonction pour supprimer le token de la base de données
function deleteToken($token, $pdo) {
    $stmt = $pdo->prepare('DELETE FROM tokens WHERE token = ?');
    $stmt->execute([$token]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
<h1>Réinitialisation du mot de passe</h1>
<form method="POST" action="">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <label for="new_password">Nouveau mot de passe:</label>
    <input type="password" name="new_password" required>
    <button type="submit" name="submit">Réinitialiser le mot de passe</button>
</form>
</body>
</html>
