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
            $_SESSION['id_empleado-' . constant('Sistema')] = $user['id_empleado'];
            $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['Nombre'];
            $_SESSION['usuario-' . constant('Sistema')] = $user['Apellidos'];
            $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
            $data = [
              'estatus' => 'success',
              'titulo' => 'Bienvenido',
              'respuesta' => 'Bienvenido al sistema empleado'
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
  function accesoUser()
  {
    try {
      $datos = $_POST;
      $user = LoginModel::accesoUser($_POST);
      if ($user != false && $user['Correo'] == $_POST['mailUser']) {
        if ($user['estatus'] == 2) {
          $data = [
            'estatus' => 'error',
            'titulo' => 'Cuenta inhabilitada',
            'respuesta' => 'Tu cuenta está suspendida, contacta a soporte técnico'
          ];
        } else {
          $permiso = LoginModel::VerificarPermiso();
          if ($permiso['Permisos'] == 0) {
            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $fechaVieja = $datos['fechaInicio'];
            $horaVieja = $datos['horaInicio'];
            $timestampHoraActual = strtotime($hora);
            $timestampHoraVieja = strtotime($horaVieja);
            $timestampHoraVieja -= (15 * 60);

            if ($fecha <= $fechaVieja) {
              if ($timestampHoraActual <= $timestampHoraVieja) {
                $idUsuario = $user['id_usuario'];
                $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
                $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['Nombre'];
                $_SESSION['usuario-' . constant('Sistema')] = $user['Apellido_paterno'];
                $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
                $registro_dispositivo = LoginModel::RegistroDispositivo($datos);
                $asignacion_sesion = LoginModel::asignacion_sesion([
                  'id_usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                  'id_sesion' => $_POST['idSesion'],
                  'id_dispositivo' => $registro_dispositivo['id_dispositivo']
                ]);
                if ($asignacion_sesion['estatus'] == 'success') {
                  $data = [
                    'estatus' => 'success',
                    'titulo' => 'Bienvenido',
                    'respuesta' => ''
                  ];
                } else {
                  $data = [
                    'estatus' => 'warning',
                    'titulo' => 'Algo salio mal en la asignacion sesion',
                    'respuesta' => ''
                  ];
                }
              } else {
                $data = [
                  'estatus' => 'warning',
                  'titulo' => 'Ya no hay acceso'
                ];
              }
            } else {
              $data = [
                'estatus' => 'error',
                'titulo' => 'No tienes acceso'
              ];
            }
          } elseif ($permiso['Permisos'] == 1) {
            $fecha = date("Y-m-d");
            $fechaVieja = $datos['fechaInicio'];
            if ($fecha <= $fechaVieja) {
              $idUsuario = $user['id_usuario'];
              $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
              $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['Nombre'];
              $_SESSION['usuario-' . constant('Sistema')] = $user['Apellido_paterno'];
              $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
              $registro_dispositivo = LoginModel::RegistroDispositivo($datos);
              $asignacion_sesion = LoginModel::asignacion_sesion([
                'id_usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                'id_sesion' => $_POST['idSesion'],
                'id_dispositivo' => $registro_dispositivo['id_dispositivo']
              ]);
              if ($asignacion_sesion['estatus'] == 'success') {
                $data = [
                  'estatus' => 'success',
                  'titulo' => 'Bienvenido',
                  'respuesta' => ''
                ];
              } else {
                $data = [
                  'estatus' => 'warning',
                  'titulo' => 'Algo salio mal en la asignacion sesion',
                  'respuesta' => ''
                ];
              }
            } else {
              $data = [
                'estatus' => 'warning',
                'titulo' => 'Ya no hay acceso'
              ];
            }
          } else {
            $data = [
              'estatus' => 'warning',
              'titulo' => 'Algo anda mal',
              'respuesta' => 'Algo no esta funcionando correctamente'
            ];
          }
        }
      } else {
        $data = [
          'estatus' => 'warning',
          'titulo' => 'Correo no encontrado',
          'respuesta' => 'No encontramos tu correo. ¿Deseas registrarte?'
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
  public function registro()
  {
    try {
      $permiso = LoginModel::VerificarPermiso();
      if ($permiso['Permisos'] == 0) {
        $datos = $_POST;
        $datos['pass'] = encrypt_decrypt('encrypt', $datos['pass']);
        $registro = LoginModel::registro($datos);
        if ($registro != false) {
          $fecha = date("Y-m-d");
          $hora = date("H:i:s");
          $fechaVieja = $datos['FechaInicioModel'];
          $horaVieja = $datos['HoraInicioModel'];
          $timestampHoraActual = strtotime($hora);
          $timestampHoraVieja = strtotime($horaVieja);
          $timestampHoraVieja -= (15 * 60);
          if ($fecha <= $fechaVieja) {
            if ($timestampHoraActual <= $timestampHoraVieja) {
              $idUsuario = $registro['id_usuario'];
              $_SESSION['id_usuario-' . constant('Sistema')] = $idUsuario;
              $_SESSION['nombre_usuario-' . constant('Sistema')] = $datos['nombre'];
              $_SESSION['correo-' . constant('Sistema')] = $datos['correo'];
              $_SESSION['tipo_usuario-' . constant('Sistema')] = 2;
              $data = [
                'estatus' => 'success',
                'titulo' => 'Bienvenido',
                'respuesta' => 'Registro aceptado, bienvenido'
              ];
              $registro_dispositivo = LoginModel::RegistroDispositivo($datos);
              if ($registro_dispositivo['estatus'] === 'success') {
                $asignacion_sesion = LoginModel::asignacion_sesion([
                  'id_usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                  'id_sesion' => $_POST['idSesion-modal'],
                  'id_dispositivo' => $registro_dispositivo['id_dispositivo']
                ]);

                if ($asignacion_sesion['estatus'] === 'success') {
                  $data = [
                    'estatus' => 'success',
                    'titulo' => 'Bienvenido',
                    'respuesta' => ''
                  ];
                } else {
                  $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error al asignar sesión',
                    'respuesta' => 'Hubo un error al asignar la sesión al dispositivo registrado'
                  ];
                }
              } else {
                $data = [
                  'estatus' => 'error',
                  'titulo' => 'Error al registrar dispositivo',
                  'respuesta' => 'Hubo un error al registrar el dispositivo'
                ];
              }
            } else {
              $data = [
                'estatus' => 'warning',
                'titulo' => 'Ya no hay acceso'
              ];
            }
          } else {
            $data = [
              'estatus' => 'warning',
              'titulo' => 'Ya no hay acceso'
            ];
          }
        } else {
          $data = [
            'estatus' => 'error',
            'titulo' => 'Hubo un problema',
            'respuesta' => 'Error al registrarte'
          ];
        }
      } elseif ($permiso['Permisos'] == 1) {
        $datos = $_POST;
        $datos['pass'] = encrypt_decrypt('encrypt', $datos['pass']);
        $registro = LoginModel::registro($datos);
        if ($registro != false) {
          $fecha = date("Y-m-d");
          $fechaVieja = $datos['FechaInicioModel'];
          $horaVieja = $datos['HoraInicioModel'];
          if ($fecha <= $fechaVieja) {
            $idUsuario = $registro['id_usuario'];
            $_SESSION['id_usuario-' . constant('Sistema')] = $idUsuario;
            $_SESSION['nombre_usuario-' . constant('Sistema')] = $datos['nombre'];
            $_SESSION['correo-' . constant('Sistema')] = $datos['correo'];
            $_SESSION['tipo_usuario-' . constant('Sistema')] = 2;
            $data = [
              'estatus' => 'success',
              'titulo' => 'Bienvenido',
              'respuesta' => 'Registro aceptado, bienvenido'
            ];
            $registro_dispositivo = LoginModel::RegistroDispositivo($datos);
            if ($registro_dispositivo['estatus'] === 'success') {
              $asignacion_sesion = LoginModel::asignacion_sesion([
                'id_usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                'id_sesion' => $_POST['idSesion-modal'],
                'id_dispositivo' => $registro_dispositivo['id_dispositivo']
              ]);

              if ($asignacion_sesion['estatus'] === 'success') {
                $data = [
                  'estatus' => 'success',
                  'titulo' => 'Bienvenido',
                  'respuesta' => ''
                ];
              } else {
                $data = [
                  'estatus' => 'error',
                  'titulo' => 'Error al asignar sesión',
                  'respuesta' => 'Hubo un error al asignar la sesión al dispositivo registrado'
                ];
              }
            } else {
              $data = [
                'estatus' => 'error',
                'titulo' => 'Error al registrar dispositivo',
                'respuesta' => 'Hubo un error al registrar el dispositivo'
              ];
            }
          } else {
            $data = [
              'estatus' => 'warning',
              'titulo' => 'Ya no tienes acceso'
            ];
          }
        } else {
          $datos = [
            'estatus' => 'error',
            'titulo' => 'Hubo un problema',
            'respuesta' => 'No podimos registrarte'
          ];
        }
      } else {
        $data = [
          'estatus' => 'error',
          'titulo' => 'Problemas',
          'respuesta' => 'Hubo un problema en la verificación.'
        ];
      }
    } catch (\Throwable $th) {
      $data = [
        'estatus' => 'error',
        'titulo' => 'Error de servidor',
        'respuesta' => 'Contacte al área de sistemas.'
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

  function paises()
  {
    try {
      $catPaises = LoginModel::paises();
      echo json_encode($catPaises);
    } catch (\Throwable $th) {
      echo "Error recopilado controlador paises" . $th->getMessage();
    }
  }
  function prefijos()
  {
    try {
      $catPrefijos = LoginModel::prefijos();
      echo json_encode($catPrefijos);
    } catch (\Throwable $th) {
      echo "Error recopilado controlador prefijos " . $th->getMessage();
    }
  }
  function estado()
  {
    try {
      $id_pais = $_GET['id_pais'];
      $catEstado = LoginModel::estado($id_pais);
      echo json_encode($catEstado);
    } catch (\Throwable $th) {
      echo "Error recopilado controlador estado " . $th->getMessage();
    }
  }

  function cat_conferencias()
  {
    try {
      $catConferencias = LoginModel::conferencias();
      foreach ($catConferencias as &$conferencia) {
        $fechaHoraInicio = new DateTime($conferencia['Fecha_Hora_Inicio']);
        $conferencia['Fecha'] = $fechaHoraInicio->format('Y-m-d');
        $conferencia['Hora'] = $fechaHoraInicio->format('H:i:s');
        unset($conferencia['Fecha_Hora_Inicio']);
      }
      echo json_encode($catConferencias);
    } catch (\Throwable $th) {
      echo "Error recopilado controlador conferencias " . $th->getMessage();
    }
  }
}