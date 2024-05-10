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
            $nuevoEstatus = base64_decode($_POST['estatus']);
            $nuevoEstatus = ($nuevoEstatus == 1) ? 2 : 1;
            $resp = adminModel::ActDesc_empleado($id_empleado, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus de la conferencia.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus de la conferencia.'
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
}
