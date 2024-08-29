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
    public static function usuario(){
        try {
            $con = new Database;
            $query = $con->prepare("SELECT * FROM cat_usuario");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model usuario: " . $e->getMessage();
            return;
        }
    }
}
