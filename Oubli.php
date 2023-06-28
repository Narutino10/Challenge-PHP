<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mail'])) {
        $host = 'localhost';
        $port = '5432';
        $db   = 'five';
        $user = 'postgres';
        $pass = 'toto';

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $mail = $_POST['mail'];
        $stmt = $pdo->prepare('SELECT Mail FROM Client WHERE Mail = ?');
        $stmt->execute([$mail]);

        if ($stmt->rowCount() > 0) {
            // Générer un jeton unique
            $token = bin2hex(random_bytes(50));

            // Sauvegarder le jeton dans la base de données ou dans une autre forme de stockage persistent

            // Envoyer un email à l'utilisateur avec le lien de réinitialisation
            $resetLink = "http://localhost/PHP/reset.php?token=" . $token;

            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = 2;
                $mail->isSMTP();
                $mail->Host       = 'smtp-mail.outlook.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'iouahabi1@myges.fr';
                $mail->Password   = 'x67u6c2K';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('iouahabi1@myges.fr', 'Support');
                $mail->addAddress($_POST['mail']);

                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de votre mot de passe';
                $mail->Body    = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="' . $resetLink . '">' . $resetLink . '</a>';

                $mail->send();
                echo 'Un email a été envoyé à votre adresse avec des instructions pour réinitialiser votre mot de passe.';
            } catch (Exception $e) {
                echo "L'email n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo 'Aucun compte trouvé avec cette adresse e-mail.';
        }
    } else {
        echo 'Veuillez entrer votre adresse e-mail.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FiveZone - Mot de passe oublié</title>
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
            <input type="submit" value="Réinitialiser mot de passe">
        </div>
    </form>
    <div class="switch">
        <a href="login.php">Se rappeler du mot de passe? Retour à la connexion</a>
    </div>
</div>
</body>
</html>

