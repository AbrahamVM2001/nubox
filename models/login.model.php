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
            $query = $con->pdo->prepare("SELECT * FROM cat_empleado WHERE correo = :mail");
            $query->execute([
                ':mail' => $datos['mail']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model user: " . $e->getMessage();
            return;
        }
    }
    public static function RegistroDispositivo($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("INSERT INTO asignacion_dispositivo (infoModelo, Direccion, FechaTiempo, id_fk_usuario) VALUES (:info, :Dir, :FechaYTiempo, :id_usuario)");

            $fecha_actual = date("Y-m-d H:i:s");
            $query->execute([
                ':info' => $datos['modelo'],
                ':Dir' => $datos['ip'],
                ':FechaYTiempo' => $fecha_actual,
                ':id_usuario' => $_SESSION['id_usuario-' . constant('Sistema')]
            ]);
            $last_id = $con->pdo->lastInsertId();

            return ['estatus' => 'success', 'mensaje' => 'Dispositivo insertado correctamente', 'id_dispositivo' => $last_id];
        } catch (PDOException $e) {
            echo "Error recopilacion model user: " . $e->getMessage();
            return ['estatus' => 'error', 'mensaje' => 'Error al insertar el comentario en la base de datos'];
        }
    }

    public static function Accesouser($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuario WHERE Correo = :email");
            $query->execute([
                ':email' => $datos['mailUser']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model user: " . $e->getMessage();
            return;
        }
    }
    public static function VerificarPermiso(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT Permisos FROM cat_conferencias;");
            $query->execute();
            return $query->fetch();
        } catch (\Throwable $e) {
            echo "Error recopliado model permiso: " . $e->getMessage();
        }
    }
    public static function registro($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("INSERT INTO cat_usuario(id_fk_prefijo, Nombre, Apellido_paterno, Apellido_materno, tipo_usuario, id_fk_pais, Username, id_fk_estado, Correo, Password, estatus)
                VALUES (:fkPrefijo, :nombre, :apellP, :apellM, '2', :fkPais, :username, :fkEstado, :email, :pass, '1')");
            $query->execute([
                ':fkPrefijo' => $datos['prefijos'],
                ':nombre' => $datos['nombre'],
                ':apellP' => $datos['AP'],
                ':apellM' => $datos['AM'],
                ':fkPais' => $datos['pais'],
                ':username' => $datos['username'],
                ':fkEstado' => $datos['estado'],
                ':email' => $datos['correo'],
                ':pass' => $datos['pass']
            ]);
            $idUsuario = $con->pdo->lastInsertId();
            return ['estatus' => 'success', 'mensaje' => 'Usuario insertado correctamente', 'id_usuario' => $idUsuario];
        } catch (\Throwable $e) {
            echo "Error recopilado model user: " . $e->getMessage();
            return ['estatus' => 'error', 'mensaje' => 'Error al insertar el comentario en la base de datos'];
        }
    }
    public static function asignacion_sesion($datos)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("INSERT INTO asignacion_sesion (id_fk_usuario, id_fk_sesion, id_fk_dispositivo) VALUES 
                (:fkUsuario, :fkSesion, :fkDispositivo)");
            $query->execute([
                ':fkUsuario' => $datos['id_usuario'],
                ':fkSesion' => $datos['id_sesion'],
                ':fkDispositivo' => $datos['id_dispositivo']
            ]);
            return ['estatus' => 'success', 'mensaje' => 'Asignacion correcta'];
        } catch (\Throwable $e) {
            echo "Error recopilado model asignacion: " . $e->getMessage();
            return ['estatus' => 'error', 'mensaje' => 'Error al asiganar conferencia'];
        }
    }
    public static function paises()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_paises");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model paises " . $e->getMessage();
            return;
        }
    }
    public static function prefijos()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_prefijos");
            $query->execute();
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error recopilado model prefijos " . $e->getMessage();
            return;
        }
    }
    public static function estado($id_pais)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_estados WHERE id_fk_pais = :id_pais");
            $query->bindParam(':id_pais', $id_pais);
            $query->execute();
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error recopilado model estados: " . $e->getMessage();
            return;
        }
    }

    public static function conferencias()
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
                END ASC;
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error recopilado model conferencias " . $e->getMessage();
            return;
        }
    }
}
