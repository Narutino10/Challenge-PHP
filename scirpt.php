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

    // Insérer des terrains fictifs
    $pdo->exec("INSERT INTO terrain (nom, capacite, typeterrain, etat, images, adresse) VALUES
                ('Terrain A', 10, 'Pelouse', 'Disponible', 'terrainA.jpg', 'Adresse A'),
                ('Terrain B', 8, 'Synthétique', 'Disponible', 'terrainB.jpg', 'Adresse B'),
                ('Terrain C', 12, 'Pelouse', 'Occupé', 'terrainC.jpg', 'Adresse C'),
                ('Terrain D', 6, 'Pelouse', 'Disponible', 'terrainD.jpg', 'Adresse D'),
                ('Terrain E', 10, 'Synthétique', 'Disponible', 'terrainE.jpg', 'Adresse E')");

    // Insérer des locations fictives
    $pdo->exec("INSERT INTO locations (joueur_id, terrain_id, date_location) VALUES
                (1, 'Terrain A', '2023-06-01'),
                (2, 'Terrain A', '2023-06-05'),
                (1, 'Terrain B', '2023-06-08'),
                (3, 'Terrain C', '2023-06-10'),
                (2, 'Terrain D', '2023-06-12')");

    echo "Les données ont été insérées avec succès.";
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>
