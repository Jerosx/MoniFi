<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);


function validate_password($conection, $username) {
    /*Valida y obtiene la contraseña cifrada asociada al usuario

    Args:
        - $conection: Variable que contiene la conexión a la bd.
        - $username: Usuario ingresado.

    Return:
        Contraseña del usuario cifrada.

     */

    $username = mysqli_real_escape_string($conection, $username);

    $sql = "SELECT user_password FROM tbl_usuarios WHERE nombre_usuario = '$username'";
    $result = mysqli_query($conection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['user_password'];
    }

    return null;
}


function validate_username($username, $conection) {
    /*Verifica si el nombre de usuario existe en la base de datos.

    Args:
        - $conection: Variable que contiene la conexión a la bd.
        - $username: Usuario ingresado.

    Return:
        Cantidad de registros encontrados en la bd para ese usuario.

     */

    $username = mysqli_real_escape_string($conection, $username);

    $sql = "SELECT id FROM tbl_usuarios WHERE nombre_usuario = '$username'";
    $result = mysqli_query($conection, $sql);

    return ($result && mysqli_num_rows($result) > 0);
}


function validate_credentials($username, $password) {
    /*Valida si el usuario ingresado existe en la bd y después valida la contraseña
    ingresada, en caso de que no exista el usuario o la contraseña sea equivocada
    lanza una advertencia.

    Args:
        - $username: Usuario ingresado.
        - $password: Contraseña ingresada.
    
    Return:
        Si las credenciales son correctas le da al usuario acceso al aplicativo.

     */

    $conection = create_conection();

    if (!validate_username($username, $conection)) {
        echo "<script> alert('Usuario no existente');
                            window.location.href='../../views/index.html';
            </script>";
        exit;
    }

    $stored_password = validate_password($conection, $username);

    if ($stored_password && password_verify($password, $stored_password)) {
        session_start();
        $_SESSION['usuario'] = $username;
        header("Location: ../../views/main.html");
        exit;
    } else {
        // Contraseña incorrecta
        echo "<script> alert('Contraseña incorrecta.);
                            window.location.href='../../views/index.html';
            </script>";
        exit;
    }
    $conection->close();
}


function insert_user_in_database($name, $lastname, $username, $password, $conection){
    /* Realiza el insert de los datos de registro a la base de datos */

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conection->prepare("INSERT INTO tbl_usuarios (nombre_usuario, nombre, apellidos, user_password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $name, $lastname, $hashed_password);

    $success = $stmt->execute();

    $stmt->close();
    return $success;
}


function register_user($name, $lastname, $username, $password){
    /* Registra un nuevo usuario después de validar su existencia */

    $conection = create_conection();

    if (validate_username($username, $conection)) {
        echo "<script> alert('Usuario ya en uso.');
                        window.location.href='../../views/register_user.html';
              </script>";
        $conection->close();
        exit;
    }

    $register_user = insert_user_in_database($name, $lastname, $username, $password, $conection);

    if ($register_user) {
        echo "<script> alert('Usuario registrado con éxito.');
                        window.location.href='../../views/index.html';
              </script>";
    } else {
        echo "<script> alert('Error al registrar usuario.');
                        window.location.href='../../views/index.html';
              </script>";
    }

    $conection->close();
    exit;
}

?>
