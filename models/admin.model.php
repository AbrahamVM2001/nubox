<?php

/**
 *
 */
class AdminModel extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }
    // comprobar estatus
    public static function comprobar()
    {
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
    public static function conferencia()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_conferencias");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
    public static function buscarConferencias($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_conferencias WHERE id_sesiones = :id_sesiones;");
            $query->execute([
                ':id_sesiones' => $id_sesiones
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarConferencia: " . $e->getMessage();
            return;
        }
    }
    public static function buscarConferenciasReplica($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_conferencias WHERE id_sesiones = :id_sesiones;");
            $query->execute([
                ':id_sesiones' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarConferencia: " . $e->getMessage();
            return;
        }
    }
    public static function guardarConferencia($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_conferencias (Tema_sesion, Fecha_Hora_Inicio, Fecha_Hora_Termino, Descripcion, Link_transmision_nativo, Link_transmision_traducida, Permisos, Estatus) VALUES (:Tema_sesion, :Fecha_Hora_Inicio, :Fecha_Hora_Termino, :Descripcion, :Link_transmision_nativo, :Link_transmision_traducida, :Permiso,:Estatus)");
            $query->execute([

                ':Tema_sesion' => $datos['tema_sesion'],
                ':Fecha_Hora_Inicio' => $datos['fechaHora_inicio'],
                ':Fecha_Hora_Termino' => $datos['fechaHoraFinalizacion'],
                ':Descripcion' => $datos['descripcion'],
                ':Link_transmision_nativo' => $datos['linkTransmision_nativo'],
                ':Link_transmision_traducida' => $datos['linkTransmision_traducida'],
                ':Permiso' => $datos['permiso'],
                ':Estatus' => $datos['estatus']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarConferencia: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarConferencia($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_conferencias SET 
                Tema_sesion = :Temasesion, 
                Fecha_Hora_Inicio = :datetimeInicio, 
                Fecha_Hora_Termino = :datetimeTermino, 
                Descripcion = :descp, 
                Link_transmision_nativo = :LTN, 
                Link_transmision_traducida = :LTT,
                Permisos = :permiso,
                Estatus = :estatus 
                    WHERE 
                id_sesiones = :idSesiones;");
            $query->execute([

                ':Temasesion' => $datos['tema_sesion'],
                ':datetimeInicio' => $datos['fechaHora_inicio'],
                ':datetimeTermino' => $datos['fechaHoraFinalizacion'],
                ':descp' => $datos['descripcion'],
                ':LTN' => $datos['linkTransmision_nativo'],
                ':LTT' => $datos['linkTransmision_traducida'],
                ':estatus' => $datos['estatus'],
                ':permiso' => $datos['permiso'],
                ':idSesiones' => $datos['id_conferencia']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarConferencia: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarEstatusConferencia($id_sesiones, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_conferencias SET  
            Estatus = :estatus 
                WHERE 
            id_sesiones = :idSesiones;");
            $query->execute([
                ':idSesiones' => base64_decode($id_sesiones),
                ':estatus' => $nuevoEstatus
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Usuario en uso');
            }

            echo "No podemos eliminar usuario: " . $e->getMessage();
            return false;
        }
    }
    // logeo
    public static function logeo()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS Nombre_Completo,
            ad.infoModelo,
            ad.Direccion,
            ad.FechaTiempo,
            ad.id_fk_usuario,
            cu.estatus,
            ad.id_dispositivo
            FROM asignacion_dispositivo AS ad
            INNER JOIN cat_usuario AS cu ON ad.id_fk_usuario = cu.id_usuario
            ORDER BY ad.FechaTiempo DESC;
                ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model logeo: " . $e->getMessage();
            return;
        }
    }
    public static function actualizarEstatusUsuario($idUsuario, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_usuario SET  
            estatus = :estatus 
                WHERE 
            id_usuario = :usuario;");
            $query->execute([
                ':usuario' => base64_decode($idUsuario),
                ':estatus' => $nuevoEstatus
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Usuario en uso');
            }

            echo "No podemos eliminar usuario: " . $e->getMessage();
            return false;
        }
    }
    // estadisticas
    public static function conteoLogeoAsistentes($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT COUNT(DISTINCT ad.id_fk_usuario) AS Total_Usuarios_Logeados
            FROM asignacion_dispositivo AS ad
            JOIN asignacion_sesion AS ases ON ad.id_fk_usuario = ases.id_fk_usuario
            JOIN cat_conferencias AS cc ON ases.id_fk_sesion = cc.id_sesiones WHERE id_fk_sesion = :idSesion;");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo conteoLogeoAsistentes: " . $e->getMessage();
            return [];
        }
    }
    public static function tablaPaisesLogeo($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS Nombre_Completo,
                    cp.pais AS Pais,
                    cc.Fecha_Hora_Inicio AS Fecha_y_Hora
                FROM asignacion_sesion asg
                    JOIN cat_usuario cu ON asg.id_fk_usuario = cu.id_usuario
                    JOIN asignacion_dispositivo ad ON asg.id_fk_dispositivo = ad.id_dispositivo
                    JOIN cat_paises cp ON cu.id_fk_pais = cp.id_pais
                    JOIN cat_conferencias cc ON asg.id_fk_sesion = cc.id_sesiones
                WHERE cc.id_sesiones = :idSesion;
                ");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error en el modelo tablaPaisesLogeo: " . $e->getMessage();
            return [];
        }
    }
    public static function tablaEstadoLogeo($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT ce.Estado AS 'Estado', COUNT(ad.id_fk_usuario) AS 'Conteo'
                FROM 
                    cat_estados ce
                JOIN 
                    cat_usuario cu ON ce.id_estado = cu.id_fk_estado JOIN asignacion_sesion ad ON cu.id_usuario = ad.id_fk_usuario
                WHERE 
                    ad.id_fk_sesion = :idSesion GROUP BY ce.Estado;
                ");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error en el modelo tablaEstadosLogeo: " . $e->getMessage();
            return [];
        }
    }
    public static function tablaVotosLogeo($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT
                CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS 'Nombre_completo',
                COUNT(cv.id_fk_pregunta) AS 'Contestadas',
                SUM(IF(cv.respuesta_pregunta = 1, 1, 0)) AS 'Aciertos'
            FROM
                cat_usuario cu
            LEFT JOIN
                cat_votacion cv ON cu.id_usuario = cv.id_fk_usuario
            WHERE
                cv.id_fk_pregunta IN (
                    SELECT id_pregunta
                    FROM cat_preguntas
                    WHERE id_fk_sesiones = :idSesion
                )
            GROUP BY
                cu.id_usuario;");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error en el modelo tablaVotosLogeo: " . $e->getMessage();
            return [];
        }
    }

    public static function tablaConectividadLogeados($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS Nombre_Completo,
                ad.infoModelo AS Dispositivo,
                cc.Fecha_Hora_Inicio AS Fecha_y_Hora
            FROM asignacion_sesion asg
                JOIN cat_usuario cu ON asg.id_fk_usuario = cu.id_usuario
                JOIN asignacion_dispositivo ad ON asg.id_fk_dispositivo = ad.id_dispositivo
                JOIN cat_conferencias cc ON asg.id_fk_sesion = cc.id_sesiones
            WHERE cc.id_sesiones = :idSesion;
            ");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error en el modelo tablaPaisesLogeo: " . $e->getMessage();
            return [];
        }
    }

    // preguntas
    public static function conferenciaVotacion()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_conferencias");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
    public static function guardarVotacion($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_preguntas (id_fk_sesiones, Pregunta, estatus) VALUES (:idSesion, :pregunta, 0)");
            $query->execute([
                ':pregunta' => $datos['pregunta'],
                ':idSesion' => $datos['sesion']
            ]);
            $last_id = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $last_id;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarVotacion: " . $e->getMessage();
            return false;
        }
    }
    public static function guardarOpcion($idPregunta, $opcion, $sesion)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_opciones (id_fk_pregunta, opcion, correcta, id_fk_sesion) VALUES (:idPregunta, :opcion, :respuesta, :idSesion)");
            $query->execute([
                ':idPregunta' => $idPregunta,
                ':opcion' => $opcion['opcion'],
                ':respuesta' => $opcion['respuesta'],
                ':idSesion' => $sesion
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error en el modelo guardarOpcion: " . $e->getMessage();
            return false;
        }
    }
    public static function buscarVotacion($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT cc.id_sesiones AS 'id_sesiones', cc.Tema_sesion AS 'Tema_sesion', cp.id_pregunta AS 'id_pregunta', cp.Pregunta AS 'Pregunta', ao.id_opcion AS 'id_opcion', ao.opcion AS 'Opciones', ao.correcta AS 'Correcta', cp.estatus AS 'estatus'
        FROM 
            cat_preguntas cp
        JOIN 
            asignacion_opciones ao ON cp.id_pregunta = ao.id_fk_pregunta
        JOIN 
            cat_conferencias cc ON cp.id_fk_sesiones = cc.id_sesiones WHERE ao.id_fk_sesion = :id_sesiones;");
            $query->execute([
                ':id_sesiones' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll(); // Modificado para recuperar todas las filas
        } catch (PDOException $e) {
            echo "Error recopilado model buscarConferencia: " . $e->getMessage();
            return;
        }
    }
    public static function actualizarVotacion($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE asignacion_opciones SET 
            opcion = :opcion,
            correcta = :correcta
            WHERE 
            id_opcion = :idOpcion;");
            $query->execute([
                ':opcion' => $datos['opcion'],
                ':correcta' => $datos['correcta'],
                ':idOpcion' => $datos['idOpcion']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error en el modelo al actualizar la opción: " . $e->getMessage();
            return false;
        }
    }
    public static function guardarNewOpcion($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_opciones (id_fk_pregunta, opcion, correcta, id_fk_sesion) VALUES (:idPregunta, :opcion, :correcta, :idSesion)");
            $query->execute([
                ':idPregunta' => $datos['idPregunta'],
                ':opcion' => $datos['opcion'],
                ':correcta' => $datos['correcta'],
                ':idSesion' => $datos['idSesion']
            ]);
            $last_id = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $last_id;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarNewOpcion: " . $e->getMessage();
            return false;
        }
    }
    public static function activaDesactiva($idPregunta, $newEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_preguntas SET  
        estatus = :estatus
            WHERE 
        id_pregunta = :idPregunta;");
            $query->execute([
                ':idPregunta' => base64_decode($idPregunta),
                ':estatus' => $newEstatus
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "No podemos desactivar voto: " . $e->getMessage();
            return false;
        }
    }
    public static function borrarVoto($idPregunta)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("DELETE FROM cat_preguntas WHERE id_pregunta = :idPregunta");
            $query->execute([
                ':idPregunta' => $idPregunta
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Pregunta con uso');
            }
            echo "Error recopilado model eliminar voto: " . $e->getMessage();
            return false;
        }
    }
    public static function borrarOpicion($idOpcion)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("DELETE FROM asignacion_opciones WHERE id_opcion = :idOpcion");
            $query->execute([
                ':idOpcion' => $idOpcion
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Voto en uso');
            }
            echo "Error recopilado model eliminar opcion: " . $e->getMessage();
            return false;
        }
    }
    public static function mostrarVotacion($id_pregunta)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT
                cp.id_pregunta AS 'ID Pregunta',
                cp.Pregunta,
                ao.id_opcion AS 'ID Opcion',
                ao.opcion AS Opcion,
                IFNULL(ROUND(COUNT(cv.id_votacion) / NULLIF(total_votos.total, 0) * 100, 2), 0) AS Porcentaje
            FROM
                cat_preguntas cp
            JOIN
                asignacion_opciones ao ON cp.id_pregunta = ao.id_fk_pregunta
            LEFT JOIN
                cat_votacion cv ON ao.id_opcion = cv.id_fk_Opcion AND cp.id_pregunta = cv.id_fk_pregunta
            LEFT JOIN
                (SELECT id_fk_pregunta, COUNT(*) AS total FROM cat_votacion GROUP BY id_fk_pregunta) total_votos ON cp.id_pregunta = total_votos.id_fk_pregunta
            WHERE
                cp.id_pregunta = :id_pregunta
            GROUP BY
                cp.id_pregunta, ao.id_opcion
            ORDER BY
                cp.id_pregunta, Porcentaje DESC;
            ");
            $query->execute([
                ':id_pregunta' => base64_decode($id_pregunta)
            ]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error recopilado model comentario: " . $e->getMessage();
            return;
        }
    }
    public static function activaDesactivaPorSegundo($idPregunta)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_preguntas SET  
                    estatus = 0
                WHERE 
                    id_pregunta = :idPregunta;");
            $query->execute([
                ':idPregunta' => base64_decode(base64_decode($idPregunta))
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "No podemos desactivar voto: " . $e->getMessage();
            return false;
        }
    }
    // borrar
    public static function pregunta_ponente($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT 
                    c.Tema_sesion AS 'Tema sesion',
                    CONCAT(u.Nombre, ' ', u.Apellido_paterno, ' ', u.Apellido_materno) AS 'Nombre completo',
                    p.pregunta AS 'Pregunta',
                    p.FechaTiempo AS 'Fecha y Hora',
                    p.id_pregunta_ponente,
                    p.estatus
                FROM 
                    asignacion_pregunta_ponente p
                JOIN 
                    cat_usuario u ON p.id_fk_usuario = u.id_usuario
                JOIN 
                    cat_conferencias c ON p.id_fk_sesion = c.id_sesiones
                WHERE 
                    p.id_fk_sesion = :idSesion and p.estatus = 1;
                ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model preguntas ponente: " . $e->getMessage();
            return;
        }
    }
    public static function ChatPonente($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS `Nombre completo`,
                ac.Mensaje AS Mensaje,
                ac.Fecha_publicacion AS `Fecha publicacion`
            FROM 
                asignacion_chat ac
            JOIN 
                cat_usuario cu ON ac.id_fk_usuario = cu.id_usuario WHERE id_fk_sesion = :idSesion ORDER BY ac.Fecha_publicacion DESC
                ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model chat ponente: " . $e->getMessage();
            return;
        }
    }
    public static function TotalPais($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT cp.pais AS Pais, COUNT(cu.id_usuario) AS `Total de usuarios logeados`
                FROM cat_usuario cu
                    JOIN cat_paises cp ON cu.id_fk_pais = cp.id_pais
                    JOIN asignacion_sesion ases ON cu.id_usuario = ases.id_fk_usuario AND ases.id_fk_sesion = :idSesion
                    GROUP BY cp.pais;        
            ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "error recopilado model total pais: " . $e->getMessage();
        }
    }
    public static function TotalEstado($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT 
                ce.estado AS Estado,
                COUNT(cu.id_usuario) AS `Total de usuarios logeados` FROM cat_usuario cu JOIN cat_estados ce ON cu.id_fk_estado = ce.id_estado
                    JOIN asignacion_sesion ases ON cu.id_usuario = ases.id_fk_usuario AND ases.id_fk_sesion = :idSesion
                    GROUP BY ce.estado; ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "error recopilado model total estado: " . $e->getMessage();
        }
    }
    public static function borrarPregunta($id_pregunta, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE asignacion_pregunta_ponente SET  
            estatus = :estatus
                WHERE 
            id_pregunta_ponente = :idPregunta;");
            $query->execute([
                ':idPregunta' => base64_decode($id_pregunta),
                ':estatus' => $nuevoEstatus
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Usuario en uso');
            }

            echo "No podemos eliminar usuario: " . $e->getMessage();
            return false;
        }
    }
    // ponente
    public static function pregunta_ponenteP($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT 
                    c.Tema_sesion AS 'Tema sesion',
                    CONCAT(u.Nombre, ' ', u.Apellido_paterno, ' ', u.Apellido_materno) AS 'Nombre completo',
                    p.pregunta AS 'Pregunta',
                    p.FechaTiempo AS 'Fecha y Hora',
                    p.id_pregunta_ponente,
                    p.estatus
                FROM 
                    asignacion_pregunta_ponente p
                JOIN 
                    cat_usuario u ON p.id_fk_usuario = u.id_usuario
                JOIN 
                    cat_conferencias c ON p.id_fk_sesion = c.id_sesiones
                WHERE 
                    p.id_fk_sesion = :idSesion and p.estatus = 1;
                ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model preguntas ponente: " . $e->getMessage();
            return;
        }
    }
    public static function ChatPonenteP($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT CONCAT(cu.Nombre, ' ', cu.Apellido_paterno, ' ', cu.Apellido_materno) AS `Nombre completo`,
                ac.Mensaje AS Mensaje,
                ac.Fecha_publicacion AS `Fecha publicacion`
            FROM 
                asignacion_chat ac
            JOIN 
                cat_usuario cu ON ac.id_fk_usuario = cu.id_usuario WHERE id_fk_sesion = :idSesion ORDER BY ac.Fecha_publicacion DESC
                ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model chat ponente: " . $e->getMessage();
            return;
        }
    }
    public static function TotalPaisP($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT cp.pais AS Pais, COUNT(cu.id_usuario) AS `Total de usuarios logeados`
                FROM cat_usuario cu
                    JOIN cat_paises cp ON cu.id_fk_pais = cp.id_pais
                    JOIN asignacion_sesion ases ON cu.id_usuario = ases.id_fk_usuario AND ases.id_fk_sesion = :idSesion
                    GROUP BY cp.pais;        
            ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "error recopilado model total pais: " . $e->getMessage();
        }
    }
    public static function TotalEstadoP($id_sesion)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT 
                ce.estado AS Estado,
                COUNT(cu.id_usuario) AS `Total de usuarios logeados` FROM cat_usuario cu JOIN cat_estados ce ON cu.id_fk_estado = ce.id_estado
                    JOIN asignacion_sesion ases ON cu.id_usuario = ases.id_fk_usuario AND ases.id_fk_sesion = :idSesion
                    GROUP BY ce.estado; ");
            $query->execute([
                'idSesion' => base64_decode(base64_decode($id_sesion))
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "error recopilado model total estado: " . $e->getMessage();
        }
    }
    // votaciones
    public static function conteoVotos($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT COUNT(DISTINCT ad.id_fk_usuario) AS Total_Usuarios_Logeados
            FROM asignacion_dispositivo AS ad
            JOIN asignacion_sesion AS ases ON ad.id_fk_usuario = ases.id_fk_usuario
            JOIN cat_conferencias AS cc ON ases.id_fk_sesion = cc.id_sesiones WHERE id_fk_sesion = :idSesion;");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo buscarPreguntaYopciones: " . $e->getMessage();
            return [];
        }
    }
    public static function viewConteoConferencia()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT
                cc.id_sesiones AS `idsesiones`,
                cc.Tema_sesion AS `Tema sesión`,
                cc.Fecha_Hora_Inicio AS `Fecha Hora Inicio`,
                cc.Fecha_Hora_Termino AS `Fecha Hora Termino`,
                cc.Descripcion AS Descripcion,
                COUNT(cv.id_votacion) AS `Total`
            FROM
                cat_conferencias cc
            JOIN
                cat_preguntas cp ON cc.id_sesiones = cp.id_fk_sesiones
            LEFT JOIN
                cat_votacion cv ON cp.id_pregunta = cv.id_fk_pregunta
            GROUP BY
                cc.id_sesiones
            ORDER BY `idsesiones` ASC;
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
    public static function conteoVotosSesion($id_sesiones)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT cu.Nombre AS Usuario, cu.Correo,
                COUNT(CASE WHEN cv.respuesta_pregunta = 1 THEN 1 END) AS Correctas,
                COUNT(cv.respuesta_pregunta) AS Contestadas
                FROM 
                cat_usuario cu
                LEFT JOIN cat_votacion cv ON cu.id_usuario = cv.id_fk_usuario
                LEFT JOIN cat_preguntas cp ON cv.id_fk_pregunta = cp.id_pregunta
                LEFT JOIN cat_conferencias cc ON cp.id_fk_sesiones = cc.id_sesiones
                WHERE cp.id_fk_sesiones = :idSesion
                GROUP BY 
                cu.id_usuario, cu.Nombre, cu.Correo");
            $query->execute([
                ':idSesion' => base64_decode(base64_decode($id_sesiones))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
}
