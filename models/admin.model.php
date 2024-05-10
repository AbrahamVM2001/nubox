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
    /* Empleado */
    public static function empleado()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT id_empleado, CONCAT(Nombre, ' ' ,Apellidos) AS Nombre_completo, Fecha_nacimiento, Edad, Telefono, Curp, correo, password, estatus FROM cat_empleado;");
            $query->execute();
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error recopilado model conferencia: " . $e->getMessage();
            return;
        }
    }
    public static function guardarEmpleado($datos, $contra_encriptada)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_empleado 
            (Nombre,Apellidos,Fecha_nacimiento,Edad,Telefono,Curp,tipo_usuario,correo,password,estatus) 
        VALUES 
            (:nom,:ape,:nacimiento,:edad,:cel,:curp,2,:email,:contra,1)");
            $query->execute([
                ':nom' => $datos['nombre'],
                ':ape' => $datos['apellidos'],
                ':nacimiento' => $datos['nacimiento'],
                ':edad' => $datos['edad'],
                ':cel' => $datos['telefono'],
                ':curp' => $datos['curp'],
                ':email' => $datos['mail'],
                ':contra' => $contra_encriptada
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarEmpleado: " . $e->getMessage();
            return false;
        }
    }
    public static function actualizarEmpleado($datos, $contra_encriptada)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE cat_empleado SET 
                Nombre = :nom,
                Apellidos = :ape,
                Fecha_nacimiento = :fecha,
                Edad = :edad,
                Telefono = :tele,
                Curp = :curp,
                correo = :mail,
                password = :contra
                    WHERE 
                id_empleado = :idEmpleado;");
            $query->execute([
                ':nom' => $datos['nombre'],
                ':ape' => $datos['apellidos'],
                ':fecha' => $datos['nacimiento'],
                ':edad' => $datos['edad'],
                ':tele' => $datos['telefono'],
                ':curp' => $datos['curp'],
                ':mail' => $datos['mail'],
                ':contra' => $contra_encriptada,
                ':idEmpleado' => $datos['empleado']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarEmpleado: " . $e->getMessage();
            return false;
        }
    }
    public static function ActDesc_empleado($id_empleado, $nuevoEstatus)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();

            $query = $con->pdo->prepare("UPDATE cat_empleado SET  
            estatus = :estatus 
                WHERE 
            id_empleado = :idEmpleado;");
            $query->execute([
                ':idEmpleado' => base64_decode($id_empleado),
                ':estatus' => base64_decode($nuevoEstatus)
            ]);

            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "No podemos eliminar usuario: " . $e->getMessage();
            return false;
        }
    }
    public static function buscarEmpleado($id_empleado)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_empleado WHERE id_empleado = :idEmpleado;");
            $query->execute([
                ':idEmpleado' => $id_empleado
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarEmpleado: " . $e->getMessage();
            return;
        }
    }
    // salon
    public static function salon()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_salon;");
            $query->execute();
            return $query->fetchAll();
        } catch (\Throwable $e) {
            echo "Error recopilado model salon: " . $e->getMessage();
            return;
        }
    }
    public static function buscarTokenSalon($token){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_salon WHERE token = :token");
            $query->execute([
                ':token' => $token
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo eventos: " . $e->getMessage();
            return [];
        }
    }
    public static function guardarSalon($datos,$rutaImagen, $token)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_salon
            (Nombre,Descripcion,Caracteristicas,Ubicacion,Aforo,Precio,titulo,Imagen,token,estatus)
        VALUES 
            (:nom,:decs,:caract,:ubi,:afo,:pre,:titu,:ima,:token,:esta)");
            $query->execute([
                ':nom' => $datos['nombre'],
                ':decs' => $datos['decripcion'],
                ':caract' => $datos['caracteristicas'],
                ':ubi' => $datos['ubicacion'],
                ':afo' => $datos['aforo'],
                ':pre' => $datos['precio'],
                ':titu' => $datos['titulo'],
                ':ima' => $rutaImagen,
                ':token' => $token,
                ':esta' => $datos['estatus']
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
            $query = $con->pdo->prepare("UPDATE cat_empleado SET 
                Nombre = :nom
                    WHERE 
                id_empleado = :idEmpleado;");
            $query->execute([
                ':nom' => $datos['nombre']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarSalon: " . $e->getMessage();
            return false;
        }
    }
}
