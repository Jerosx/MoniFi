<?php
# Modulo que contiene en constantes la estructura de la base de datos


class TblUsuarios{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_NAME = 'tbl_usuarios';
    /** */

    const ID = 'id';
    /** */

    const NOMBRE = 'nombre';
    /** */

    const EMAIL = 'email';
    /** */

    const CLAVE_USUARIO = 'contrasena';
    /** */

    const FECH_CREACION = 'fecha_creacion';
    /** */
};


class TblCuenta{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_NAME = 'tbl_cuenta';
    /** */

    const ID = 'id';
    /** */

    const USUARIO_ID = 'usuario_id';
    /** */

    const NOMBRE = 'nombre';
    /** */

    const PRESUPUESTO = 'presupuesto';
    /** */

    const ESTADO = 'estado';
    /** */

    const FECHA_CREACION = 'fecha_creacion';
    /** */
};


class TblGasto{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_NAME = 'tbl_gasto';
    /** */

    const ID = 'id';
    /** */

    const CUENTA_ID = 'cuenta_id';
    /** */

    const DESCRIPCION = 'descripcion';
    /** */

    const MONTO = 'monto';
    /** */

    const FECHA = 'fecha';
    /** */

    const TIPO_GASTO_ID = 'tipo_gasto_id';
    /** */
};


class TblTipoGasto{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_NAME = 'tipo_gasto';
    /** */

    const ID = 'id';
    /** */

    const NOMBRE = 'nombre';
    /** */
};

?>