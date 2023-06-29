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
        if (isset($_POST['mail'], $_POST['password'])) {
            // Remplacer les valeurs avec les entrées de l'utilisateur
            $email = $_POST['mail'];
            $password = $_POST['password'];

            // Exemple de requête SQL pour récupérer l'utilisateur basé sur le mail
            $query = "SELECT * FROM client WHERE Mail = :email";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Vérifier si une ligne correspondante a été trouvée
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['mdp'])) {
                    // L'utilisateur est authentifié avec succès
                    // Récupération du rôle de l'utilisateur
                    $role = $row['role'];

                    // Redirection en fonction du rôle
                    if ($role === 'admin') {
                        header('Location: Dashboard_admin.php');
                    } else {
                        header('Location: Dashboard_client.php');
                    }

                    exit();
                } else {
                    // L'authentification a échoué
                    echo 'Identifiants invalides. Veuillez réessayer.';
                }
            } else {
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
    <title>FiveZone - Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #4CAF50;
            font-size: 36px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .form-field label {
            display: block;
            margin-bottom: 5px;
        }

        .form-field input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .form-field input[type="submit"] {
            background: #4CAF50;
            border: 0;
            color: white;
            cursor: pointer;
        }

        .form-field input[type="submit"]:hover {
            background: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .switch {
            text-align: center;
            margin-top: 20px;
        }

        .switch a {
            color: #4CAF50;
            text-decoration: none;
        }

        .switch a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>FiveZone</h1>
</div>
<div class="form-container">
    <form method="post" action="">
        <div class="form-field">
            <label for="mail">Email:</label>
            <input type="text" id="mail" name="mail">
        </div>
        <div class="form-field">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-field">
            <a href="Oubli.php">Mot de passe oublié ?</a>
        </div>
        <div class="form-field">
            <input type="submit" value="Connexion">
        </div>
    </form>
    <div class="switch">
        <a href="register.php">Pas encore de compte ? Inscrivez-vous ici</a>
    </div>
</div>
</body>
</html>
