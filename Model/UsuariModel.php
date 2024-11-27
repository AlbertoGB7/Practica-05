<?php
# Model per a les funcions relacionades amb els usuaris
require_once 'connexio.php';

function obtenirUsuariPerNom($usuari) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE usuari = :usuari";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['usuari' => $usuari]);
    return $stmt->fetch(); // Retorna l'usuari si existeix
}

function inserirUsuari($usuari, $hashed_password) {
    $connexio = connectarBD();
    $sql = "INSERT INTO usuaris (usuari, contrasenya) VALUES (:usuari, :password)";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['usuari' => $usuari, 'password' => $hashed_password]); // Retorna true si l'inserció és exitosa
}

function obtenirUsuariPerCorreu($email) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE email = :email";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(); // Retorna l'usuari si existeix
}

function actualitzarContrasenya($usuari, $novaContrasenyaHashed) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET contrasenya = :novaContrasenya WHERE usuari = :usuari";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['novaContrasenya' => $novaContrasenyaHashed, 'usuari' => $usuari]);
}


// Part REMEMBER ME:
function guardarToken($userId, $token) {
    $connexio = connectarBD();
    // Eliminamos el token del usuario antes de guardarlo para evitar duplicados
    eliminarToken($userId);
    $sql = "UPDATE usuaris SET token_remember = :token, token_remember_expiracio = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['token' => $token, 'id' => $userId]);
}

function obtenirUsuariPerToken($token) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE token_remember = :token AND token_remember_expiracio > NOW()";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['token' => $token]);
    return $stmt->fetch();
}

function eliminarToken($userId) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET token_remember = NULL, token_remember_expiracio = NULL WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['id' => $userId]);
}

?>
