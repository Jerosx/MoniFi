<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);
include_once(DB_METADATA_PATH);


function get_logged_user_name($con)
{
    $user_id = $_SESSION["user_id"] ?? null;
    if (!$user_id) {
        return null;
    }

    $sql = "SELECT " . Usuario::NOMBRE . "
            FROM " . Usuario::TBL_NAME . "
            WHERE " . Usuario::ID . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result[Usuario::NOMBRE] ?? null;
}


function get_password_hash($con, $email) {
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT " . Usuario::CONTRASENA . "
            FROM " . Usuario::TBL_NAME . "
            WHERE " . Usuario::EMAIL . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()[Usuario::CONTRASENA];
    }

    return null;
}


function email_exists($con, $email) {
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT " . Usuario::ID . "
            FROM " . Usuario::TBL_NAME . "
            WHERE " . Usuario::EMAIL . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result && $result->num_rows > 0);
}


function validate_credentials($email, $password) {
    $con = create_conection();

    if (!email_exists($con, $email)) {
        echo "<script>
                alert('El correo no está registrado.');
                window.location.href='../../public/index.html';
              </script>";
        exit;
    }

    $stored_hash = get_password_hash($con, $email);

    if ($stored_hash && password_verify($password, $stored_hash)) {

        session_start();

        $user_id = get_logged_user_id($con, $email);

        $_SESSION['user_id'] = $user_id;

        header("Location: ../../views/main.php");
        exit;
    }

    echo "<script>
            alert('Contraseña incorrecta.');
            window.location.href='../../public/index.html';
          </script>";
    exit;
}


function insert_user($name, $email, $password, $con) {

    $sql = "INSERT INTO " . Usuario::TBL_NAME . " (
                " . Usuario::NOMBRE . ",
                " . Usuario::EMAIL . ",
                " . Usuario::CONTRASENA . "
            ) VALUES (?, ?, ?)";

    $hashed = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed);

    return $stmt->execute();
}


function register_user($name, $email, $password) {
    $con = create_conection();

    if (email_exists($con, $email)) {
        echo "<script>
                alert('Este correo ya está registrado.');
                window.location.href='../../public/register_user.html';
              </script>";
        $con->close();
        exit;
    }

    if (insert_user($name, $email, $password, $con)) {
        echo "<script>
                alert('Usuario creado con éxito');
                window.location.href='../../public/index.html';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar usuario');
                window.location.href='../../public/register_user.html';
              </script>";
    }

    $con->close();
    exit;
}

?>
