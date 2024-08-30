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
    /* inicio */
    function render()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/index");
        } else {
            $this->recargar();
        }
    }
    // usuario
    function usuario()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/usuario");
        } else {
            $this->recargar();
        }
    }
    function viewUsuario()
    {
        try {
            $usuario = AdminModel::usuario();
            echo json_encode($usuario);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador usuario: " . $th->getMessage();
            return;
        }
    }
    function guardarUsuario()
    {
        try {
            $password = $_POST['pass'];
            $errors = [];
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Debe contener al menos una mayúscula.';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Debe contener al menos una minúscula.';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Debe contener al menos un número.';
            }
            if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
                $errors[] = 'Debe contener al menos un carácter especial.';
            }
            if (strlen($password) < 8 || strlen($password) > 32) {
                $errors[] = 'La contraseña debe tener entre 8 y 32 caracteres.';
            }
            if (!empty($errors)) {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error de validación',
                    'respuesta' => implode(' ', $errors)
                ];
                echo json_encode($data);
                return;
            }
            $_POST['pass'] = encrypt_decrypt('encrypt', $password);
            if ($_POST['tipo'] == 'nuevo') {
                $resp = AdminModel::guardarUsuario($_POST);
            } else {
                $resp = AdminModel::actualizarUsuario($_POST);
            }
            if ($resp !== false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Usuario registrado' : 'Usuario actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Usuario creado correctamente' : 'Se actualizó correctamente el usuario'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Error en el registro' : 'Usuario no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear el usuario' : 'No se pudo actualizar correctamente el usuario'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error del servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
        }

        echo json_encode($data);
    }
    function buscarUsuario($param = null)
    {
        try {
            $usuario = adminModel::buscarUsuario($param[0]);
            $usuario['contrasena'] = encrypt_decrypt('decrypt', $usuario['contrasena']);
            echo json_encode($usuario);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarUsuario: " . $th->getMessage();
            return;
        }
    }
    function activar_desactivar_usuario()
    {
        try {
            $id_usuario = $_POST['id_usuario'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::actualizarEstatusUsuario($id_usuario, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus del usuario.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus del usuario.'
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
    // salones
    function salon()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/salon");
        } else {
            $this->render();
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
    function guardarSalon()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = AdminModel::guardarSalon($_POST);
            } else {
                $resp = AdminModel::actualizarSalon($_POST);
            }
            if ($resp !== false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Salon registrado' : 'Salon actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Salon creado correctamente' : 'Se actualizó correctamente el salon'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Error en el registro' : 'Salon no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear el salon' : 'No se pudo actualizar correctamente el salon'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error del servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
        }

        echo json_encode($data);
    }
    function pais()
    {
        try {
            $pais = AdminModel::pais();
            echo json_encode($pais);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador pais: " . $th->getMessage();
            return;
        }
    }
    function estado()
    {
        try {
            $id_pais = $_GET['id_pais'];
            $catEstado = AdminModel::estado($id_pais);
            echo json_encode($catEstado);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador estado " . $th->getMessage();
        }
    }
    function buscarEspacio($param = null)
    {
        try {
            $espacio = adminModel::buscarEspacio($param[0]);
            echo json_encode($espacio);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarEspacio: " . $th->getMessage();
            return;
        }
    }
}
