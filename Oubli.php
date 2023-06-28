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
    <title>Mot de passe oublié</title>
</head>
<body>
<form method="post" action="">
    <label for="mail">Email:</label><br>
    <input type="text" id="mail" name="mail"><br>
    <input type="submit" value="Réinitialiser mot de passe">
</form>
</body>
</html>
