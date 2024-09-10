<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Login extends ControllerBase
{

    function __construct(){
        parent::__construct();
    }
    function render(){
        $this->view->render('login/index');
    }
    function acceso()
    {
        try {
            $user = LoginModel::user($_POST);
            if ($user != false && $user['correo'] == $_POST['correo']) {
                if ($user['contrasena'] == encrypt_decrypt('encrypt', $_POST['pass'])) {
                    if ($user['estatus'] == "1") {
                        $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
                        $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['nombre'];
                        $_SESSION['usuario-' . constant('Sistema')] = $user['apellido_paterno'];
                        $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
                        $data = [
                            'estatus' => 'success',
                            'titulo' => 'Bienvenido',
                            'respuesta' => 'Acceso correcto, bienvinido ' . $user['nombre']
                        ];
                    } else {
                        $data = [
                            'estatus' => 'error',
                            'titulo' => 'Ingreso inválido',
                            'respuesta' => 'No tienes autorización para ingresar'
                        ];
                    }
                } else {
                    $data = [
                        'estatus' => 'error',
                        'titulo' => 'Contraseña incorrecta',
                        'respuesta' => 'La contraseña ingresada es incorrecta'
                    ];
                }
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Usuario incorrecto',
                    'respuesta' => 'El usuario ingresado es incorrecto'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas'
            ];
        }
        echo json_encode($data);
    }
    // carruseles
    function viewSalon(){
        try {
            $salon = LoginModel::viewSalon();
            echo json_encode($salon);
        } catch (\Throwable $th) {
            echo "Error en controllador salon: " . $th->getMessage();
            return;
        }
    }
    function viewOficina(){
        try {
            $oficina = LoginModel::viewOficina();
            echo json_encode($oficina);
        } catch (\Throwable $th) {
            echo "Error en controllador oficina: " . $th->getMessage();
            return;
        }
    }
    // mostrar el salon
    function salon($param){
        $this->view->salon = $param[0];
        $this->view->render('login/salon');
    }
    function espacio($param){
        try {
            $contenido = LoginModel::espacio($param[0]);
            echo json_encode($contenido);
        } catch (\Throwable $th) {
            echo "Error en el espacio controllador: " . $th->getMessage();
            return;
        }
    }
    function salir(){
        unset($_SESSION['id_usuario-' . constant('Sistema')]);
        unset($_SESSION['nombre_usuario-' . constant('Sistema')]);
        unset($_SESSION['usuario-' . constant('Sistema')]);
        unset($_SESSION['tipo_usuario-' . constant('Sistema')]);
        /* session_destroy(); */
        header("location:" . constant('URL'));
    }
    // pantalla para mostrar todos los salones
    function salones(){
        $this->view->render('login/salones');
    }
    function mostrarSalones(){
        try {
            $salones = LoginModel::mostrarSalones();
            echo json_encode($salones);
        } catch (\Throwable $th) {
            echo "Error controlador mostrarSalones: " . $th->getMessage();
            return;
        }
    }
    // pantalla para mostrar todos las oficinas
    function oficinas(){
        $this->view->render('login/oficinas');
    }
    function mostrarOficinas(){
        try {
            $oficinas = LoginModel::mostrarOficinas();
            echo json_encode($oficinas);
        } catch (\Throwable $th) {
            echo "Error controlador mostrarOficinas: " . $th->getMessage();
            return;
        }
    }
}
?>