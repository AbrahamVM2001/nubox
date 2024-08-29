<?php
require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");
require_once("public/phpqrcode/qrlib.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Admin extends ControllerBase
{
    function __construct(){
        parent::__construct();
    }
    /* inicio */
    function render(){
        if ($this->verificarAdmin()) {
            $this->view->render("admin/index");
        } else {
            $this->recargar();
        }
    }
    // usuario
    function usuario(){
        if ($this->verificarAdmin()) {
            $this->view->render("admin/usuario");
        } else {
            $this->recargar();
        }
    }
}