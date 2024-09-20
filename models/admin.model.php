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
    // mostrar graficas para analisis
    public static function containerVentasTotales(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT SUM(monto) AS total_monto FROM asignacion_pago;");
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error model containerVentasTotales: " . $e->getMessage();
            return;
        }
    }
    public static function containerClientes(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT COUNT(tipo_usuario) AS numero_total FROM cat_usuario WHERE tipo_usuario = 3;");
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error model containerClientes: " . $e->getMessage();
            return;
        }
    }
    public static function containerEspacios(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT 
                e.id_espacio, 
                e.nombre, 
                COUNT(r.id_asignacion_reservacion) AS total_reservaciones
            FROM 
                asignacion_reservacion r
            INNER JOIN 
                cat_espacios e ON r.fk_espacio = e.id_espacio
            GROUP BY 
                e.id_espacio, e.nombre;
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model containerEspacios: " . $e->getMessage();
            return;
        }
    }
    public static function containerClientesSinAsignar(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT 
                e.id_espacio,
                e.nombre AS nombre_espacio,
                u.id_usuario,
                u.nombre AS nombre_usuario,
                u.apellido_paterno,
                u.apellido_materno
            FROM 
                cat_espacios e
            LEFT JOIN 
                cat_usuario u ON u.tipo_usuario = 2 
            LEFT JOIN 
                asignacion_usuario_reservacion aur ON u.id_usuario = aur.fk_usuario
            WHERE 
                aur.fk_usuario IS NULL 
                AND e.estatus = 1; -- Ajusta este filtro de acuerdo a la lógica de tu aplicación para espacios activos.
            ");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model containerClientesSinAsignar: " . $e->getMessage();
            return;
        }
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
    // espacios
    public static function espacios()
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
    public static function guardarEspacios($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_espacios
                (nombre, tipo_espacio, descripcion, fk_pais, fk_estado, longitud, latitud, direccion, precio_hora, token, ubicacion, estatus)
                    VALUES
                (:nombre, :tipoEspacio, :descripcion, :fkPais, :fkEstado, :longitud, :latitud, :direccion, :PrecioHora, :token, :ubicacion, 1)");
            $query->execute([
                ':nombre' => $datos['nombre'],
                ':tipoEspacio' => $datos['tipo_espacio'],
                ':descripcion' => $datos['desc'],
                ':fkPais' => $datos['id_pais'],
                ':fkEstado' => $datos['id_estado'],
                ':direccion' => $datos['direccion'],
                ':longitud' => $datos['longitud'],
                ':latitud' => $datos['latitud'],
                ':PrecioHora' => $datos['precio'],
                ':token' => $datos['token'],
                ':ubicacion' => $datos['ubicacion']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarSalon: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarEspacios($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_espacios SET
                nombre = :nombre,
                tipo_espacio = :tipoEspacio,
                descripcion = :descripcion,
                fk_pais = :idPais,
                fk_estado = :idEstado,
                direccion = :direccion,
                longitud = :longitud,
                latitud = :latitud,
                token = :token,
                ubicacion = :ubicacion
                    WHERE 
                id_espacio = :idEspacio;");
            $query->execute([
                ':idEspacio' => $datos['id_espacio'],
                ':nombre' => $datos['nombre'],
                ':tipoEspacio' => $datos['tipo_espacio'],
                ':descripcion' => $datos['desc'],
                ':idPais' => $datos['id_pais'],
                ':idEstado' => $datos['id_estado'],
                ':direccion' => $datos['direccion'],
                ':longitud' => $datos['longitud'],
                ':latitud' => $datos['latitud'],
                ':token' => $datos['token'],
                ':ubicacion' => $datos['ubicacion']
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
    public static function actualizarEstatusEspacio($id_espacio, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_espacios SET  
            estatus = :txtEstatus 
                WHERE 
            id_espacio = :idEspacio;");
            $query->execute([
                ':idEspacio' => base64_decode($id_espacio),
                ':txtEstatus' => base64_decode($nuevoEstatus)
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Espacio en uso');
            }
            echo "No podemos eliminar espacio: " . $e->getMessage();
            return false;
        }
    }
    // asignacion contenido
    public static function contenido($id_espacio)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ac.*,
                ce.nombre AS nombre_espacio
            FROM
                asignacion_contenido ac
            JOIN
                cat_espacios ce ON ac.fk_espacio = ce.id_espacio
            WHERE
                fk_espacio = :idEspacio
            ");
            $query->execute([
                ':idEspacio' => base64_decode(base64_decode($id_espacio))
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model usuario: " . $e->getMessage();
            return;
        }
    }
    public static function buscarToken($token)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT token FROM asignacion_contenido WHERE token = :token");
            $query->execute([
                ':token' => $token
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo eventos: " . $e->getMessage();
            return [];
        }
    }
    public static function guardarContenido($datos)
    {
        try {
            $fecha = date("Y-m-d h:m:s");
            $usuario = $_SESSION['id_usuario-' . constant('Sistema')];
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO asignacion_contenido
                (fk_usuario, fk_espacio, fecha, token, ubicacion, estatus)
                    VALUES
                (:fkUsuario, :fkEspacio, :fecha, :token, :ubicacion, 1)");
            $query->execute([
                ':fkUsuario' => $usuario,
                ':fkEspacio' => base64_decode(base64_decode($datos['id_espacio'])),
                ':fecha' => $fecha,
                ':token' => $datos['token'],
                ':ubicacion' => $datos['ubicacion']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarContenido: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarContenido($datos)
    {
        try {
            $usuario = $_SESSION['id_usuario-' . constant('Sistema')];
            $fecha = date("Y-m-d h:m:s");
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE asignacion_contenido SET
                fk_usuario = :fkUsuario,
                fk_espacio = :fkEspacio,
                fecha = :fecha,
                token = :token,
                ubicacion = :ubicacion
                    WHERE 
                id_asignacion_contenido = :idContenido;");
            $query->execute([
                ':idContenido' => $datos['id_contenido'],
                ':fkEspacio' => base64_decode(base64_decode($datos['id_espacio'])),
                ':fkUsuario' => $usuario,
                ':fecha' => $fecha,
                ':token' => $datos['token'],
                ':ubicacion' => $datos['ubicacion']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarContenido: " . $e->getMessage();
            return false;
        }
    }
    public static function buscarContenido($id_asignacion_contenido)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM asignacion_contenido WHERE id_asignacion_contenido = :idContenido;");
            $query->execute([
                ':idContenido' => $id_asignacion_contenido
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarEspacio: " . $e->getMessage();
            return;
        }
    }
    public static function actualizarEstatusContenido($id_asignacion_contenido, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE asignacion_contenido SET  
            estatus = :txtEstatus 
                WHERE 
            id_asignacion_contenido = :idContenido;");
            $query->execute([
                ':idContenido' => base64_decode($id_asignacion_contenido),
                ':txtEstatus' => base64_decode($nuevoEstatus)
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            if ($e->getCode() == '23000') {
                throw new Exception('Espacio en uso');
            }
            echo "No podemos eliminar contenido: " . $e->getMessage();
            return false;
        }
    }
}
