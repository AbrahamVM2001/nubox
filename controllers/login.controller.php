<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Login extends ControllerBase
{

    function __construct()
    {
        parent::__construct();
    }
    function render()
    {
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
    function registro()
    {
        try {
            $password = $_POST['passRegistro'];
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
            $_POST['passRegistro'] = encrypt_decrypt('encrypt', $password);
            $resp = LoginModel::registro($_POST);
            if ($resp !== false) {
                $_SESSION['id_usuario-' . constant('Sistema')] = $resp['id_usuario'];
                        $_SESSION['nombre_usuario-' . constant('Sistema')] = $_POST['nombre'];
                        $_SESSION['usuario-' . constant('Sistema')] = $_POST['apellido_paterno'];
                        $_SESSION['tipo_usuario-' . constant('Sistema')] = $_POST['tipo_usuario'];
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Registro exitoso' . '' . $_POST['name'],
                    'respuesta' => ''
                ];
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error al registro',
                    'respuesta' => 'UPS!, vuelve a intentarlo'
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
    // carruseles
    function viewSalon()
    {
        try {
            $salon = LoginModel::viewSalon();
            echo json_encode($salon);
        } catch (\Throwable $th) {
            echo "Error en controllador salon: " . $th->getMessage();
            return;
        }
    }
    function viewOficina()
    {
        try {
            $oficina = LoginModel::viewOficina();
            echo json_encode($oficina);
        } catch (\Throwable $th) {
            echo "Error en controllador oficina: " . $th->getMessage();
            return;
        }
    }
    // mostrar el salon
    function salon($param)
    {
        $this->view->salon = $param[0];
        $this->view->render('login/salon');
    }
    function espacio($param)
    {
        try {
            $contenido = LoginModel::espacio($param[0]);
            echo json_encode($contenido);
        } catch (\Throwable $th) {
            echo "Error en el espacio controllador: " . $th->getMessage();
            return;
        }
    }
    function salir()
    {
        unset($_SESSION['id_usuario-' . constant('Sistema')]);
        unset($_SESSION['nombre_usuario-' . constant('Sistema')]);
        unset($_SESSION['usuario-' . constant('Sistema')]);
        unset($_SESSION['tipo_usuario-' . constant('Sistema')]);
        /* session_destroy(); */
        header("location:" . constant('URL'));
    }
    // pantalla para mostrar todos los salones
    function salones()
    {
        $this->view->render('login/salones');
    }
    function mostrarSalones()
    {
        try {
            $salones = LoginModel::mostrarSalones();
            echo json_encode($salones);
        } catch (\Throwable $th) {
            echo "Error controlador mostrarSalones: " . $th->getMessage();
            return;
        }
    }
    // pantalla para mostrar todos las oficinas
    function oficinas()
    {
        $this->view->render('login/oficinas');
    }
    function mostrarOficinas()
    {
        try {
            $oficinas = LoginModel::mostrarOficinas();
            echo json_encode($oficinas);
        } catch (\Throwable $th) {
            echo "Error controlador mostrarOficinas: " . $th->getMessage();
            return;
        }
    }
    // pago
    function pago($param = null){
        $this->view->pagoEspacio = $param[0];
        $this->view->render("login/pago");
    }
    function registroPago(){
        try {
            $pago = $this->registroReserva();
            if ($pago != false) {
                $resp = $this->registroPago($_POST);
                
            } else {
                # code...
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function registroReserva(){
        try {
            $resp = LoginModel::registroReserva($_POST);
            if ($resp != false) {
                return;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            echo "Error en el controllador registro Reserva: " . $th->getMessage();
            return;
        }
    }
}
