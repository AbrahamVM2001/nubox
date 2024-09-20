<?php

/**
 *
 */
class usuarioModel extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }
    // mostrar el cliente asignado
    public static function asignacion(){
        try {
            $con = new database;
            $query = $con->pdo->prepare("
            SELECT
                CONCAT(cu.nombre, ' ', cu.apellido_paterno, ' ', cu.apellido_materno) AS Nombre_del_cliente,
                ce.nombre AS Espacio,
                aur.fecha_inicio AS Fecha_pago,
                ce.precio_hora AS Monto,
                ct.numero_tarjeta AS Numero_tarjeta,
                ar.fecha_incio AS Fecha_inicio,
                ar.fecha_finalizacion AS Fecha_finalizacion,
                aur.estatus,
                aur.id_asignacion_usuario_reservacion,
                ct.fk_usuario
            FROM
                asignacion_usuario_reservacion aur
            LEFT JOIN
                asignacion_reservacion ar ON aur.fk_asignacion_reserva = ar.id_asignacion_reservacion
            LEFT JOIN
                cat_usuario cu ON aur.fk_usuario = cu.id_usuario
            LEFT JOIN
                cat_tarjeta ct ON ar.fk_usuario = ct.fk_usuario
            LEFT JOIN
                cat_espacios ce ON ar.fk_espacio = ce.id_espacio
            WHERE
                cu.id_usuario = :usuario
                AND aur.estatus = 1;
            ");
            $query->execute([
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')]
            ]);
            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "Error en el model de asignacion: " . $e->getMessage();
            return;
        }
    }
    // terminado
    public static function terminado($id_asignacion_usuario_reservacion, $nuevoEstatus)
    {
        try {
            $fecha = date("Y-m-d h:m:s");
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE asignacion_usuario_reservacion SET  
            fecha_finalizacion = :fecha,
            estatus = :txtEstatus
                WHERE 
            id_asignacion_usuario_reservacion = :idAsignacion;");
            $query->execute([
                ':idAsignacion' => base64_decode($id_asignacion_usuario_reservacion),
                ':fecha' => $fecha,
                ':txtEstatus' => base64_decode($nuevoEstatus)
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error al momento de habilitar la asignacion: " . $e->getMessage();
            return false;
        }
    }
    public static function buscarUsuario($id_usuario){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuario WHERE id_usuario = :id_usuario;");
            $query->execute([
                ':id_usuario' => base64_decode(base64_decode($id_usuario))
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarUsuario: " . $e->getMessage();
            return;
        }
    }
}
