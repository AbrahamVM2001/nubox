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
            (:name, :paterno, :materno, 3, :email, :pass, 1)");
            $query->execute([
                ':name' => $datos['name'],
                ':paterno' => $datos['apellidoP'],
                ':materno' => $datos['apellidoM'],
                ':email' => $datos['emailRegistro'],
                ':pass' => $datos['passRegistro']
            ]);
            $idUsuario = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return ['estatus' => 'success', 'mensaje' => 'Usuario insertado correctamente', 'id_usuario' => $idUsuario];
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
    public static function imagen($id_espacio)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ac.ubicacion,
                ce.nombre
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
            (fk_reservacion, fk_usuario, fecha_pago, monto, estado)
            VALUES
            (:reserva, :usuario, :fecha_pago, :monto, :estado)");
            $query->execute([
                ':reserva' => $datos['id_asignacion_reservacion'],
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':monto' => $datos['total'],
                ':fecha_pago' => $fecha,
                ':estado' => 'Exitoso'
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
        (fk_usuario, numero_tarjeta, fecha_vencimiento, nombre_titular)
        VALUES
        (:usuario, :numero, :fecha, :nombre)");
            $query->execute([
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':numero' => $datos['cardnumber'],
                ':fecha' => $datos['exp-date'],
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
    public static function extraerCorreo()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT correo FROM cat_usuario WHERE id_usuario = :Usuario;");
            $query->execute([
                ':Usuario' => $_SESSION['id_usuario-' . constant('Sistema')]
            ]);
            return $query->fetch();
        } catch (\Throwable $th) {
            echo "Error en el extraerCorreo controller: " . $th->getMessage();
            return false;
        }
    }
    public static function buscarUsuarioDisponible()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT DISTINCT cu.id_usuario, cu.nombre, cu.apellido_paterno, cu.apellido_materno, cu.correo
            FROM cat_usuario cu
            LEFT JOIN asignacion_usuario_reservacion aur 
                ON cu.id_usuario = aur.fk_usuario AND aur.estatus = 1
            WHERE cu.tipo_usuario = 2
                AND aur.fk_usuario IS NULL
            LIMIT 1;
            ");
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado en buscarUsuarioDisponible: " . $e->getMessage();
            return false;
        }
    }
    public static function asignarUsuarioReservacion($idUsuario, $idAsignacionReserva)
    {
        try {
            $fecha = date("Y-m-d h:m:s");
            $con = new Database;
            $query = $con->pdo->prepare("INSERT INTO asignacion_usuario_reservacion (fk_usuario, fk_asignacion_reserva, fecha_inicio, estatus) VALUES (:usuario, :reserva, :fecha, 1)");
            $query->execute([
                ':usuario' => $idUsuario,
                ':reserva' => $idAsignacionReserva,
                ':fecha' => $fecha
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error recopilado en asignarUsuarioReservacion: " . $e->getMessage();
            return false;
        }
    }
    // restaurar contraseña
    public static function correo($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuario WHERE correo = :usuario");
            $query->execute([
                ':usuario' => $datos['correoConfir']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model correo: " . $e->getMessage();
            return;
        }
    }
    public static function buscarNumero($numero)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT numero FROM asignacion_numero_verificacion WHERE numero = :numero");
            $query->execute([
                ':numero' => $numero
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo eventos: " . $e->getMessage();
            return [];
        }
    }
    public static function registroNumero($datos){
        try {
            $fecha = date('Y-m-d H:i:s');
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_numero_verificacion
            (fk_usuario, numero, fecha, confirmacion)
                VALUES
            (:usuario, :numero, :fecha, 0)");
            $query->execute([
                ':usuario' => $datos['id_usuario'],
                ':numero' => $datos['numero_verificar'],
                ':fecha' => $fecha
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarUsuario: " . $e->getMessage();
            return false;
        }
    }
    public static function verificadorNumero($datos){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM asignacion_numero_verificacion WHERE numero = :numero");
            $query->execute([
                'numero' => $datos['numero']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error en el model verificadorNumero: " . $e->getMessage();
            return;
        }
    }
    public static function registroEstatusVerificar($datos){
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE asignacion_numero_verificacion SET
                confirmacion = 1
                    WHERE 
                id_asignacion_numero_verificacion = :numero;");
            $query->execute([
                ':numero' => $datos['id_asignacion_numero_verificacion']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error en el model registroEstatusVerificar: " . $e->getMessage();
            return false;
        }
    }
    public static function restaurarContrasena($datos){
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_usuario SET
                contrasena = :contrasena
                    WHERE
                id_usuario = :usuario;");
            $query->execute([
                ':usuario' => base64_decode(base64_decode($datos['id_usuario'])),
                ':contrasena' => $datos['contrasena']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error en el model restaurarContrasena: " . $e->getMessage();
            return false;
        }
    }
}
