$(function () {
    // mostrar paises
    async function pais(identificador, actual = null) {
        try {
            $(identificador).empty();
            let peticion = await fetch(servidor + `admin/pais`);
            let response = await peticion.json();
            let option_select = document.createElement("option")
            option_select.value = '';
            option_select.text = 'Selecciona el país...';
            $(identificador).append(option_select);
            for (let item of response) {
                let option = document.createElement("option")
                option.value = item.id_pais;
                option.text = item.pais + " / " + item.country;
                if (actual != null) {
                    if (item.id_pais == actual) {
                        option.selected = true;
                    }
                }
                $(identificador).append(option)
            }
            console.log('cargando país...');
        } catch (err) {
            if (err.name == 'AbortError') { } else { throw err; }
        }
    }
    async function estado(identificador, id_pais, actual = null) {
        try {
            $(identificador).empty();
            let peticion = await fetch(servidor + `admin/estado?id_pais=${id_pais}`);
            let response = await peticion.json();
            let option_select = document.createElement("option")
            option_select.value = '';
            option_select.text = 'Selecciona el estado...';
            $(identificador).append(option_select);
            for (let item of response) {
                let option = document.createElement("option")
                option.value = item.id_estado;
                option.text = item.Estado + " / " + item.State;
                if (actual != null) {
                    if (item.id_estado == actual) {
                        option.selected = true;
                    }
                }
                $(identificador).append(option)
            }
            console.log('cargando estado...');
        } catch (err) {
            if (err.name == 'AbortError') { } else { throw err; }
        }
    }
    $('#id_pais').change(function () {
        let id_pais = $(this).val();
        estado('#id_estado', id_pais);
    });
    pais('#id_pais');
    // mostrar salones
    async function cardsSalon() {
        try {
            let peticion = await fetch(servidor + `admin/viewSalon`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin salones registrados</h3>`).appendTo("#container-salon").addClass('text-danger');
                return false;
            }
            $("#container-salon").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre</th>
                <th class="text-uppercase">Tipo de espacio</th>
                <th class="text-uppercase">País</th>
                <th class="text-uppercase">Estado</th>
                <th class="text-uppercase">Cordenadas</th>
                <th class="text-uppercase">Precio</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-salon")
                .removeClass("text-danger");
            $('#info-table-result').DataTable({
                "drawCallback": function (settings) {
                    $('.paginate_button').addClass("btn").removeClass("paginate_button");
                    $('.dataTables_length').addClass('pull-left');
                    $('#info-table-result_filter').addClass('pull-right');
                    $('input').addClass("form-control");
                    $('select').addClass('form-control');
                    $('.previous.disabled').addClass("btn-outline-info opacity-5 btn-rounded mx-2 mt-3");
                    $('.next.disabled').addClass("btn-outline-info opacity-5 btn-rounded mx-2 mt-3");
                    $('.previous').addClass("btn-outline-info btn-rounded mx-2 mt-3");
                    $('.next').addClass("btn-outline-info btn-rounded mx-2 mt-3");
                },
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "pageLength": 5,
                "lengthMenu": [[5, 10, 15], [5, 10, "All"]],
                data: response,
                "columns": [
                    { "data": "nombre", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let espacio = (data.tipo_espacio == 1) ? 'Salon' : 'Oficina';
                            return espacio;
                        },
                        className: 'text-vertical text-center'
                    },
                    { "data": "pais", className: 'text-vertical text-center' },
                    { "data": "estado", className: 'text-vertical text-center' },
                    { "data": "cordenadas", className: 'text-vertical text-center'},
                    { "data": "precio_hora", className: 'text-vertical text-center'},
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_espacio)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                </div>`;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-salon')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsSalon();
    // guardar salones o modificar
    $(".btn-salon").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            if ($('#nombre').val().length == 0 || $('#tipo_espacio').val().length == 0 || $('#id_pais').val().length == 0 || $('#id_estado').val().length == 0 || $('#cordenadas').val().length == 0 || $('#precio').val().length == 0) {
                Swal.fire("Por favor llenar todos los campos por favor!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: servidor + "admin/guardarSalon",
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
    // buscar salon
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_espacio'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-salon")[0].reset();
            $('#modalSalonLabel').text('Editar espacio');
            $('#modalSalon').modal('show');
            buscarEspacio(data['id_espacio']);
        }
    });
    async function buscarEspacio(id_espacio) {
        try {
            let peticion = await fetch(servidor + `admin/buscarEspacio/${id_espacio}`);
            let response = await peticion.json();
            console.log(peticion);
            $('#tipo').val('');
            $('#id_espacio').val(response['id_espacio']);
            $('#nombre').val(response['nombre']);
            $('#tipo_espacio').val(response['tipo_espacio']);
            $('#id_pais').val(response['fk_pais']);
            $('#id_estado').val(response['fk_estado']);
            $('#cordenadas').val(response['cordenadas']);
            $('#precio').val(response['precio']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
});