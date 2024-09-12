<?php

/**
 *
 */
class LoginModel extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }
    public static function user($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuario WHERE correo = :usuario");
            $query->execute([
                ':usuario' => $datos['correo']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model user: " . $e->getMessage();
            return;
        }
    }
    public static function registro($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_usuario
                (nombre, apellido_paterno, apellido_materno, tipo_usuario, correo, contrasena, estatus)
                    VALUES
                (:name, :paterno, :materno, 4, :email, :pass, 1)");
            $query->execute([
                ':name' => $datos['name'],
                ':paterno' => $datos['apellidoP'],
                ':materno' => $datos['apellidoM'],
                ':email' => $datos['emailRegistro'],
                ':pass' => $datos['passRegistro']
            ]);
            $idUsuario = $con->pdo->lastInsertId();
            return ['estatus' => 'success', 'mensaje' => 'Usuario insertado correctamente', 'id_usuario' => $idUsuario];
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarUsuario: " . $e->getMessage();
            return false;
        }
    }
    // funciones de carruseles
    public static function viewSalon()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = 1 AND estatus = 1;");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model salon: " . $e->getMessage();
            return;
        }
    }
    public static function viewOficina()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = 2 AND estatus = 1");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model oficina: " . $e->getMessage();
            return;
        }
    }
    // mostrar el espacio seleccionado
    public static function espacio($id_espacio)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ac.*,
                ce.*
            FROM
                asignacion_contenido ac
            LEFT JOIN
                cat_espacios ce ON ac.fk_espacio = ce.id_espacio
            WHERE
                ac.fk_espacio = :idEspacio;
            ");
            $query->execute([
                ':idEspacio' => base64_decode(base64_decode($id_espacio))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model espacio: " . $e->getMessage();
            return;
        }
    }
    public static function asignacion($id_espacio)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ar.*
            FROM
                asignacion_reservacion ar
            JOIN
                cat_espacios ce ON ar.fk_espacio = ce.id_espacio
            WHERE
                ar.fk_espacio = :idEspacio;
            ");
            $query->execute([
                ':idEspacio' => base64_decode(base64_decode($id_espacio))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model espacio: " . $e->getMessage();
            return;
        }
    }
    // pantalla para mostrar todos los salones
    public static function mostrarSalones()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = '1';");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model salones: " . $e->getMessage();
            return;
        }
    }
    public static function mostrarOficinas()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = '2';");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model oficinas: " . $e->getMessage();
            return;
        }
    }
    // registro de pago
    public static function registroReserva($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_reservacion
            (fk_usuario, fk_espacio, fecha_incio, fecha_finalizacion)
                VALUES
            (:usuario, :espacio, :fechaI, :fechaF)");
            $query->execute([
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':espacio' => base64_decode(base64_decode($datos['id_espacio'])),
                ':fechaI' => $datos['fecha_ingreso'],
                ':fechaF' => $datos['fecha_finalizacion']
            ]);
            $idReserva = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $idReserva;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model registroReserva: " . $e->getMessage();
            return false;
        }
    }
    public static function registroPago($datos)
    {
        try {
            $con = new Database;
            $fecha = date('Y-m-d h:m:s');
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_pago 
            (fk_reservacion, fk_usuario, monto, fecha_pago, metodo_pago, estado)
            VALUES
            (:reserva, :usuario, :monto, :fecha_pago, :pago, :estado)");
            $query->execute([
                ':reserva' => $datos['id_asignacion_reservacion'],
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':monto' => $datos['total'],
                ':fecha_pago' => $fecha,
                ':pago' => $datos['metodo_pago'],
                ':estado' => 'comprobacion'
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model registroPago: " . $e->getMessage();
            return false;
        }
    }
    public static function registroTarjeta($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_tarjeta
            (fk_usuario, numero_tarjeta, fecha_vencimiento, cvc, nombre_titular)
            VALUES
            (:usuario, :numero, :fecha, :cvc, :nombre)");
            $query->execute([
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':numero' => $datos['cardnumber'],
                ':fecha' => $datos['exp-date'],
                ':cvc' => $datos['cardCvc'],
                ':nombre' => $datos['name']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model registroPago: " . $e->getMessage();
            return false;
        }
    }
}
