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
    // mostrar graficas para analisis
    function containerVentasTotales(){
        try {
            $containerVentasTotales = AdminModel::containerVentasTotales();
            echo json_encode($containerVentasTotales);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador containerVentasTotales: " . $th->getMessage();
            return;
        }
    }
    function containerClientes(){
        try {
            $containerClientes = AdminModel::containerClientes();
            echo json_encode($containerClientes);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador containerClientes: " . $th->getMessage();
            return;
        }
    }
    function containerEspacios(){
        try {
            $containerEspacios = AdminModel::containerEspacios();
            echo json_encode($containerEspacios);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador containerEspacios: " . $th->getMessage();
            return;
        }
    }
    function containerClientesSinAsignar(){
        try {
            $containerClientesSinAsignar = AdminModel::containerClientesSinAsignar();
            echo json_encode($containerClientesSinAsignar);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador containerClientesSinAsignar: " . $th->getMessage();
            return;
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
    // espacios
    function espacios()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/espacios");
        } else {
            $this->render();
        }
    }
    function viewEspacios()
    {
        try {
            $espacios = AdminModel::espacios();
            echo json_encode($espacios);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador espacios: " . $th->getMessage();
            return;
        }
    }
    function guardarEspacios()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $tipo = $_POST['tipo_espacio'];
                $token = $this->generarToken();
                while ($this->buscarToken($token)) {
                    $token = $this->generarToken();
                }
                $rutaImagen = $this->subirImagen($token, $_FILES['ubicacion'], $tipo);
                $_POST['ubicacion'] = $rutaImagen;
                $_POST['token'] = $token;
                $resp = AdminModel::guardarEspacios($_POST);
            } else {
                $tipo = $_POST['tipo_espacio'];
                $token = $this->generarToken();
                while ($this->buscarToken($token)) {
                    $token = $this->generarToken();
                }
                $rutaImagen = $this->subirImagen($token, $_FILES['ubicacion'], $tipo);
                $_POST['ubicacion'] = $rutaImagen;
                $_POST['token'] = $token;
                $resp = AdminModel::actualizarEspacios($_POST);
            }
            if ($resp !== false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Espacio registrado' : 'Espacio actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Espacio creado correctamente' : 'Se actualizó correctamente el espacio'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Error en el registro' : 'Espacio no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear el espacio' : 'No se pudo actualizar correctamente el espacio'
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
    function activar_desactivar_espacio()
    {
        try {
            $id_espacio = $_POST['id_espacio'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::actualizarEstatusEspacio($id_espacio, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus del espacio.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus del espacio.'
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
    // asignacion_contenido
    function contenido($param = null){
        if ($this->verificarAdmin()) {
            $this->view->asignacion_contenido = $param[0];
            $this->view->tipo = $param[1];
            $this->view->render("admin/contenido");
        } else {
            $this->render();
        }
        
    }
    function viewContenido($param = null)
    {
        try {
            $contenido = AdminModel::contenido($param[0]);
            echo json_encode($contenido);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador contenido: " . $th->getMessage();
            return;
        }
    }
    function generarToken()
    {
        $caracteres = "ABCDEFGHIJKLNMOPQRSTUVWXYZ123456789";
        $token = "";
        for ($i = 0; $i < 18; $i++) {
            $token .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $token;
    }
    function buscarToken($token)
    {
        try {
            $tokensEncontrados = AdminModel::buscarToken($token);
            return count($tokensEncontrados) > 0;
        } catch (\Throwable $th) {
            echo "Error en el controlador token: " . $th->getMessage();
            return false;
        }
    }
    function subirImagen($token, $foto, $tipo)
    {
        $directorio = 'public/contenido/';
        if ($tipo === "1") {
            $directorio .= "salones/";
        } else {
            $directorio .= "oficinas/";
        }
        if (isset($foto) && $foto['error'] == UPLOAD_ERR_OK) {
            $nombreArchivo = $token . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
            $rutaCompleta = $directorio . $nombreArchivo;
            move_uploaded_file($foto['tmp_name'], $rutaCompleta);
            return $rutaCompleta;
        } else {
            return false;
        }
    }
    function guardarContenido()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $tipo = base64_decode(base64_decode($_POST['tipo_contenido']));
                $token = $this->generarToken();
                while ($this->buscarToken($token)) {
                    $token = $this->generarToken();
                }
                $rutaImagen = $this->subirImagen($token, $_FILES['ubicacion'], $tipo);
                $_POST['ubicacion'] = $rutaImagen;
                $_POST['token'] = $token;
                $resp = AdminModel::guardarContenido($_POST);
            } else {
                $tipo = base64_decode(base64_decode($_POST['tipo_contenido']));
                $token = $this->generarToken();
                while($this->buscarToken($token)) {
                    $token = $this->generarToken();
                }
                $rutaImagen = $this->subirImagen($token, $_FILES['ubicacion'], $tipo);
                $_POST['ubicacion'] = $rutaImagen;
                $_POST['token'] = $token;
                $resp = AdminModel::actualizarContenido($_POST);
            }
            if ($resp !== false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Espacio registrado' : 'Espacio actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Espacio creado correctamente' : 'Se actualizó correctamente el espacio'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Error en el registro' : 'Espacio no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear el espacio' : 'No se pudo actualizar correctamente el espacio'
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
    function buscarContenido($param = null)
    {
        try {
            $espacio = adminModel::buscarContenido($param[0]);
            echo json_encode($espacio);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarEspacio: " . $th->getMessage();
            return;
        }
    }
    function activar_desactivar_contenido()
    {
        try {
            $id_asignacion_contenido = $_POST['id_asignacion_contenido'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = adminModel::actualizarEstatusContenido($id_asignacion_contenido, $nuevoEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => 'Se actualizó correctamente el estatus del espacio.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo actualizar el estatus del espacio.'
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
}