<form id="form-new-reservacion" action="javascript:;" class="needs-validation" novalidate method="post">
    <input type="hidden" name="id_espacio" id="id_espacio" value="<?= $this->pagoEspacio; ?>">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <label for="fecha_ingreso">Fecha de Inicio <span>*</span></label>
            <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" required>
            <div class="invalid-feedback">
                Ingresa la fecha de ingreso, por favor.
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <label for="fecha_finalizacion">Fecha de finalizacion <span>*</span></label>
            <input type="date" class="form-control" name="fecha_finalizacion" id="fecha_finalizacion">
            <div class="invalid-feedback">
                Ingresa la fecha de finalizacion, por favor.
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2">
        <input type="text" readonly="readonly" name="pago_dia" id="pago_dia" class="form-control">
        <p>Total: $<input type="text" readonly="readonly" name="total" id="total" style="background-color: #fff; border: 0; width: 70px; font-weight: 900;">mxn</p>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2">
        <label for="Nombre">Nombre del titular <span>*</span></label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Juan Garcia Martinez">
        <div class="invalid-feedback">
            Ingresa el nombre del titular, por favor.
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-4">
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>
    </div>
    <input type="hidden" name="stripeToken" id="stripeToken">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <button data-formulario="form-new-reservacion" type="button" class="btn btn-primary btn-reservar mt-3">Reservación</button>
    </div>
</form>
admin.controller.php
function procesamientoPago()
    {
        try {
            require_once('public/vendor/autoload.php');
            \Stripe\Stripe::setApiKey('sk_test_51PxwgDK9TllkJ0UIX9fsNhAhfO5rsuMqYjr31DsSEffdEEvsaMti5bJpmMbQbsZTIT2no8nsxqRjEbLp5rZ1a5v000V5sKouBc');
            $token = $_POST['stripeToken'];
            $amount = $_POST['total'] * 100;
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'mxn',
                'description' => 'Pago por reserva',
                'source' => $token,
            ]);
            $idReserva = LoginModel::registroReserva($_POST);
            if ($idReserva !== false) {
                $_POST['id_asignacion_reservacion'] = $idReserva;
                $resp = LoginModel::registroPago($_POST);
                if ($resp !== false) {
                    $numeroTarjeta = $_POST['numero_tarjeta'];
                    $_POST['numero_tarjeta'] = substr($numeroTarjeta, -4);
                    $tarjeta = LoginModel::registroTarjeta($_POST);
                    if ($tarjeta !== false) {
                        $data = [
                            'estatus' => 'success',
                            'titulo' => 'Pago correcto',
                            'respuesta' => 'El pago se completó correctamente'
                        ];
                    } else {
                        $data = [
                            'estatus' => 'error',
                            'titulo' => 'Error pago',
                            'respuesta' => 'No se pudo procesar el pago.'
                        ];
                    }
                } else {
                    $data = [
                        'estatus' => 'error',
                        'titulo' => 'Error pago',
                        'respuesta' => 'Pago incorrecto, contacte servicio Atención al cliente'
                    ];
                }
            } else {
                $data = [
                    'estatus' => 'error',
                    'titulo' => 'Error registro reserva',
                    'respuesta' => 'Error en el registro de la reserva'
                ];
            }
        } catch (\Throwable $th) {
            echo "Error en el controlador: " . $th->getMessage();
            return;
        }
        echo json_encode($data);
    }
quiero registrar los datos de la tarjeta de stripe como accedo a esos campos
el login.model.php
public static function registroTarjeta($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_tarjeta
            (fk_usuario, numero_tarjeta, fecha_vencimiento, cvc, nombre_titular)
            VALUES
            (:usuario, :numero, :fecha, :cvc, :nombre)");
            $query->execute([
                ':usuario' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':numero' => $datos['cardnumber'],
                ':fecha' => $datos['exp-date'],
                ':cvc' => $datos['cardCvc'],
                ':nombre' => $datos['name']
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model registroPago: " . $e->getMessage();
            return false;
        }
    }