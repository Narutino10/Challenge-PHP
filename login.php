<?php
// Informations de connexion à la base de données PostgreSQL
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    // Créer une nouvelle connexion PDO à la base de données PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Configurer le mode d'erreur PDO pour afficher les exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Exemple de requête SQL pour vérifier l'authentification de l'utilisateur
        $query = "SELECT * FROM Client WHERE Mail = :email AND MDP = :password";
        $stmt = $pdo->prepare($query);

        if (isset($_POST['mail'], $_POST['password'])) {
            // Remplacer les valeurs avec les entrées de l'utilisateur
            $email = $_POST['mail'];
            $password = $_POST['password'];

            // Exécuter la requête avec les paramètres
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Vérifier si une ligne correspondante a été trouvée
            if ($stmt->rowCount() > 0) {
                // L'utilisateur est authentifié avec succès
                header('Location: dashboard.php'); // Rediriger vers dashboard.php
                exit();
            } else {
                // L'authentification a échoué
                echo 'Identifiants invalides. Veuillez réessayer.';
            }
        } else {
            echo 'Veuillez remplir tous les champs.';
        }
    }
} catch (PDOException $e) {
    // Gérer les erreurs de connexion à la base de données
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
<form method="post" action="">
    <label for="mail">Email:</label><br>
    <input type="text" id="mail" name="mail"><br>
    <label for="password">Mot de passe:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <a href="Oubli.php">Mot de passe oublié ?</a><br><br>
    <input type="submit" value="Connexion">
</form>
</body>
</html>
