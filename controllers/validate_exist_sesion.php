<?php
/* Comprobar si no hay una sesión activa antes de iniciar una nueva sesión */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar SI EXISTE el índice
$user_session = $_SESSION['user_id'] ?? null;

// Si no hay sesión válida → redirigir SIEMPRE al login correcto
if (!$user_session) {
    header("Location: /MoniFi-/public/index.html");
    exit;
}
?>
