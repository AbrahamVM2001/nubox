<?php
define("HOST", "localhost");//Servidor donde se aloja la base de datos
define("DB", "u547745900_nubox");//Nombre de la base de datos
define("USER", "u547745900_nubox");//Usuario de la base de datos
define("PASSWORD", "9XJQd5Ep#H;h");//Contraseña de usuario de la base de datos
define("CHARSET", "utf8");//Codificación de caracteres.

    $db = new Database();
    try {
        $query = $db->pdo->prepare("SELECT * FROM configuracion");
        $query->execute();
        $resp = $query->fetchAll();
    } catch (PDOException $e) {
        echo "Error recopilado: ".$e->getMessage();
        return [];
    }
define("URL",$resp[0]['dominio_sociedad']);//Dominio del sistema
define("Sistema", "programa-academico");//Nombre del sistema

define("LOGOTIPO", $resp[0]['ruta_logotipo']);
define("ICONO",$resp[0]['ruta_icono']);
define("SOCIEDAD",$resp[0]['nombre_sociedad']);
define("NOMBRESISTEMA",$resp[0]['nombre_sistema']);
define("DESCRIPCIONSISTEMA",$resp[0]['descripcion_sistema']);
define("CORREOSOPORTE",$resp[0]['correo_soporte']);
define("PASSWORDCORREOSOPORTE",$resp[0]['password_correo']);

