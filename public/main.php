<?php
# VALIDACIÓN DE SESION ACTIVA
include('..\controllers\validate_exist_sesion.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="icon" href="character/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../views/style/navbar_styles.css">
    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- BOOTSTRAP CDN -->

</head>
<body>


  <main>
    <h1 class="text-center mt-5 pt-5">Contenido principal de la página</h1>
  </main>

  <form action="../controllers/close_session.php" method="post">
                <button class="btn btn-warning mt-4" type="submit" id="cerrarSesionBtn" name="cerrarSesionBtn">Cerrar Sesión</button>
  </form>

</body>
</html>