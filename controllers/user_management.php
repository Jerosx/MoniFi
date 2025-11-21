<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);
include_once(DB_METADATA_PATH);


function get_password_hash($con, $email) {
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT " . TblUsuarios::CLAVE_USUARIO . "
            FROM " . TblUsuarios::TBL_NAME . "
            WHERE " . TblUsuarios::EMAIL . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()[TblUsuarios::CLAVE_USUARIO];
    }

    return null;
}


function email_exists($con, $email) {
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT " . TblUsuarios::ID . "
            FROM " . TblUsuarios::TBL_NAME . "
            WHERE " . TblUsuarios::EMAIL . " = ?";

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
        $_SESSION['email'] = $email;

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

    $sql = "INSERT INTO " . TblUsuarios::TBL_NAME . " (
                " . TblUsuarios::NOMBRE . ",
                " . TblUsuarios::EMAIL . ",
                " . TblUsuarios::CLAVE_USUARIO . "
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
