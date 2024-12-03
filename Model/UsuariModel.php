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

function inserirUsuari($usuari, $hashed_password, $correo) { // MIRAR PARTE DE OAUT
    $connexio = connectarBD();
    $sql = "INSERT INTO usuaris (usuari, contrasenya, correo) VALUES (:usuari, :password, :correo)";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute([
        'usuari' => $usuari,
        'password' => $hashed_password,
        'correo' => $correo
    ]); // Retorna true si l'inserció és exitosa
}

// INSERIR USUARI GOOGLE

function inserirUsuariGoogle($usuari, $hashed_password, $correo, $es_social = false) {
    $connexio = connectarBD();
    $sql = "INSERT INTO usuaris (usuari, contrasenya, correo, aut_social) 
            VALUES (:usuari, :password, :correo, :es_social)";
    $stmt = $connexio->prepare($sql);
    
    // Aquí verificamos que si no tenemos contraseña, la pasamos como NULL.
    return $stmt->execute([
        'usuari' => $usuari, // El nombre de usuario aleatorio
        'password' => $hashed_password === null ? null : $hashed_password, // Usar NULL si no hay contraseña
        'correo' => $correo, // El correo siempre debe pasarse
        'es_social' => $es_social ? 'si' : 'no' // Indicamos si es un login social
    ]);
}




function obtenirUsuariPerCorreu($email) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE correo = :email"; // Cambiado 'email' por 'correo'
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

function eliminarToken($userId) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET token_remember = NULL, token_remember_expiracio = NULL WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['id' => $userId]);
}

function actualitzarNomUsuari($usuariActual, $nouNom) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET usuari = :nouNom WHERE usuari = :usuariActual";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['nouNom' => $nouNom, 'usuariActual' => $usuariActual]);
}

function actualitzarImatgeUsuari($usuari, $novaImatge) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET imatge = :novaImatge WHERE usuari = :usuari";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['novaImatge' => $novaImatge, 'usuari' => $usuari]);
}

function buscarUsuariPerCorreu($correu) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE correo = :correu";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['correu' => $correu]);
    return $stmt->fetch(); // Retorna l'usuari si existeix
}

function actualitzarContrasenyaUsuari($idUsuari, $novaContrasenyaHashed) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET contrasenya = :novaContrasenya WHERE id = :idUsuari";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['novaContrasenya' => $novaContrasenyaHashed, 'idUsuari' => $idUsuari]); // Retorna true si l'actualització és exitosa
}

// Función para guardar el token de recuperación de contraseña
function guardarTokenRecuperacio($userId, $token) {
    $connexio = connectarBD();
    // Establecer el token de recuperación y su fecha de expiración (por ejemplo, 1 hora)
    $sql = "UPDATE usuaris SET token_recuperacion = :token, token_expiracio_restablir = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['token' => $token, 'id' => $userId]); // Retorna true si la actualización es exitosa
}

// Función para eliminar el token de recuperación después de restablecer la contraseña
function eliminarTokenRecuperacio($userId) {
    $connexio = connectarBD();
    $sql = "UPDATE usuaris SET token_recuperacion = NULL, token_expiracio_restablir = NULL WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['id' => $userId]); // Retorna true si la eliminación es exitosa
}


// Canviada per el token de recuperació de contrasenya
function obtenirUsuariPerTokenRec($token) {
    $connexio = connectarBD();
    $sql = "SELECT * FROM usuaris WHERE token_recuperacion = :token AND token_expiracio_restablir > NOW()";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['token' => $token]);
    return $stmt->fetch(); // Retorna l'usuari si el token és vàlid
}

function guardarToken($usuari, $token) {
    $connexio = connectarBD();
    // Calcular la fecha de expiración (7 días a partir de ahora)
    $expiracio = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60));
    
    // Actualizar el token y su expiración
    $sql = "UPDATE usuaris SET token_remember = :token, token_remember_expiracio = :expiracio WHERE usuari = :usuari";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute([
        'token' => $token,
        'expiracio' => $expiracio,
        'usuari' => $usuari
    ]);
}

// Obtener un usuario por el token
function obtenirUsuariPerToken($token) {
    $connexio = connectarBD();
    
    // Verificar si el token es válido y no ha expirado
    $sql = "SELECT * FROM usuaris WHERE token_remember = :token AND token_remember_expiracio > NOW()";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['token' => $token]);
    return $stmt->fetch(); // Retorna el usuario si el token es válido
}

// PART ADMINISTRADOR

function obtenirTotsElsUsuaris() {
    $connexio = connectarBD();
    $sql = "SELECT id, usuari, correo, rol FROM usuaris";
    $stmt = $connexio->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function eliminarArticlesDeUsuari($id_usuari) {
    $connexio = connectarBD();
    $sql = "DELETE FROM articles WHERE usuari_id = :id_usuari";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['id_usuari' => $id_usuari]);
}

function eliminarUsuari($id) {
    $connexio = connectarBD();
    $sql = "DELETE FROM usuaris WHERE id = :id";
    $stmt = $connexio->prepare($sql);
    $stmt->execute(['id' => $id]);
}

function insertarUsuariGoogle($correo) {
    $connexio = connectarBD();
    $sql = "INSERT INTO usuaris (correo, aut_social, rol) VALUES (:correo, 'si', 'user')";
    $stmt = $connexio->prepare($sql);
    return $stmt->execute(['correo' => $correo]);
}


?>
