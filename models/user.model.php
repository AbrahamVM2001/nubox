<?php

/**
 *
 */
class UserModel extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }
    // comprobar estatus
    public static function comprobar() {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT estatus FROM cat_usuario;");
            $query->execute();
            $result = $query->fetch();
            return $result['estatus'];
        } catch (\PDOException $e) {
            echo "Error recopilado model comprobar: " . $e->getMessage();
            return;
        }
    }
    /* conferencia */
    public static function infoUltimoConferencia()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT *
            FROM cat_conferencias
            WHERE Fecha_Hora_Inicio > NOW() OR (Fecha_Hora_Termino > NOW() AND Fecha_Hora_Inicio < NOW())
            ORDER BY 
                CASE 
                    WHEN Fecha_Hora_Termino < NOW() THEN Fecha_Hora_Inicio - NOW() 
                    ELSE Fecha_Hora_Inicio 
                END ASC LIMIT 1;
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
    public static function ComentariosView($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS Nombre_Completo,
                ac.Mensaje,
                ac.Fecha_publicacion
            FROM asignacion_chat ac
                INNER JOIN cat_usuario cu ON ac.id_fk_usuario = cu.id_usuario
            WHERE ac.id_fk_sesion = :id ORDER BY ac.Fecha_publicacion DESC;
            ");
            $query->execute([
                ':id' => $id_sesiones
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "Error recopilado model comentario: " . $e->getMessage();
            return;
        }
    }
    public static function registroComentario($datos)
    {
        try {
            $con = new Database;
            $fecha_actual = date("Y-m-d H:i:s");
            $query = $con->pdo->prepare("INSERT INTO asignacion_chat (id_fk_usuario, id_fk_sesion, Mensaje, Fecha_publicacion, Estatus)
                VALUES (:fkUsuario, :fkSesion, :mensaje, :fecha, '1');");
            $query->execute([
                ':fkUsuario' => $datos['idUsuario'],
                ':fkSesion' => $datos['idSesion'],
                ':mensaje' => $datos['comentario'],
                ':fecha' => $fecha_actual
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error recopilado model registro: " . $e->getMessage());
            return false;
        }
    }
    public static function registroPregunta($datos)
    {
        try {
            $con = new Database;
            $fecha_actual = date("Y-m-d H:i:s");
            $query = $con->pdo->prepare("INSERT INTO asignacion_pregunta_ponente (id_fk_usuario, id_fk_sesion, pregunta, FechaTiempo, estatus)
                VALUES 
                    (:fk_usuario, :fk_sesion, :Pregunta, :fecha, 1)");
            $query->execute([
                ':fk_usuario' => $datos['idUsuarioPregunta'],
                ':fk_sesion' => $datos['idSesionPregunta'],
                ':Pregunta' => $datos['pregunta'],
                ':fecha' => $fecha_actual
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("Error recopilado model registro: " . $e->getMessage());
            return false;
        }
    }
    public static function mostrarVotacion($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT cat_preguntas.Pregunta, cat_preguntas.id_pregunta, asignacion_opciones.opcion, asignacion_opciones.id_opcion, asignacion_opciones.correcta
                                FROM cat_preguntas
                                INNER JOIN asignacion_opciones ON cat_preguntas.id_pregunta = asignacion_opciones.id_fk_pregunta
                                WHERE cat_preguntas.id_fk_sesiones = :id_sesiones AND cat_preguntas.Estatus = 1");
            $query->execute([':id_sesiones' => $id_sesiones]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error recopilado model comentario: " . $e->getMessage();
            return;
        }
    }
    public static function verificarRespuesta($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT correcta FROM asignacion_opciones WHERE id_opcion = :idOpcion");
            $query->execute([
                'idOpcion' => $datos['idOpcion']
            ]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            return $resultado['correcta'];
        } catch (\PDOException $e) {
            error_log("Error recopilado model registro: " . $e->getMessage());
            return null;
        }
    }
    public static function verificarUsurario($datos){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_votacion WHERE id_fk_pregunta = :fkPregunta");
            $query->execute([
                ':fkPregunta' => $datos['idPregunta']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model verficarUsuario: " . $e->getMessage();
            return;
        }
    }

    public static function respuesta($datos, $idOpciones)
    {
        try {
            $con = new Database;
            $fecha_actual = date("Y-m-d H:i:s");
            $usuario = isset($_SESSION['id_usuario-' . constant('Sistema')]) ? $_SESSION['id_usuario-' . constant('Sistema')] : '';
            $query = $con->pdo->prepare("INSERT INTO cat_votacion (id_fk_usuario, id_fk_pregunta, respuesta_pregunta, fechaHora, id_fk_Opcion)
                VALUES 
                    (:fk_usuario, :fk_pregunta, :respuesta, :fecha, :fkOpcion)");
            $query->execute([
                'fk_usuario' => $usuario,
                'fk_pregunta' => $datos['idPregunta'],
                ':respuesta' => $datos['opcion'],
                ':fecha' => $fecha_actual,
                ':fkOpcion' => $idOpciones
            ]); 
            return true;
        } catch (\PDOException $e) {
            error_log("Error recopilado model registro: " . $e->getMessage());
            return false;
        }
    }
}
