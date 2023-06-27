<?php
// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer le token et le nouveau mot de passe soumis
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Vérifier si le token existe dans la base de données
    if (tokenExists($token)) {
        // Mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
        updatePassword($token, $newPassword);

        // Supprimer le token de la base de données
        deleteToken($token);

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
function tokenExists($token) {
    // Implémentez votre logique pour vérifier si le token existe dans la base de données
    // Retournez true si le token existe, sinon retournez false
}

// Fonction pour mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
function updatePassword($token, $newPassword) {
    // Implémentez votre logique pour mettre à jour le mot de passe de l'utilisateur dans la base de données
    // en utilisant le token comme identifiant
}

// Fonction pour supprimer le token de la base de données
function deleteToken($token) {
    // Implémentez votre logique pour supprimer le token de la base de données
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
