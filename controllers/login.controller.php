<?php

class Login extends ControllerBase
{

  function __construct()
  {
    parent::__construct();
  }
  function render()
  {
    $this->view->render('login/index');
  }
  function acceso()
  {
    try {
      $user = LoginModel::user($_POST);
      if ($user != false && $user['correo'] == $_POST['mail']) {
        /* echo json_encode("Correcto usuario"); */
        if ($user['password'] == encrypt_decrypt('encrypt', $_POST['pass'])) {
          /* echo json_encode("Correcto password"); */
          if ($user['estatus'] == "1") {
            $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
            $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['Nombre'];
            $_SESSION['usuario-' . constant('Sistema')] = $user['Apellidos'];
            $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
            $data = [
              'estatus' => 'success',
              'titulo' => 'Bienvenido',
              'respuesta' => 'Bienvenido usuario'
            ];
          } else {
            $data = [
              'estatus' => 'error',
              'titulo' => 'Ingreso invalido',
              'respuesta' => 'No tienes autorización para ingresar'
            ];
          }
        } else {
          $data = [
            'estatus' => 'error',
            'titulo' => 'Contraseña incorrecta',
            'respuesta' => 'La contraseña ingresada es incorrecta'
          ];
        }
      } else {
        $data = [
          'estatus' => 'error',
          'titulo' => 'Usuario incorrecto',
          'respuesta' => 'El usuario ingresado es incorrecto'
        ];
      }
    } catch (\Throwable $th) {
      echo "error controlador acceso: " . $th->getMessage();
      $data = [
        'estatus' => 'error',
        'titulo' => 'Error de servidor',
        'respuesta' => 'Contacte al área de sistemas'
      ];
    }
    echo json_encode($data);
  }
  function salir()
  {
    unset($_SESSION['id_usuario-' . constant('Sistema')]);
    unset($_SESSION['nombre_usuario-' . constant('Sistema')]);
    unset($_SESSION['usuario-' . constant('Sistema')]);
    unset($_SESSION['tipo_usuario-' . constant('Sistema')]);
    /* session_destroy(); */
    header("location:" . constant('URL'));
  }
}