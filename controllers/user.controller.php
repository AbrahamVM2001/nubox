<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");
require_once("public/phpqrcode/qrlib.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class User extends ControllerBase
{
    function __construct()
    {
        parent::__construct();
    }
    /* sesiones stream*/
    function render()
    {
        if ($this->verificarUser()) {
            $this->view->render("user/index");
        } else {
            $this->recargar();
        }
    }
    // comprobar estatus
    function comprobar()
    {
        try {
            $estatus = UserModel::comprobar();
            if ($estatus == 2) {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No tienes acceso',
                    'respuesta' => 'Lo siento, tu cuenta ha sido cerrada por incumplimiento.',
                    'redirectUrl' => constant('URL')
                ];
                unset($_SESSION['id_usuario-' . constant('Sistema')]);
                unset($_SESSION['nombre_usuario-' . constant('Sistema')]);
                unset($_SESSION['usuario-' . constant('Sistema')]);
                unset($_SESSION['tipo_usuario-' . constant('Sistema')]);
            } else {
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error del servidor',
                'respuesta' => 'Contacta al área de sistemas. Error:' . $th->getMessage()
            ];
        }
        echo json_encode($data);
    }
    // view stream
    function infoActualConferencia()
    {
        try {
            $infoConferencia = UserModel::infoUltimoConferencia();
            echo json_encode($infoConferencia);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia: " . $th->getMessage();
            return;
        }
    }
    function MostrarComentarios($param = null)
    {
        try {
            $comentario = UserModel::ComentariosView($param[0]);
            echo json_encode($comentario);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador comentario: " . $th->getMessage();
            return;
        }
    }
    function RegistroComentario()
    {
        try {
            $datos = $_POST;
            if (UserModel::registroComentario($datos)) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Comentario registrado',
                    'respuesta' => ''
                ];
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Algo salió mal!',
                    'respuesta' => 'Hubo un error al publicar el comentario. Inténtelo nuevamente.'
                ];
            }
        } catch (\Throwable $th) {
            error_log("Error controlador: " . $th->getMessage());
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas'
            ];
        }
        echo json_encode($data);
    }
    function RegistroPreguntas()
    {
        try {
            $datos = $_POST;
            if (UserModel::registroPregunta($datos)) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Pregunta enviada',
                    'respuesta' => ''
                ];
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Algo salio mal',
                    'respuesta' => 'Hubo un error al mandar la pregunta'
                ];
            }
        } catch (\Throwable $th) {
            error_log("Error controlador: " . $th->getMessage());
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas'
            ];
        }
        echo json_encode($data);
    }
    // iframe votaciones
    function votacion()
    {
        if ($this->verificarUser()) {
            $this->view->render("user/votacion");
        } else {
            $this->recargar();
        }
    }
    function MostrarVotacion($param = null)
    {
        try {
            $votacion = UserModel::mostrarVotacion($param[0]);
            echo json_encode($votacion);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador votacion: " . $th->getMessage();
            return;
        }
    }
    public function respuesta()
    {
        try {
            $datos = $_POST;
            $idOpciones = $_POST['idOpcion'];
            $usuarioExistente = UserModel::verificarUsurario($datos);
            $usuarioLogeado = isset($_SESSION['id_usuario-' . constant('Sistema')]) ? $_SESSION['id_usuario-' . constant('Sistema')] : '';
            if ($usuarioExistente != false && $usuarioExistente['id_fk_pregunta'] == $_POST['idPregunta'] && $usuarioExistente['id_fk_usuario'] == $usuarioLogeado) {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Ya contestaste la pregunta'
                ];
            }else {
                $resultado = UserModel::respuesta($datos, $idOpciones);
                if ($resultado) {
                    if ($datos['opcion'] == 1) {
                        $data = [
                            'estatus' => 'success',
                            'titulo' => 'Respuesta correcta felicidades.'
                        ];
                    } else {
                        $data = [
                            'estatus' => 'error',
                            'titulo' => 'UPS!, respuesta incorrecta.'
                        ];
                    }
                } else {
                    $data = [
                        'estatus' => 'error',
                        'titulo' => 'Error al registrar respuesta',
                        'respuesta' => 'Hubo un error al registrar la respuesta. Por favor, inténtelo de nuevo.'
                    ];
                }
            }
        } catch (\Throwable $th) {
            error_log("Error controlador: " . $th->getMessage());
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error de servidor',
                'respuesta' => 'Contacte al área de sistemas'
            ];
        }
        echo json_encode($data);
    }
}
