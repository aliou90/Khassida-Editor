<?php
// Définissez le port sur 2222
$port = 2222;

// Récupérez l'adresse IP du serveur (peut-être votre adresse IP locale)
$serverIP = getHostByName(getHostName());

// Affichez l'adresse IP et le port pour informer l'utilisateur
echo "Adresse IP du serveur : $serverIP\n";
echo "Port du serveur : $port\n";

// Créez un serveur HTTP simple
exec("php -S $serverIP:$port");
?>