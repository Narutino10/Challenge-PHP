<?php
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer l'ID du joueur à partir de la requête
    $joueurId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Récupérer les détails du joueur
    $stmt = $pdo->prepare('SELECT client.id, client.nom, client.images, stat.matchjouer, stat.matchgagner, stat.but, stat.passedecisive 
                           FROM client 
                           JOIN stat ON client.id = stat.id
                           WHERE client.id = :id');
    $stmt->execute([':id' => $joueurId]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Envoyer les détails du joueur en réponse
    header('Content-Type: application/json');
    echo json_encode($joueur);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>
