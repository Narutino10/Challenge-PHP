<?php
// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer l'adresse e-mail soumise
    $email = $_POST['email'];

    // Vérifier si l'adresse e-mail existe dans la base de données
    // (Vous devez mettre en œuvre cette vérification en fonction de votre structure de base de données)
    if (emailExists($email)) {
        // Générer un token unique pour la réinitialisation du mot de passe
        $token = generateToken();

        // Enregistrer le token dans la base de données avec la correspondance de l'adresse e-mail
        saveToken($email, $token);

        // Envoyer l'e-mail de réinitialisation du mot de passe
        $subject = 'Réinitialisation du mot de passe';
        $message = 'Bonjour, cliquez sur le lien suivant pour réinitialiser votre mot de passe : ' . generateResetLink($token);
        $headers = 'From: votre@email.com';

        if (mail($email, $subject, $message, $headers)) {
            // L'e-mail a été envoyé avec succès
            echo 'Un e-mail de réinitialisation du mot de passe a été envoyé à votre adresse e-mail.';
        } else {
            // Une erreur s'est produite lors de l'envoi de l'e-mail
            echo 'Une erreur s\'est produite lors de l\'envoi de l\'e-mail. Veuillez réessayer plus tard.';
        }
    } else {
        // L'adresse e-mail n'existe pas dans la base de données
        echo 'Cette adresse e-mail n\'est pas enregistrée.';
    }
}

// Fonction pour vérifier si l'adresse e-mail existe dans la base de données
function emailExists($email) {
    // Implémentez votre logique pour vérifier si l'adresse e-mail existe dans la base de données
    // Retournez true si l'adresse e-mail existe, sinon retournez false
}

// Fonction pour générer un token unique
function generateToken() {
    $token = bin2hex(random_bytes(32)); // Génère un token de 32 octets en format hexadécimal
    return $token;
}

// Fonction pour enregistrer le token dans la base de données
function saveToken($email, $token) {
    // Implémentez votre logique pour enregistrer le token dans la base de données
    // Assurez-vous d'associer le token à l'adresse e-mail correspondante
}

function generateResetLink($token) {
    $resetLink = 'reset.php?token=' . $token; 
    return $resetLink;
}
?>
