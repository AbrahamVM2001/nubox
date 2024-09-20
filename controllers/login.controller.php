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
                        if ($user['tipo_usuario'] == 3) {
                            $data = [
                                'estatus' => 'warning',
                                'titulo' => 'Servicio exclusivo',
                                'respuesta' => 'Servicio para nuestros empleados'
                            ];
                        } else {
                            $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
                            $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['nombre'];
                            $_SESSION['usuario-' . constant('Sistema')] = $user['apellido_paterno'];
                            $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
                            $data = [
                                'estatus' => 'success',
                                'titulo' => 'Bienvenido',
                                'respuesta' => 'Acceso correcto, bienvinido ' . $user['nombre']
                            ];
                        }
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
            $idUsuario = $resp['id_usuario'];
            $name = $_POST['name'];
            $apellidoP = $_POST['apellidoP'];
            $tipo = '3';
            if ($resp['estatus'] == 'success') {
                $_SESSION['id_usuario-' . constant('Sistema')] = $idUsuario;
                $_SESSION['nombre_usuario-' . constant('Sistema')] = $name;
                $_SESSION['usuario-' . constant('Sistema')] = $apellidoP;
                $_SESSION['tipo_usuario-' . constant('Sistema')] = $tipo;
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Registro exitoso',
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
    function imagen($param)
    {
        try {
            $contenido = LoginModel::imagen($param[0]);
            echo json_encode($contenido);
        } catch (\Throwable $th) {
            echo "Error en el espacio controllador: " . $th->getMessage();
            return;
        }
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
    function asignacion($param)
    {
        try {
            $asignacion = LoginModel::asignacion($param[0]);
            echo json_encode($asignacion);
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
    function pago($param = null)
    {
        $this->view->pagoEspacio = $param[0];
        $this->view->render("login/pago");
    }
    function procesamientoPago()
    {
        try {
            require_once('public/vendor/autoload.php');
            \Stripe\Stripe::setApiKey('sk_test_51PxwgDK9TllkJ0UIX9fsNhAhfO5rsuMqYjr31DsSEffdEEvsaMti5bJpmMbQbsZTIT2no8nsxqRjEbLp5rZ1a5v000V5sKouBc');

            // Procesar pago
            $charge = $this->procesarPagoStripe($_POST['stripeToken'], $_POST['total']);
            if (!$charge) {
                return $this->respuestaError('Error al procesar el pago con Stripe');
            }

            // Registrar reserva
            $idReserva = LoginModel::registroReserva($_POST);
            if (!$idReserva) {
                return $this->respuestaError('Error al registrar la reserva');
            }

            $_POST['id_asignacion_reservacion'] = $idReserva;

            // Registrar pago
            if (!LoginModel::registroPago($_POST)) {
                return $this->respuestaError('Error al registrar el pago en la base de datos');
            }

            // Registrar tarjeta
            $tarjeta = $this->registrarTarjeta($charge, $_POST);
            if (!$tarjeta) {
                return $this->respuestaError('Error al registrar la tarjeta');
            }

            // Enviar correo de confirmación
            if (!$this->enviarCorreoConfirmacion($idReserva, $_POST['total'])) {
                return $this->respuestaError('Error al enviar el correo de confirmación');
            }

            // Buscar y asignar usuario
            $usuarioDisponible = LoginModel::buscarUsuarioDisponible();
            if (!$usuarioDisponible || !isset($usuarioDisponible['id_usuario'])) {
                return $this->respuestaError('No hay usuarios disponibles para asignar a esta reservación');
            }

            if (!LoginModel::asignarUsuarioReservacion($usuarioDisponible['id_usuario'], $idReserva)) {
                return $this->respuestaError('Error al asignar el usuario a la reservación');
            }

            return $this->respuestaExito('El pago y asignación se concretaron correctamente');
        } catch (\Throwable $th) {
            echo "Error en el controlador: " . $th->getMessage();
        }
    }
    // Función para procesar pago con Stripe
    private function procesarPagoStripe($token, $total)
    {
        try {
            return \Stripe\Charge::create([
                'amount' => $total * 100, // Stripe maneja cantidades en centavos
                'currency' => 'mxn',
                'description' => 'Pago por reserva',
                'source' => $token,
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    // Función para registrar tarjeta
    private function registrarTarjeta($charge, $postData)
    {
        $postData['cardnumber'] = $charge->source->last4;
        $postData['exp-date'] = $charge->source->exp_month . '/' . $charge->source->exp_year;
        $postData['cardCvc'] = '***';

        return LoginModel::registroTarjeta($postData);
    }
    // Función para enviar correo de confirmación
    private function enviarCorreoConfirmacion($idReserva, $total)
    {
        $extraer = LoginModel::extraerCorreo();
        $fecha = date("Y-m-d h:m:s");

        return $this->correoPago($extraer['correo'], $idReserva, $fecha, $total);
    }
    // Función para retornar respuesta de éxito
    private function respuestaExito($mensaje)
    {
        echo json_encode([
            'estatus' => 'success',
            'titulo' => 'Éxito',
            'respuesta' => $mensaje
        ]);
    }
    // Función para retornar respuesta de error
    private function respuestaError($mensaje)
    {
        echo json_encode([
            'estatus' => 'error',
            'titulo' => 'Error',
            'respuesta' => $mensaje
        ]);
    }
    // envio de correo electronico de la compra
    function correoPago($correo, $id, $fecha, $total)
    {
        $mail = new PHPMailer(true);
        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Correo</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
                    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                <style>
                    body {
                        background-image: url(' . constant("URL") . 'public/img/confeti.gif);
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: cover;
                    }
                    a {
                        text-decoration: none;
                        color: #000;
                        font-weight: 600;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" style="background-color: #9fb8d9;">
                            <img src="' . constant("URL") . 'public/img/logo.png" width="35%" alt="Cabezera del Correo">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mt-5">
                            <h1>¡Gracias por tu reservación.</h1>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5" style="height: 50vh;">
                            <p>Conserva el número de reservación para cualquier referencia de tu compra o darle seguimiento a tu
                                entrega.</p>
                            <p>Número de reservación: <span>' . $id .  '</span></p>
                            <p>Fecha: <span>' . $fecha .  '</span></p>
                            <p>Total: <span>' . $total . '</span></p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 text-center" style="background-color: #9fb8d9;">
                            <p>Grácias por confiar en nosotros.</p>
                            <p>Si necesitas ayuda con la compra o el servicio solo manda un mensaje por whatsapp a 5583417548</p>
                            <p>Distribuido por Nubox S.A. de C.V. Cuautitlán México cond. 32 casa 32 Santa Elena Estado de México
                                54850. RFC: NBUX934567M01.
                                Régimen General de Ley Personas Morales.</p>
                            <p>©<span id="fecha"></span> Reservados todos los derechos. <a href="https://devabraham.com/">Ing.
                                    Abraham Vera</a> | Sitio web de México</p>
                            <ul class="list-unstyled d-flex align-item-center justify-content-center">
                                <li class="ms-3">
                                    <a href="https://www.linkedin.com/in/abraham-vera-713789181/">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                            <circle cx="4" cy="4" r="2" fill="#000000" fill-opacity="0">
                                                <animate fill="freeze" attributeName="fill-opacity" dur="0.15s" values="0;1" />
                                            </circle>
                                            <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="4">
                                                <path stroke-dasharray="12" stroke-dashoffset="12" d="M4 10v10">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.15s"
                                                        dur="0.2s" values="12;0" />
                                                </path>
                                                <path stroke-dasharray="12" stroke-dashoffset="12" d="M10 10v10">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.45s"
                                                        dur="0.2s" values="12;0" />
                                                </path>
                                                <path stroke-dasharray="24" stroke-dashoffset="24"
                                                    d="M10 15c0 -2.76 2.24 -5 5 -5c2.76 0 5 2.24 5 5v5">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.65s"
                                                        dur="0.2s" values="24;0" />
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                                <li class="ms-3">
                                    <a href="https://github.com/AbrahamVM2001">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                            <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2">
                                                <path stroke-dasharray="32" stroke-dashoffset="32"
                                                    d="M12 4c1.67 0 2.61 0.4 3 0.5c0.53 -0.43 1.94 -1.5 3.5 -1.5c0.34 1 0.29 2.22 0 3c0.75 1 1 2 1 3.5c0 2.19 -0.48 3.58 -1.5 4.5c-1.02 0.92 -2.11 1.37 -3.5 1.5c0.65 0.54 0.5 1.87 0.5 2.5c0 0.73 0 3 0 3M12 4c-1.67 0 -2.61 0.4 -3 0.5c-0.53 -0.43 -1.94 -1.5 -3.5 -1.5c-0.34 1 -0.29 2.22 0 3c-0.75 1 -1 2 -1 3.5c0 2.19 0.48 3.58 1.5 4.5c1.02 0.92 2.11 1.37 3.5 1.5c-0.65 0.54 -0.5 1.87 -0.5 2.5c0 0.73 0 3 0 3">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.7s"
                                                        values="32;0" />
                                                </path>
                                                <path stroke-dasharray="12" stroke-dashoffset="12"
                                                    d="M9 19c-1.41 0 -2.84 -0.56 -3.69 -1.19c-0.84 -0.63 -1.09 -1.66 -2.31 -2.31">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.8s" dur="0.2s"
                                                        values="12;0" />
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                    crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                    crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
                    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
                    crossorigin="anonymous"></script>
            </body>
            </html>
        ';
        try {
            $mail->IsSMTP();
            $mail->isHTML(true);
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 465;
            $mail->Username = 'nubox@devabraham.com';
            $mail->Password = 'Nubox##2001';
            $mail->setFrom('nubox@devabraham.com', 'Nubox');

            $mail->AddAddress(trim($correo));

            $mail->Subject = 'Pago Exitoso';
            $mail->Body = $html;
            $mail->AltBody = $html;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            if ($mail->Send()) {
                /* $resp = CartasModel::actualizarCorreoEnviado($profesor['id_profesor'], 'programa','cartapresencial','cartavirtual'); */
                return true;
            } else {
                /* $resp = AdminModel::actualizarCorreoEnviado($datosDestinatario['id_detalle_lista'], $datosCampania['id_campania'], 0); */
                return false;
            }
        } catch (phpmailerException $e) {
            echo "Error phpmailerexception:" . $e->errorMessage();
        } catch (Exception $e) {
            echo "Error Exception:" . $e->getMessage();
        }
    }
    // olvide mi contraseña
    function password()
    {
        $this->view->render("login/password");
    }
    function correo()
    {
        try {
            $user = LoginModel::correo($_POST);
            $numero = $this->generarNumero();
            while ($this->buscarNumero($numero)) {
                $numero = $this->generarNumero();
            }
            $user['numero_verificar'] = $numero;
            $this->confirmacion($user);
            $registroNumero = LoginModel::registroNumero($user);
            $data = [
                'estatus' => 'success',
                'titulo' => 'Correo Enviado',
                'respuesta' => 'Correo enviado correctamente',
                'envio' => $user['id_usuario']
            ];
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas'
            ];
        }
        echo json_encode($data);
    }
    function buscarNumero($token)
    {
        try {
            $numeroEncontrados = LoginModel::buscarNumero($token);
            return count($numeroEncontrados) > 0;
        } catch (\Throwable $th) {
            echo "Error en el controlador token: " . $th->getMessage();
            return false;
        }
    }
    function generarNumero()
    {
        $caracteres = "123456789";
        $token = "";
        for ($i = 0; $i < 4; $i++) {
            $token .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $token;
    }
    function confirmacion($datos)
    {
        $mail = new PHPMailer(true);
        $html = '
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmación</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
                    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                <style>
                    a {
                        text-decoration: none;
                        color: #000;
                        font-weight: 600;
                    }

                    span {
                        color: #ff0000;
                        font-weight: 900;
                        font-size: 36px;
                    }
                </style>
            </head>

            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" style="background-color: #9fb8d9;">
                            <img src="' . constant('URL') . 'public/img/logo.png" width="35%" alt="Cabezera del Correo">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5">
                            <p style="font-size: 24px; font-weight: 600;">Código de verificación es:</p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mt-5 mb-5">
                            <span>' . $datos['numero_verificar'] .  '</span>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5">
                            <p>
                                Para proteger tu información y prevenir fraudes o clonaciones, enviamos un número de verificación
                                que tiene una vigencia de solo 5 minutos desde su emisión. De esta manera, podrás actualizar tu
                                contraseña de forma más segura y eficiente.
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 text-center" style="background-color: #9fb8d9;">
                            <p>Grácias por confiar en nosotros.</p>
                            <p>Si necesitas ayuda con la compra o el servicio solo manda un mensaje por whatsapp a 5583417548</p>
                            <p>Distribuido por Nubox S.A. de C.V. Cuautitlán México cond. 32 casa 32 Santa Elena Estado de México
                                54850. RFC: NBUX934567M01.
                                Régimen General de Ley Personas Morales.</p>
                            <p>©<span id="fecha"></span> Reservados todos los derechos. <a href="https://devabraham.com/">Ing.
                                    Abraham Vera</a> | Sitio web de México</p>
                            <ul class="list-unstyled d-flex align-item-center justify-content-center">
                                <li class="ms-3">
                                    <a href="https://www.linkedin.com/in/abraham-vera-713789181/">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                            <circle cx="4" cy="4" r="2" fill="#000000" fill-opacity="0">
                                                <animate fill="freeze" attributeName="fill-opacity" dur="0.15s" values="0;1" />
                                            </circle>
                                            <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="4">
                                                <path stroke-dasharray="12" stroke-dashoffset="12" d="M4 10v10">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.15s"
                                                        dur="0.2s" values="12;0" />
                                                </path>
                                                <path stroke-dasharray="12" stroke-dashoffset="12" d="M10 10v10">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.45s"
                                                        dur="0.2s" values="12;0" />
                                                </path>
                                                <path stroke-dasharray="24" stroke-dashoffset="24"
                                                    d="M10 15c0 -2.76 2.24 -5 5 -5c2.76 0 5 2.24 5 5v5">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.65s"
                                                        dur="0.2s" values="24;0" />
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                                <li class="ms-3">
                                    <a href="https://github.com/AbrahamVM2001">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                            <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2">
                                                <path stroke-dasharray="32" stroke-dashoffset="32"
                                                    d="M12 4c1.67 0 2.61 0.4 3 0.5c0.53 -0.43 1.94 -1.5 3.5 -1.5c0.34 1 0.29 2.22 0 3c0.75 1 1 2 1 3.5c0 2.19 -0.48 3.58 -1.5 4.5c-1.02 0.92 -2.11 1.37 -3.5 1.5c0.65 0.54 0.5 1.87 0.5 2.5c0 0.73 0 3 0 3M12 4c-1.67 0 -2.61 0.4 -3 0.5c-0.53 -0.43 -1.94 -1.5 -3.5 -1.5c-0.34 1 -0.29 2.22 0 3c-0.75 1 -1 2 -1 3.5c0 2.19 0.48 3.58 1.5 4.5c1.02 0.92 2.11 1.37 3.5 1.5c-0.65 0.54 -0.5 1.87 -0.5 2.5c0 0.73 0 3 0 3">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.7s"
                                                        values="32;0" />
                                                </path>
                                                <path stroke-dasharray="12" stroke-dashoffset="12"
                                                    d="M9 19c-1.41 0 -2.84 -0.56 -3.69 -1.19c-0.84 -0.63 -1.09 -1.66 -2.31 -2.31">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.8s" dur="0.2s"
                                                        values="12;0" />
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                    crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                    crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
                    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
                    crossorigin="anonymous"></script>
            </body>

            </html>
        ';
        try {
            $mail->IsSMTP();
            $mail->isHTML(true);
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 465;
            $mail->Username = 'nubox@devabraham.com';
            $mail->Password = 'Nubox##2001';
            $mail->setFrom('nubox@devabraham.com', 'Nubox');

            $mail->AddAddress(trim($datos['correo']));

            $mail->Subject = 'Número de verificación';
            $mail->Body = $html;
            $mail->AltBody = $html;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            if ($mail->Send()) {
                /* $resp = CartasModel::actualizarCorreoEnviado($profesor['id_profesor'], 'programa','cartapresencial','cartavirtual'); */
                return true;
            } else {
                /* $resp = AdminModel::actualizarCorreoEnviado($datosDestinatario['id_detalle_lista'], $datosCampania['id_campania'], 0); */
                return false;
            }
        } catch (phpmailerException $e) {
            echo "Error phpmailerexception:" . $e->errorMessage();
        } catch (Exception $e) {
            echo "Error Exception:" . $e->getMessage();
        }
    }
    function verificar($param = null)
    {
        $this->view->confirmar = $param[0];
        $this->view->render("login/verificar");
    }
    function verificadorNumero()
    {
        try {
            $_POST['numero'] = $_POST['numero1'] .  $_POST['numero2'] . $_POST['numero3'] . $_POST['numero4'];
            $numeroVerificar = LoginModel::verificadorNumero($_POST);
            if ($numeroVerificar != false && $numeroVerificar['numero'] == $_POST['numero']) {
                $fechaActual = new DateTime();
                $fechaAlmacenada = new DateTime($numeroVerificar['fecha']);
                $diferencia = $fechaActual->diff($fechaAlmacenada);
                $minutos = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;
                if ($minutos < 5) {
                    $data = [
                        'estatus' => 'success',
                        'titulo' => 'Correo',
                        'respuesta' => 'Se verifico el número direccionando a restaurar contraseña',
                        'envio' => $numeroVerificar['fk_usuario']
                    ];
                } else {
                    $data = [
                        'estatus' => 'warning',
                        'titulo' => 'Número incorrecto',
                        'respuesta' => 'Número ya paso de vigencia renvia el número'
                    ];
                }
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error número',
                    'respuesta' => 'Error en el número de confirmación'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error en el controlador',
                'respuesta' => 'Error en el controlador de verificadorNumero: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    function cambiarContrasena($param = null)
    {
        $this->view->cambiarContrasena = $param[0];
        $this->view->render("login/cambiarContrasena");
    }
    function restaurarContrasena()
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
            $_POST['contrasena'] = encrypt_decrypt('encrypt', $password);
            $resp = LoginModel::restaurarContrasena($_POST);
            if ($resp !== false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Contraseña',
                    'respuesta' => 'La contraseña se cambio correctamente'
                ];
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error cambio',
                    'respuesta' => 'No pudimos cambiar contrasena, vuelve a intentar o contacte a soporte tecnico'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error en el controlador',
                'respuesta' => 'Error en el controlador restaurarContrasena: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
}
