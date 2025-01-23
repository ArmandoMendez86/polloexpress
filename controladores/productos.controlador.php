<?php
require_once '../modelos/productos.modelo.php';


class ControladorProductos
{

    static public $tabla = "productos";

    /* ############################# Obetner menu ############################# */
    static public function ctrObtenerProductos()
    {
        $respuesta = ModeloProductos::mdlObtenerProductos(self::$tabla);
        return $respuesta;
    }

    /* ############################# Obtener menu administración ############################# */
    static public function ctrObtenerMenu()
    {
        $respuesta = ModeloProductos::mdlObtenerMenu(self::$tabla);
        return $respuesta;
    }

    /* ############################# Agregar menu ############################# */
    static public function ctrAgregarMenu($datos)
    {

        unset($datos['accion']);
        if (isset($_FILES["img"]["tmp_name"])) {

            list($ancho, $alto) = getimagesize($_FILES["img"]["tmp_name"]);

            $nuevoAncho = 500;
            $nuevoAlto = 500;

            $directorio = "../assets/img";

            $aleatorio = mt_rand(100, 999);
            $ruta = $directorio . "/" . $aleatorio;

            if ($_FILES["img"]["type"] == "image/jpeg" || $_FILES["img"]["type"] == "image/jpg") {
                $ruta .= ".jpg";
                $origen = imagecreatefromjpeg($_FILES["img"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);


                $blanco = imagecolorallocate($destino, 255, 255, 255);
                imagefill($destino, 0, 0, $blanco);

                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                imagejpeg($destino, $ruta);
                $datos["img"] = 'assets/img/' . $aleatorio . '.jpg';
            } elseif ($_FILES["img"]["type"] == "image/png") {
                $ruta .= ".png";
                $origen = imagecreatefrompng($_FILES["img"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);


                imagealphablending($destino, false);
                imagesavealpha($destino, true);

                $colorTransparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
                imagefill($destino, 0, 0, $colorTransparente);

                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                imagepng($destino, $ruta);
                $datos["img"] = 'assets/img/' . $aleatorio . '.png';
            }

            imagedestroy($origen);
            imagedestroy($destino);
        } else {
            $datos['img'] = '';
        }

        $respuesta = ModeloProductos::mdlAgregarMenu(self::$tabla, $datos);
        return $respuesta;
    }

    /* ############################# Editar menu ############################# */
    static public function ctrEditarMenu($datos)
    {

        unset($datos['accion']);

        if (isset($_FILES["img"]["tmp_name"])) {

            list($ancho, $alto) = getimagesize($_FILES["img"]["tmp_name"]);

            $nuevoAncho = 500;
            $nuevoAlto = 500;

            $directorio = "../assets/img";

            $aleatorio = mt_rand(100, 999);
            $ruta = $directorio . "/" . $aleatorio;

            if ($_FILES["img"]["type"] == "image/jpeg" || $_FILES["img"]["type"] == "image/jpg") {
                $ruta .= ".jpg";
                $origen = imagecreatefromjpeg($_FILES["img"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);


                $blanco = imagecolorallocate($destino, 255, 255, 255);
                imagefill($destino, 0, 0, $blanco);

                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                imagejpeg($destino, $ruta);
                $datos["img"] = 'assets/img/' . $aleatorio . '.jpg';
            } elseif ($_FILES["img"]["type"] == "image/png") {
                $ruta .= ".png";
                $origen = imagecreatefrompng($_FILES["img"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);


                imagealphablending($destino, false);
                imagesavealpha($destino, true);

                $colorTransparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
                imagefill($destino, 0, 0, $colorTransparente);

                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                imagepng($destino, $ruta);
                $datos["img"] = 'assets/img/' . $aleatorio . '.png';
            }

            imagedestroy($origen);
            imagedestroy($destino);
        } else {
            $datos['img'] = '';
        }

        $respuesta = ModeloProductos::mdlEditarMenu(self::$tabla, $datos);
        return $respuesta;
    }

    /* ############################# Eliminar menu ############################# */
    static public function ctrEliminarMenu($id)
    {
        $respuesta = ModeloProductos::mdlEliminarMenu(self::$tabla, $id);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerProductos') {
    $respuesta = ControladorProductos::ctrObtenerProductos();
    echo json_encode($respuesta);
}

if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerMenu') {
    $respuesta = ControladorProductos::ctrObtenerMenu();
    echo json_encode(['data' => $respuesta]);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'editarMenu') {
    $respuesta = ControladorProductos::ctrEditarMenu($_POST);
    echo json_encode($respuesta);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'agregarMenu') {
    $respuesta = ControladorProductos::ctrAgregarMenu($_POST);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'eliminarMenu') {
    $respuesta = ControladorProductos::ctrEliminarMenu($_POST['id']);
    echo json_encode($respuesta);
}
