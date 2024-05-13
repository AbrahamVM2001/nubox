<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");
require_once("public/phpqrcode/qrlib.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Admin extends ControllerBase
{
    function __construct()
    {
        parent::__construct();
    }
    /* sesiones */
    function render()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/index");
        } else {
            $this->recargar();
        }
    }

    // Generador de token
    function generarToken()
    {
        $caracteres = "ABCDEFGHIJKLNMOPQRSTUVWXYZ123456789";
        $token = "";
        for ($i = 0; $i < 10; $i++) {
            $token .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $token;
    }
    // empleados

    function empleados()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/empleado");
        } else {
            $this->recargar();
        }
    }
    function viewEmpleado()
    {
        try {
            $empleado = AdminModel::empleado();
            echo json_encode($empleado);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador empleado: " . $th->getMessage();
            return;
        }
    }
    function guardarEmpleado()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $contra_encriptada = encrypt_decrypt('encrypt', $_POST['pass']);
                $resp = AdminModel::guardarEmpleado($_POST, $contra_encriptada);
            } else {
                $contra_encriptada = encrypt_decrypt('encrypt', $_POST['pass']);
                $resp = AdminModel::actualizarEmpleado($_POST, $contra_encriptada);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Empleado creado' : 'Empleado actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creó correctamente el empleado' : 'Se actualizó correctamente el empleado'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Empleado no creada' : 'Empleado no actualizada',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el empleado' : 'No se pudo actualizar correctamente el empleado'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }
    function ActDec_empleado()
    {
        try {
            $id_empleado = $_POST['idSesion'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::ActDesc_empleado($id_empleado, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus del empleado.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus del empleado.'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    function buscarEmpleado($param = null)
    {
        try {
            $empleado = adminModel::buscarEmpleado($param[0]);
            $empleado['password'] = encrypt_decrypt('decrypt', $empleado['password']);
            echo json_encode($empleado);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarUsuario: " . $th->getMessage();
            return;
        }
    }

    // salon
    function salon()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/salon");
        } else {
            $this->recargar();
        }
    }
    function viewSalon()
    {
        try {
            $salon = AdminModel::salon();
            echo json_encode($salon);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador salon: " . $th->getMessage();
            return;
        }
    }
    function buscarTokenSalon($token)
    {
        try {
            $tokensEncontrados = AdminModel::buscarTokenSalon($token);
            return count($tokensEncontrados) > 0;
        } catch (\Throwable $th) {
            echo "Error en el controlador token: " . $th->getMessage();
            return false;
        }
    }
    function limpiarTitulo($titulo)
    {
        $tituloLimpio = preg_replace('/[^a-zA-Z]/', '', $titulo);

        $tituloLimpio = strtoupper($tituloLimpio);

        return $tituloLimpio;
    }

    function guardarSalon()
    {
        try {
            $token = $this->generarToken();
            while ($this->buscarTokenSalon($token)) {
                $token = $this->generarToken();
            }
            $tituloLimpio = $this->limpiarTitulo($_POST['titulo']);

            if ($_POST['tipo'] == 'nuevo') {
                $foto = $_FILES['imagen'];
                $nombreArchivo = $token . $tituloLimpio . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
                $rutaImagen = $this->subirImagenSalon($nombreArchivo, $foto);
                $resp = AdminModel::guardarSalon($_POST, $rutaImagen, $token);
            } else {
                $foto = $_FILES['imagen'];
                $nombreArchivo = $token . $tituloLimpio . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
                $rutaImagen = $this->subirImagenSalon($nombreArchivo, $foto);
                $resp = AdminModel::actualizarSalon($_POST, $rutaImagen, $token);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Salón publicado' : 'Salon actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Salón publicado en la página central' : 'Se actualizó correctamente el salon'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Salón no publicado' : 'Salón no actualizada',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo publicar correctamente el salón' : 'No se pudo actualizar correctamente el salon'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas. Error:' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }


    function subirImagenSalon($nombreArchivo, $foto)
    {
        $directorio = 'public/img/SALONES';
        $rutaCompleta = $directorio . '/' . $nombreArchivo;

        if (isset($foto) && $foto['error'] == UPLOAD_ERR_OK) {
            move_uploaded_file($foto['tmp_name'], $rutaCompleta);
            return $rutaCompleta;
        } else {
            return false;
        }
    }
    function ActDec_salon()
    {
        try {
            $id_salon = $_POST['idsalon'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::ActDesc_salon($id_salon, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus del salon.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus del salon.'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    function buscarSalon($param = null)
    {
        try {
            $salon = adminModel::buscarSalon($param[0]);
            echo json_encode($salon);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarSalon: " . $th->getMessage();
            return;
        }
    }
    // oficinas
    function oficina() {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/oficina");
        } else {
            $this->recargar();
        }
    }
    function viewOficina()
    {
        try {
            $oficina = AdminModel::oficina();
            echo json_encode($oficina);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador oficina: " . $th->getMessage();
            return;
        }
    }
    function buscarTokenOficina($token)
    {
        try {
            $tokensEncontrados = AdminModel::buscarTokenSalon($token);
            return count($tokensEncontrados) > 0;
        } catch (\Throwable $th) {
            echo "Error en el controlador token: " . $th->getMessage();
            return false;
        }
    }
    function guardarOficina()
    {
        try {
            $token = $this->generarToken();
            while ($this->buscarTokenOficina($token)) {
                $token = $this->generarToken();
            }
            $tituloLimpio = $this->limpiarTitulo($_POST['titulo']);

            if ($_POST['tipo'] == 'nuevo') {
                $foto = $_FILES['imagen'];
                $nombreArchivo = $token . $tituloLimpio . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
                $rutaImagen = $this->subirImagenOficina($nombreArchivo, $foto);
                $resp = AdminModel::guardarOficina($_POST, $rutaImagen, $token);
            } else {
                $foto = $_FILES['imagen'];
                $nombreArchivo = $token . $tituloLimpio . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
                $rutaImagen = $this->subirImagenOficina($nombreArchivo, $foto);
                $resp = AdminModel::actualizarOficina($_POST, $rutaImagen, $token);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Oficina publicado' : 'Oficina actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Oficina publicado en la página central' : 'Se actualizó correctamente la Oficina'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Oficina no publicado' : 'Oficina no actualizada',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo publicar correctamente el oficina' : 'No se pudo actualizar correctamente la oficina'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas. Error:' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    function subirImagenOficina($nombreArchivo, $foto)
    {
        $directorio = 'public/img/OFICINAS';
        $rutaCompleta = $directorio . '/' . $nombreArchivo;

        if (isset($foto) && $foto['error'] == UPLOAD_ERR_OK) {
            move_uploaded_file($foto['tmp_name'], $rutaCompleta);
            return $rutaCompleta;
        } else {
            return false;
        }
    }
    function ActDec_oficina()
    {
        try {
            $id_oficina = $_POST['idoficina'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::ActDesc_oficina($id_oficina, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus de la oficina.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus de la oficina.'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    function buscarOficina($param = null)
    {
        try {
            $oficina = adminModel::buscarOficina($param[0]);
            echo json_encode($oficina);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarOficina: " . $th->getMessage();
            return;
        }
    }
    // Reportes
    function asignacion() {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/asignacion");
        } else {
            $this->recargar();
        }
    }
    function viewAsignacion()
    {
        try {
            $asignacion = AdminModel::asginacion();
            echo json_encode($asignacion);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador oficina: " . $th->getMessage();
            return;
        }
    }
}
