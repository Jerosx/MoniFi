<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);
include_once(DB_METADATA_PATH);


function validate_password($conection, $username) {
    $username = mysqli_real_escape_string($conection, $username);

    $sql = "SELECT " . TblUsuarios::CLAVE_USUARIO .
           " FROM " . TblUsuarios::TBL_USUARIO .
           " WHERE " . TblUsuarios::NOMBRE_USUARIO . " = '$username'";

    $result = mysqli_query($conection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[TblUsuarios::CLAVE_USUARIO];
    }

    return null;
}


function validate_username($username, $conection) {
    $username = mysqli_real_escape_string($conection, $username);

    $sql = "SELECT " . TblUsuarios::ID .
           " FROM " . TblUsuarios::TBL_USUARIO .
           " WHERE " . TblUsuarios::NOMBRE_USUARIO . " = '$username'";

    $result = mysqli_query($conection, $sql);

    return ($result && mysqli_num_rows($result) > 0);
}


function validate_credentials($username, $password) {
    $conection = create_conection();

    if (!validate_username($username, $conection)) {
        echo "<script> alert('Usuario no existente');
                        window.location.href='../../public/index.html';
              </script>";
        exit;
    }

    $stored_password = validate_password($conection, $username);

    if ($stored_password && password_verify($password, $stored_password)) {
        session_start();
        $_SESSION['usuario'] = $username;
        header("Location: ../../public/main.php");
        exit;
    } else {
        echo "<script> alert('Contraseña incorrecta.');
                        window.location.href='../../public/index.html';
              </script>";
        exit;
    }

    $conection->close();
}


function insert_user_in_database($name, $lastname, $username, $password, $conection){
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO " . TblUsuarios::TBL_USUARIO . " (" .
           TblUsuarios::NOMBRE_USUARIO . ", " .
           TblUsuarios::NOMBRE . ", " .
           TblUsuarios::APELLIDOS . ", " .
           TblUsuarios::CLAVE_USUARIO .
           ") VALUES (?, ?, ?, ?)";

    $stmt = $conection->prepare($sql);
    $stmt->bind_param("ssss", $username, $name, $lastname, $hashed_password);

    $success = $stmt->execute();

    $stmt->close();
    return $success;
}


function register_user($name, $lastname, $username, $password){
    $conection = create_conection();

    if (validate_username($username, $conection)) {
        echo "<script> alert('Usuario ya en uso.');
                        window.location.href='../../public/register_user.html';
              </script>";
        $conection->close();
        exit;
    }

    $register_user = insert_user_in_database($name, $lastname, $username, $password, $conection);

    if ($register_user) {
        echo "<script> alert('Usuario registrado con éxito.');
                        window.location.href='../../public/index.html';
              </script>";
    } else {
        echo "<script> alert('Error al registrar usuario.');
                        window.location.href='../../public/index.html';
              </script>";
    }

    $conection->close();
    exit;
}

?>
