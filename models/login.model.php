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
    // funciones de carruseles
    public static function viewSalon(){
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
    public static function viewOficina(){
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
    public static function espacio($id_espacio){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
            SELECT
                ac.*,
                ar.*,
                ce.*
            FROM
                asignacion_contenido ac
            LEFT JOIN
                asignacion_reservacion ar ON ac.fk_espacio = ar.fk_espacio
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
    // pantalla para mostrar todos los salones
    public static function mostrarSalones(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = '1';");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model salones: " .$e->getMessage();
            return;
        }
    }
    public static function mostrarOficinas(){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_espacios WHERE tipo_espacio = '2';");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error model oficinas: " .$e->getMessage();
            return;
        }
    }
}
