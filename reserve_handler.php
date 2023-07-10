<?php
// Connexion à la base de données
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification des données de réservation
    $terrainNom = $_POST['terrain'];
    $dateLocation = $_POST['date'];

    // Vérifier si le terrain est libre
    $stmt = $pdo->prepare('SELECT * FROM terrain WHERE nom = :terrain_nom AND location = \'libre\'');
    $stmt->bindParam(':terrain_nom', $terrainNom);
    $stmt->execute();
    $terrain = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$terrain) {
        echo 'Ce terrain n\'est pas disponible pour la réservation.';
        exit;
    }

    // Effectuer la réservation
    $stmt2 = $pdo->prepare('INSERT INTO locations (joueur_id, terrain_id, date_location) VALUES (:joueur_id, :terrain_id, :date_location)');
    $stmt2->bindParam(':joueur_id', $_SESSION['id']); // Assurez-vous que la variable de session contient l'ID du joueur connecté
    $stmt2->bindParam(':terrain_id', $terrain['id']);
    $stmt2->bindParam(':date_location', $dateLocation);
    $stmt2->execute();

    // Mettre à jour l'état du terrain dans la table "terrain"
    $stmt3 = $pdo->prepare('UPDATE terrain SET location = \'occupé\' WHERE nom = :terrain_nom');
    $stmt3->bindParam(':terrain_nom', $terrainNom);
    $stmt3->execute();

    // Redirection vers le dashboard client
    header('Location: Dashboard_client.php');
    exit;

} catch (PDOException $e) {
    echo 'Erreur lors de la réservation : ' . $e->getMessage();
}
?>
