<?php
session_start();
session_destroy(); #destruyo la sesión
echo "<script> alert('SESSIÓN CERRADA');window.location.href='../public/index.html';</script>";exit;

?>