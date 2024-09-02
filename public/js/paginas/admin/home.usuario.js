$(function () {
    // mostrar usuario
    async function cardsUsuario() {
        try {
            let peticion = await fetch(servidor + `admin/viewUsuario`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin usuarioss registrados</h3>`).appendTo("#container-usuario").addClass('text-danger');
                return false;
            }
            $("#container-usuario").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre del usuario</th>
                <th class="text-uppercase">Tipo de usuario</th>
                <th class="text-uppercase">Correo</th>
                <th class="text-uppercase">Contraseña</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-usuario")
                .removeClass("text-danger");
            $('#info-table-result').DataTable({
                "drawCallback": function (settings) {
                    $('.paginate_button').addClass("btn").removeClass("paginate_button");
                    $('.dataTables_length').addClass('pull-left');
                    $('#info-table-result_filter').addClass('pull-right');
                    $('input').addClass("form-control");
                    $('select').addClass('form-control');
                    $('.previous.disabled').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.next.disabled').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.previous').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.next').addClass("btn-light btn-rounded mx-2 mt-3");
                },
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "pageLength": 5,
                "lengthMenu": [[5, 10, 15], [5, 10, "All"]],
                data: response,
                "columns": [
                    { "data": "nombre_completo", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let estatus = (data.tipo_usuario == 1) ? 'Admin' : 'Usuario';
                            return estatus;
                        },
                        className: 'text-vertical text-center'
                    },
                    { "data": "correo", className: 'text-vertical text-center' },
                    { "data": "contrasena", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_usuario)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                </div>`;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsUsuario();
    // guardar y modificar usuario
    $(".btn-usuario").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            if ($('#nombre').val().length == 0 || $('#apaterno').val().length == 0 || $('#tipo_usuario').val().length == 0) {
                Swal.fire("Por favor llenar todos los campos por favor!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: servidor + "admin/guardarUsuario",
                    dataType: "json",
                    data: new FormData(form.get(0)),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        // setting a timeout
                        $("#loading").addClass("loading");
                    },
                    success: function (data) {
                        console.log(data);
                        Swal.fire({
                            position: "top-end",
                            icon: data.estatus,
                            title: data.respuesta,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function (data) {
                        console.log("Error ajax");
                        console.log(data);
                    },
                });
            }
        }
        form.addClass("was-validated");
    });
    // comprobar de en tiempo real si cumple las reglas de la contraseña
    function validatePassword() {
        let password = $('#pass').val();
        let confirmPassword = $('#pass_confir').val();
        let hasUppercase = /[A-Z]/.test(password);
        let hasLowercase = /[a-z]/.test(password);
        let hasNumbers = /[0-9]/.test(password);
        let hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        let isLongEnough = password.length >= 8;
        let isShortEnough = password.length <= 32;
        let passwordsMatch = password === confirmPassword;
        updateValidationUI('.verificar-mayusculas', hasUppercase);
        updateValidationUI('.verificar-minusculas', hasLowercase);
        updateValidationUI('.verificar-numeros', hasNumbers);
        updateValidationUI('.verificar-caracteres', hasSpecial);
        updateValidationUI('.verificar-meno', isLongEnough);
        updateValidationUI('.verificar-mayor', isShortEnough);
        updateValidationUI('.verificar-similar', passwordsMatch && isLongEnough && isShortEnough);
    }
    function updateValidationUI(selector, isValid) {
        let icon = $(selector);
        icon.html('');
        let svg = isValid 
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="8.48" viewBox="0 0 1179 1000"><path fill="#059669" d="M1179 72Q929 294 579 822l-115 179Q320 821 0 501l107-107l286 250q150-150 279-271.5T877.5 185T1009 74t77-59l21-14q4 0 11 2t26 19.5t35 49.5"/></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="#e11d48" d="m427.314 107.313l-22.628-22.626L256 233.373L107.314 84.687l-22.628 22.626L233.373 256L84.686 404.687l22.628 22.626L256 278.627l148.686 148.686l22.628-22.626L278.627 256z"/></svg>';
        icon.html(svg);
    }
    $('#pass').on('input', validatePassword);
    $('#pass_confir').on('input', validatePassword);
    // modificar usuario
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_usuario'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-usuario")[0].reset();
            $('#modalUsuarioLabel').text('Editar usuario');
            $('#modalUsuario').modal('show');
            buscarUsuario(data['id_usuario']);
        }
    });
    async function buscarUsuario(id_usuario) {
        try {
            let peticion = await fetch(servidor + `admin/buscarUsuario/${id_usuario}`);
            let response = await peticion.json();
            $('#tipo').val('');
            $('#id_usuario').val(response['id_usuario']);
            $('#nombre').val(response['nombre']);
            $('#apaterno').val(response['apellido_paterno']);
            $('#amaterno').val(response['apellido_materno']);
            $('#tipo_usuario').val(response['tipo_usuario']);
            $('#correo').val(response['correo']);
            $('#pass').val(response['contrasena']);
            $('#pass_confir').val(response['contrasena']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    // activar y desactivar estatus usuario
    $(document).on('click', '.btn-suspender', function () {
        suspender($(this).data('id'), $(this).data('estatus'));
    });
    function suspender(idUsuario, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/activar_desactivar_usuario",
            dataType: "json",
            data: { id_usuario: idUsuario, estatus: estatus },
            beforeSend: () => $("#loading").addClass("loading"),
            success: (data) => {
                Swal.fire({
                    position: "top-end",
                    icon: data.estatus,
                    title: data.titulo,
                    text: data.respuesta,
                    showConfirmButton: false,
                    timer: 2000,
                });
                setTimeout(() => location.reload(), 2000);
            },
            error: (data) => console.log("Error ajax", data),
            complete: () => $("#loading").removeClass("loading"),
        });
    }
});