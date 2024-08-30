<?php

use function GuzzleHttp\Promise\queue;

/**
 *
 */
class AdminModel extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }
    // usuario
    public static function usuario()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                id_usuario,
                CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo,
                tipo_usuario,
                correo,
                contrasena,
                estatus
            FROM
                cat_usuario;
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model usuario: " . $e->getMessage();
            return;
        }
    }
    public static function guardarUsuario($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_usuario
                (nombre, apellido_paterno, apellido_materno, tipo_usuario, correo, contrasena, estatus)
                    VALUES
                (:name, :paterno, :materno, :tipo_usuario, :email, :pass, 1)");
            $query->execute([
                ':name' => $datos['nombre'],
                ':paterno' => $datos['apaterno'],
                ':materno' => $datos['amaterno'],
                ':tipo_usuario' => $datos['tipo_usuario'],
                ':email' => $datos['correo'],
                ':pass' => $datos['pass']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarUsuario: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarUsuario($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_usuario SET
                nombre = :name,
                apellido_paterno = :paterno,
                apellido_materno = :materno,
                tipo_usuario = :tipo_usuario,
                correo = :email,
                contrasena = :password
                    WHERE 
                id_usuario = :idUsuario;");
            $query->execute([
                ':idUsuario' => $datos['id_usuario'],
                ':name' => $datos['nombre'],
                ':paterno' => $datos['apaterno'],
                ':materno' => $datos['amaterno'],
                ':tipo_usuario' => $datos['tipo_usuario'],
                ':email' => $datos['correo'],
                ':password' => $datos['pass']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarUsuario: " . $e->getMessage();
            return false;
        }
    }
    public static function buscarUsuario($id_usuario)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuario WHERE id_usuario = :id_usuario;");
            $query->execute([
                ':id_usuario' => $id_usuario
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarUsuario: " . $e->getMessage();
            return;
        }
    }
    public static function actualizarEstatusUsuario($id_usuario, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_usuario SET  
            estatus = :txtEstatus 
                WHERE 
            id_usuario = :idUsuario;");
            $query->execute([
                ':idUsuario' => base64_decode($id_usuario),
                ':txtEstatus' => base64_decode($nuevoEstatus)
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
    // salones
    public static function salon()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ces.*,
                cp.pais,
                ce.estado
            FROM
                cat_espacios ces
            JOIN
                cat_paises cp ON ces.fk_pais = cp.id_pais
            JOIN
                cat_estados ce ON ces.fk_estado = ce.id_estado
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model usuario: " . $e->getMessage();
            return;
        }
    }
    public static function guardarSalon($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_espacios
                (nombre, tipo_espacio, fk_pais, fk_estado, cordenadas, precio_hora, estatus)
                    VALUES
                (:nombre, :tipoEspacio, :fkPais, :fkEstado, :cordenadas, :PrecioHora, 1)");
            $query->execute([
                ':nombre' => $datos['nombre'],
                ':tipoEspacio' => $datos['tipo_espacio'],
                ':fkPais' => $datos['id_pais'],
                ':fkEstado' => $datos['id_estado'],
                ':cordenadas' => $datos['cordenadas'],
                ':PrecioHora' => $datos['precio']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarSalon: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarSalon($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_salon SET
                nombre = :nombre,
                tipo_espacio = :tipoEspacio,
                fk_pais = :idPais,
                fk_estado = :idEstado,
                cordenadas = :cordenadas
                    WHERE 
                id_salon = :idSalon;");
            $query->execute([
                ':nombre' => $datos['nombre'],
                ':tipoEspacio' => $datos['tipo_espacio'],
                ':idPais' => $datos['id_pais'],
                ':idEstado' => $datos['id_estado'],
                ':cordenadas' => $datos['cordenadas']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarSalon: " . $e->getMessage();
            return false;
        }
    }
    public static function pais()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_paises WHERE id_pais;");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model usuario: " . $e->getMessage();
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
    public static function buscarEspacio($id_espacio)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE id_espacio = :id_espacio;");
            $query->execute([
                ':id_espacio' => $id_espacio
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarEspacio: " . $e->getMessage();
            return;
        }
    }
}
