<?php
# Clases que representan la estructura de las tablas de la base de datos.


class Usuario {
    const TBL_NAME = 'usuario';

    const ID = 'id';
    const NOMBRE = 'nombre';
    const EMAIL = 'email';
    const CONTRASENA = 'contrasena';
    const FECHA_CREACION = 'fecha_creacion';
}


class Estado {
    const TBL_NAME = 'estado';

    const ID = 'id';
    const NOMBRE = 'nombre';
}


class Cuenta {
    const TBL_NAME = 'cuenta';

    const ID = 'id';
    const USUARIO_ID = 'usuario_id';
    const NOMBRE = 'nombre';
    const PRESUPUESTO = 'presupuesto';
    const ESTADO_ID = 'estado_id';
    const FECHA_CREACION = 'fecha_creacion';
}


class CategoriaGasto {
    const TBL_NAME = 'categoria_gasto';

    const ID = 'id';
    const NOMBRE = 'nombre';
    const NECESIDADES_ID = 1;
    const GUSTOS_ID = 2;
}


class SubcategoriaGasto {
    const TBL_NAME = 'subcategoria_gasto';

    const ID = 'id';
    const CATEGORIA_ID = 'categoria_id';
    const NOMBRE = 'nombre';
}


class Gasto {
    const TBL_NAME = 'gasto';

    const ID = 'id';
    const CUENTA_ID = 'cuenta_id';
    const DESCRIPCION = 'descripcion';
    const MONTO = 'monto';
    const FECHA = 'fecha';
    const SUBCATEGORIA_GASTO_ID = 'subcategoria_gasto_id';
}
?>