<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");
require_once("public/phpqrcode/qrlib.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class usuario extends ControllerBase
{
    function __construct()
    {
        parent::__construct();
    }
    function render()
    {
        if ($this->verificarUser()) {
            $this->view->render("usuario/index");
        } else {
            $this->recargar();
        }
    }
    // mostrar asignacion
    function asignacion()
    {
        try {
            $asignacion = usuarioModel::asignacion();
            echo json_encode($asignacion);
        } catch (\Throwable $th) {
            echo "Error en el controlador asignacion: " . $th->getMessage();
            return;
        }
    }
    // marcar como terminado
    function terminado()
    {
        try {
            $id_asignacion_usuario_reservacion = $_POST['id_asignacion_usuario_reservacion'];
            $nuevoEstatus = $_POST['estatus'];
            $nuevoEstatus = ($nuevoEstatus == "MQ==") ? "MA==" : "MQ==";
            $resp = usuarioModel::terminado($id_asignacion_usuario_reservacion, $nuevoEstatus);
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
    // generar reporte
    function buscarUsuario($param = null)
    {
        try {
            $usuario = usuarioModel::buscarUsuario($param[0]);
            echo json_encode($usuario);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarUsuario: " . $th->getMessage();
            return;
        }
    }
    function generarReporte()
    {
        try {
            $rutaPDF = $this->generarPDF($_POST);
            $_POST['pdf'] = $rutaPDF;
            $correo = $this->correoReporte($_POST);
            if ($correo != false) {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error correo',
                    'respuesta' => 'No se pudo mandar el correo'
                ];
            }
            $data = [
                'estatus' => 'success',
                'titulo' => 'Correo Enviado',
                'respuesta' => 'El correo ha sido enviado correctamente'
            ];
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error controlador',
                'respuesta' => 'Mensaje del controlador en error: ' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    public function generarPDF($datos)
    {
        try {
            $rutaCarpeta = __DIR__ . '/../public/contenido/documento/';
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetMargins(25, 25, 25);
            $pdf->SetY(25);
            $rutaImagen = __DIR__ . '/../public/img/Reporte.jpg';
            $pdf->Image($rutaImagen, 0, 0, 210, 297);
            $titulo = mb_convert_encoding($datos['titulo'], 'ISO-8859-1', 'UTF-8');
            $descripcion = mb_convert_encoding($datos['desc'], 'ISO-8859-1', 'UTF-8');
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetY(25);
            $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 10, $descripcion);
            $rutaArchivo = $rutaCarpeta . 'Reporte.pdf';
            $pdf->Output('F', $rutaArchivo);
            return $rutaArchivo;
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    function correoReporte($datos)
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
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" style="background-color: #ff0000; color: #fff;">
                            <img src="' . constant("URL") . 'public/img/logo.png" alt="logo" width="35%">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                            <h1 class="text-danger">UPS!, Reporte por incumplimiento de nuestras normas</h1>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <p>Hola, <span class="fw-bold">Nombre del cliente</span>:</p>
                            <p>Nuestro empleado asignado ha enviado un reporte sobre el incumplimiento de nuestras normas. De
                                acuerdo con nuestros acuerdos, si se trata de un salón, tendrás 1 hora para desalojar el lugar; de
                                no hacerlo, se contactará a la patrulla. En el caso de una oficina, tendrás aproximadamente 2 días
                                para desocupar el espacio. Te adjuntamos la información detallada del reporte recibido.</p>
                            <p>Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarnos.</p>
                            <p>Saludos cordiales, Nubox S.A. de C.V.</p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 text-center" style="background-color: #ff0000; color: #fff;">
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
            $mail->addAttachment($datos['pdf'], 'Reporte.pdf');
            $mail->AddAddress(trim($datos['correo']));
            $mail->Subject = 'Notificación de Incumplimiento de Normativas';
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
}
