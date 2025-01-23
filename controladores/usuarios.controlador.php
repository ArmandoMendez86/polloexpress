<?php
date_default_timezone_set('America/Mexico_City');
require_once '../modelos/usuarios.modelo.php';


class ControladorUsuarios
{

    static public $tabla = "empleados";

    static public function ctrIngresoUsuario($datos)
    {

        unset($datos['accion']);

        $usuario = $datos['usuario'];
        $contraseñaIngresada = $usuario["password"];

        $item = "usuario";
        $valor = $usuario["usuario"];

        $respuesta = ModeloUsuario::MdlObtenerUsuario(self::$tabla, $item, $valor);

        if ($respuesta && password_verify($contraseñaIngresada, $respuesta["password"])) {

            if ($respuesta["estado"] == 1) {
                session_start();
                $_SESSION["iniciarSesion"] = "ok";
                $_SESSION["id"] = $respuesta["id"];
                $_SESSION["nombre"] = $respuesta["nombre"];
                $_SESSION["apellido"] = $respuesta["apellido"];
                $_SESSION["usuario"] = $respuesta["usuario"];
                $_SESSION["estado"] = $respuesta["estado"];
                $_SESSION["rol"] = $respuesta["rol"];


                $fecha = date("Y-m-d");
                $hora = date("H:i:s");

                $fechaActual = $fecha . " " . $hora;

                $item1 = "ultimo_login";
                $valor1 = $fechaActual;
                $item2 = "id";
                $valor2 = $respuesta["id"];

                $registrarUltimoLogin = ModeloUsuario::mdlActivarUsuario(self::$tabla, $item1, $valor1, $item2, $valor2);

                if ($registrarUltimoLogin == "ok") {
                    echo json_encode(['status' => 'success', 'redirect' => 'productos']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cuenta inactiva, consulta al administrador!']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario o contraseña incorrecta']);
        }
    }

    static public function ctrObtenerUsuarios()
    {
        $respuesta = ModeloUsuario::mdlObtenerUsuarios(self::$tabla);
        return $respuesta;
    }


    static  public function ctrActualizarEstado($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloUsuario::mdlActualizarEstado(self::$tabla, $datos);
        return $respuesta;
    }

    static  public function ctrActualizarUsuario($datos)
    {
        unset($datos['accion']);
        $datos = json_decode($datos['info'], true);
        $respuesta = ModeloUsuario::mdlActualizarUsuario(self::$tabla, $datos);
        return $respuesta;
    }

    static  public function ctrAgregarUsuario($datos)
    {
        unset($datos['accion']);
        $datos = json_decode($datos['usuario'], true);
        $respuesta = ModeloUsuario::mdlAgregarUsuario(self::$tabla, $datos);
        return $respuesta;
    }

    public static function ctrEliminarUsuario($datos)
    {
        unset($datos['accion']);
        $id = $datos['id'];
        $respuesta = ModeloUsuario::mdlEliminarUsuario(self::$tabla, $id);
        return $respuesta;
    }

    public static function ctrCerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Sesión cerrada']);
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerUsuarios') {
    $respuesta = ControladorUsuarios::ctrObtenerUsuarios();
    echo json_encode(['data' => $respuesta]);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizarStatusUsuario') {
    $respuesta = ControladorUsuarios::ctrActualizarEstado($_POST);
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizarDatosUsuario') {
    $respuesta = ControladorUsuarios::ctrActualizarUsuario($_POST);
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'nuevoUsuario') {
    $respuesta = ControladorUsuarios::ctrAgregarUsuario($_POST);
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminarUsuario') {
    $respuesta = ControladorUsuarios::ctrEliminarUsuario($_POST);
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'ingresoUsuario') {
    $respuesta = ControladorUsuarios::ctrIngresoUsuario($_POST);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'cerrarSesion') {
    $respuesta = ControladorUsuarios::ctrCerrarSesion();
}
