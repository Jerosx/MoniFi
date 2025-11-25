<?php

function get_logged_user_id() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['user_id'] ?? null;
}


?>