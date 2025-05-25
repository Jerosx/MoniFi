<?php
# Modulo que contiene en constantes la estructura de la base de datos


class TblUsuarios{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_USUARIO = 'tbl_usuarios';
    /** */

    const ID = 'id';
    /** */

    const NOMBRE_USUARIO = 'nombre_usuario';
    /** */

    const NOMBRE = 'nombre';
    /** */

    const APELLIDOS = 'apellidos';
    /** */

    const CLAVE_USUARIO = 'user_password';
    /** */
};


class TblCuenta{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_USUARIO = 'tbl_cuenta';
    /** */

    const ID = 'id';
    /** */

    const PROPIETARIO = 'propietario';
    /** */

    const NOMBRE = 'nombre';
    /** */

    const VALOR_TOTAL = 'valor_total';
    /** */

    const SALARIO = 'salario';
    /** */

    const ESTADO = 'estado';
    /** */
};


class TblDeuda{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_USUARIO = 'tbl_deuda';
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

    const TIPO_DEUDA_ID = 'tipo_deuda_id';
    /** */
};


class TblTipoDeuda{
    /*Clase que contiene constantes que representan cada una de las tablas de la
    base de datos y sus respectivos campos.*/

    const TBL_USUARIO = 'tipo_deuda';
    /** */

    const ID = 'id';
    /** */

    const NOMBRE = 'nombre';
    /** */
};

?>