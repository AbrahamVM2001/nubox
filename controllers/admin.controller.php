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
    // comprobar estatus
    function comprobar()
    {
        try {
            $estatus = AdminModel::comprobar();
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
                $data = [];
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
    // conferencia
    function conferencia()
    {
        try {
            $conferencia = AdminModel::conferencia();
            echo json_encode($conferencia);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia: " . $th->getMessage();
            return;
        }
    }
    function guardarConferencia()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = AdminModel::guardarConferencia($_POST);
            } else {
                $resp = AdminModel::actualizarConferencia($_POST);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Conferencia creado' : 'Conferencia actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creó correctamente la conferencia' : 'Se actualizó correctamente la conferencia'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Conferencia no creada' : 'Conferencia no actualizada',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente la conferencia' : 'No se pudo actualizar correctamente la conferencia'
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
    function buscarConferencia($param = null)
    {
        try {
            $sesiones = adminModel::buscarConferencias($param[0]);
            echo json_encode($sesiones);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarUsuario: " . $th->getMessage();
            return;
        }
    }
    function buscarConferenciaReplica($param = null)
    {
        try {
            $sesiones = adminModel::buscarConferenciasReplica($param[0]);
            echo json_encode($sesiones);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarUsuario: " . $th->getMessage();
            return;
        }
    }
    function desahabilitar_conferencia($param = null)
    {
        try {
            $id_sesiones = $_POST['idSesion'];
            $nuevoEstatus = base64_decode($_POST['estatus']);
            $nuevoEstatus = ($nuevoEstatus == 1) ? 2 : 1;
            $resp = adminModel::actualizarEstatusConferencia($id_sesiones, $nuevoEstatus);

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
    // logeo
    function logeo()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/logeo");
        } else {
            $this->recargar();
        }
    }
    function muestraLogeo()
    {
        try {
            $logeo = AdminModel::logeo();
            echo json_encode($logeo);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia: " . $th->getMessage();
            return;
        }
    }
    function desahabilitar_usuario($param = null)
    {
        try {
            $idUsuario = $_POST['idUsuario'];
            $nuevoEstatus = base64_decode($_POST['estatus']);
            $nuevoEstatus = ($nuevoEstatus == 1) ? 2 : 1;
            $data = [
                'estatus' => 'success',
                'titulo' => $nuevoEstatus
            ];
            $resp = adminModel::actualizarEstatusUsuario($idUsuario, $nuevoEstatus);

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
    // estadisticas
    function estadisticas()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/estadisticas");
        } else {
            $this->recargar();
        }
    }

    function conteoLogeo($param = null)
    {
        try {
            $id_sesiones = $param[0];
            $conteo = adminModel::conteoLogeoAsistentes($id_sesiones);
            echo json_encode($conteo);
        } catch (\Throwable $th) {
            echo "Error en el controlador conteoLogeo: " . $th->getMessage();
        }
    }

    function tablaPaisesLogeoados($param = null)
    {
        try {
            $id_sesiones = $param[0];
            $tabla = AdminModel::tablaPaisesLogeo($id_sesiones);
            echo json_encode($tabla);
        } catch (\Throwable $th) {
            echo "Error en el controlador mostrarTablaPaises: " . $th->getMessage();
        }
    }
    function tablaConectividadLogeados($param = null)
    {
        try {
            $id_sesiones = $param[0];
            $tabla = AdminModel::tablaConectividadLogeados($id_sesiones);
            echo json_encode($tabla);
        } catch (\Throwable $th) {
            echo "Error en el controlador mostrarTablaPaises: " . $th->getMessage();
        }
    }
    function tablaEstadoLogeados($param = null)
    {
        try {
            $id_sesiones = $param[0];
            $tabla = AdminModel::tablaEstadoLogeo($id_sesiones);
            echo json_encode($tabla);
        } catch (\Throwable $th) {
            echo "Error en el controlador mostrarTablaPaises: " . $th->getMessage();
        }
    }
    function tablaVotosLogeados($param = null)
    {
        try {
            $id_sesiones = $param[0];
            $tabla = AdminModel::tablaVotosLogeo($id_sesiones);
            echo json_encode($tabla);
        } catch (\Throwable $th) {
            echo "Error en el controlador mostrarTablaPaises: " . $th->getMessage();
        }
    }
    public function generarPDF()
    {
        try {
            $tablaData = json_decode($_POST['tablaData'], true);

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);
            $columnWidth = 50;
            $pdf->Cell($columnWidth, 10, 'Nombre completo', 1, 0, 'C');
            $pdf->Cell($columnWidth, 10, 'Dispositivo', 1, 0, 'C');
            $pdf->Cell($columnWidth, 10, 'Fecha y Hora', 1, 1, 'C');

            $maxHeight = 10;
            foreach ($tablaData as $row) {
                $yBefore = $pdf->GetY();
                $xBefore = $pdf->GetX();

                $pdf->Cell($columnWidth, 10, $row[0], 1);
                $xAfter = $pdf->GetX();
                $pdf->SetXY($xAfter, $yBefore);
                $pdf->MultiCell($columnWidth, 10, $row[1], 1);
                $yAfter = $pdf->GetY();
                $pdf->SetXY($xAfter + $columnWidth, $yBefore);
                $pdf->Cell($columnWidth, 10, $row[2], 1, 0, '', 0, '', 0, false, 'T', 'T');
                $pdf->SetXY($xBefore, $yAfter);
            }

            $pdf->Ln();

            $pdfFileName = 'reporte.pdf';
            $pdf->Output('F', $pdfFileName);

            echo json_encode(array('url' => $pdfFileName));
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    public function generarPDFEstado()
    {
        try {
            $tablaData = json_decode($_POST['tablaData'], true);

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);
            $columnWidth = 50;
            $pdf->Cell($columnWidth, 10, 'Estado', 1, 0, 'C');
            $pdf->Cell($columnWidth, 10, 'Conteo', 1, 1, 'C');
            foreach ($tablaData as $row) {
                $pdf->Cell($columnWidth, 10, $row[0], 1);
                $pdf->Cell($columnWidth, 10, $row[1], 1, 1, 'C');
            }
            $pdfFileName = 'reporte.pdf';
            $pdf->Output('F', $pdfFileName);

            echo json_encode(array('url' => $pdfFileName));
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    public function generarPDFVotos()
    {
        try {
            $tablaData = json_decode($_POST['tablaData'], true);

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);
            $columnWidth = 50;
            $pdf->Cell($columnWidth, 10, 'Nombre completo', 1, 0, 'C');
            $pdf->Cell($columnWidth, 10, 'Contestadas', 1, 0, 'C');
            $pdf->Cell($columnWidth, 10, 'Aciertos', 1, 1, 'C');

            $maxHeight = 10;
            foreach ($tablaData as $row) {
                $yBefore = $pdf->GetY();
                $xBefore = $pdf->GetX();

                $pdf->Cell($columnWidth, 10, $row[0], 1);
                $xAfter = $pdf->GetX();
                $pdf->SetXY($xAfter, $yBefore);
                $pdf->MultiCell($columnWidth, 10, $row[1], 1);
                $yAfter = $pdf->GetY();
                $pdf->SetXY($xAfter + $columnWidth, $yBefore);
                $pdf->Cell($columnWidth, 10, $row[2], 1, 0, '', 0, '', 0, false, 'T', 'T');
                $pdf->SetXY($xBefore, $yAfter);
            }

            $pdf->Ln();

            $pdfFileName = 'reporte.pdf';
            $pdf->Output('F', $pdfFileName);

            echo json_encode(array('url' => $pdfFileName));
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }

    // preguntas
    function preguntas()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/preguntas");
        } else {
            $this->recargar();
        }
    }
    function conferenciaVotacion()
    {
        try {
            $conferencia = AdminModel::conferenciaVotacion();
            echo json_encode($conferencia);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia votacion: " . $th->getMessage();
            return;
        }
    }
    public function guardarVotacion()
    {
        try {
            $resp = AdminModel::guardarVotacion($_POST);
            if ($resp != false) {
                $idPregunta = $resp;
                $opciones = array();
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'opcion_') === 0) {
                        $indice = substr($key, strlen('opcion_'));
                        $opcion = htmlspecialchars($value);
                        $respuesta = isset($_POST['respuesta_' . $indice]) ? intval($_POST['respuesta_' . $indice]) : 0;
                        $opciones[] = array(
                            'opcion' => $opcion,
                            'respuesta' => $respuesta
                        );
                    }
                }
                $opcionesGuardadas = array();
                $sesion = $_POST['sesion'];
                foreach ($opciones as $opcion) {
                    $guardarOpcionRespuesta = AdminModel::guardarOpcion($idPregunta, $opcion, $sesion);
                    if ($guardarOpcionRespuesta != false) {
                        $opcionesGuardadas[] = $opcion;
                    }
                }

                if (!empty($opcionesGuardadas)) {
                    $data = [
                        'estatus' => 'success',
                        'titulo' => 'Votacion creado',
                        'respuesta' => 'Se creó la votación correctamente'
                    ];
                } else {
                    $data = [
                        'estatus' => 'warning',
                        'titulo' => 'No se crearon opciones',
                        'respuesta' => 'No se crearon las opciones correctamente'
                    ];
                }
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No se crearon preguntas',
                    'respuesta' => 'No se crearon las preguntas correctamente'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas. Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }
    function buscarVotacion($param = null)
    {
        try {
            $votacion = adminModel::buscarVotacion($param[0]);
            echo json_encode($votacion);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarVotacion: " . $th->getMessage();
            return;
        }
    }
    public function actualizarVotacion()
    {
        try {
            $opcion = [
                'opcion' => $_POST['opcion'],
                'correcta' => $_POST['correcta'],
                'idOpcion' => $_POST['id_opcion']
            ];

            $data = [
                'estatus' => 'warning',
                'titulo' => $opcion
            ];

            $resultado = AdminModel::actualizarVotacion($opcion);

            if ($resultado) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Actualización correcta',
                    'respuesta' => 'Se ha actualizado la votacion correctamente'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No se actualizó',
                    'respuesta' => 'No se actualizó correctamente la votacion'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas. Error' . $th->getMessage()
            ];
        }

        echo json_encode($data);
    }
    public function guardarnewVotacion()
    {
        try {
            $resp = AdminModel::guardarNewOpcion($_POST);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Voto nuevo',
                    'respuesta' => 'Nuevo voto insertado correctamente'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No se crearon preguntas',
                    'respuesta' => 'No se crearon las preguntas correctamente'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas. Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }
    function activaDesactiva($param = null)
    {
        try {
            $idPregunta = $_POST['id_pregunta'];
            $nuevoEstatus = $_POST['estatus'];
            $newEstatus = ($nuevoEstatus == 1) ? 0 : 1;
            $resp = adminModel::activaDesactiva($idPregunta, $newEstatus);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Operación exitosa',
                    'respuesta' => ($nuevoEstatus == '1') ? 'Se desactivo la votacion' : 'Se activo la votacion',
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Error al actualizar',
                    'respuesta' => 'No se pudo ni activar ni desactivar la votacion.'
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
    function borrarVoto($param = null)
    {
        try {
            $resp = AdminModel::borrarVoto($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Eliminado',
                    'respuesta' => 'El voto se elimino correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No se elimino',
                    'respuesta' => 'El voto no se elimino correctamente.'
                ];
            }
        } catch (Exception $e) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'No podemos borrar autor. Error: ' . $e->getMessage()
            ];
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'warning',
                'titulo' => 'Pregunta en uso ',
                'respuesta' => 'La pregunta esta siendo usada en muchos capos. Error: ' . $th->getMessage()
            ];
        }

        echo json_encode($data);
    }
    function borrarOpcion($param = null)
    {
        try {
            $resp = AdminModel::borrarOpicion($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'Eliminado',
                    'respuesta' => 'El voto se elimino correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'No se elimino',
                    'respuesta' => 'El voto no se elimino correctamente.'
                ];
            }
        } catch (Exception $e) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'No podemos borrar autor. Error: ' . $e->getMessage()
            ];
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'warning',
                'titulo' => 'Pregunta en uso ',
                'respuesta' => 'La pregunta esta siendo usada en muchos capos. Error: ' . $th->getMessage()
            ];
        }

        echo json_encode($data);
    }
    // votacion por segundo
    function votacion($param = null)
    {
        if ($this->verificarAdmin()) {
            $this->view->pregunta = $param[0];
            $this->view->render("admin/votaciones");
        } else {
            $this->recargar();
        }
    }
    function MostrarVotacion($param = null)
    {
        try {
            $votacion = AdminModel::mostrarVotacion($param[0]);
            echo json_encode($votacion);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador votacion: " . $th->getMessage();
            return;
        }
    }
    function activaDesactivaPorSegundo()
    {
        try {
            $idPregunta = $_POST['id_pregunta'];
            $resp = adminModel::activaDesactivaPorSegundo($idPregunta);
            if ($resp != false) {
                // $data = [
                //     'estatus' => 'success',
                //     'titulo' => $idPregunta
                // ];
            } else {
                // $data = [
                //     'estatus' => 'warning',
                //     'titulo' => 'Error al actualizar'
                // ];
            }
        } catch (\Throwable $th) {
            // $data = [
            //     'estatus' => 'error',
            //     'titulo' => 'Error de servidor',
            //     'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            // ];
        }

        // echo json_encode($data);
    }
    // borrar

    function borrar($param = null)
    {
        if ($this->verificarAdmin()) {
            $this->view->conferencia = $param[0];
            $this->view->render("admin/borrar");
        } else {
            $this->recargar();
        }
    }
    function pregunta_ponente($param = null)
    {
        try {
            $preguntas = AdminModel::pregunta_ponente($param[0]);
            echo json_encode($preguntas);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador preguntaPonente: " . $th->getMessage();
            return;
        }
    }
    function chatGrupal_ponente($param = null)
    {
        try {
            $preguntas = AdminModel::ChatPonente($param[0]);
            echo json_encode($preguntas);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador chatGrupalPonente: " . $th->getMessage();
            return;
        }
    }
    function totalPais_ponente($param = null)
    {
        try {
            $chat = AdminModel::TotalPais($param[0]);
            echo json_encode($chat);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador total pais: " . $th->getMessage();
            return;
        }
    }
    function totalEstado_ponente($param = null)
    {
        try {
            $estado = AdminModel::totalEstado($param[0]);
            echo json_encode($estado);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador total estado: " . $th->getMessage();
        }
    }
    function borrarPregunta()
    {
        try {
            $id_pregunta = $_POST['idPregunta'];
            $nuevoEstatus = base64_decode($_POST['estatus']);
            $nuevoEstatus = ($nuevoEstatus == 0) ? 1 : 0;
            $resp = adminModel::borrarPregunta($id_pregunta, $nuevoEstatus);

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
    // pregunta ponente

    function ponente($param = null)
    {
        if ($this->verificarAdmin()) {
            $this->view->conferencia = $param[0];
            $this->view->render("admin/ponente");
        } else {
            $this->recargar();
        }
    }
    function pregunta_ponenteB($param = null)
    {
        try {
            $preguntas = AdminModel::pregunta_ponenteP($param[0]);
            echo json_encode($preguntas);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia: " . $th->getMessage();
            return;
        }
    }
    function chatGrupal_ponenteB($param = null)
    {
        try {
            $preguntas = AdminModel::ChatPonenteP($param[0]);
            echo json_encode($preguntas);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador conferencia: " . $th->getMessage();
            return;
        }
    }
    function totalPais_ponenteB($param = null)
    {
        try {
            $chat = AdminModel::TotalPaisP($param[0]);
            echo json_encode($chat);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador total pais: " . $th->getMessage();
            return;
        }
    }
    function totalEstado_ponenteB($param = null)
    {
        try {
            $estado = AdminModel::TotalEstadoP($param[0]);
            echo json_encode($estado);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador total estado: " . $th->getMessage();
        }
    }
}
