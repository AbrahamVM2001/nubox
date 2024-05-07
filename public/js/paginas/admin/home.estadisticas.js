$(function () {
    async function cardsConferencia() {
        try {
            let peticion = await fetch(servidor + `admin/conferencia`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin conferencia asignadas</h3>`).appendTo("#container-estadisticas").addClass('text-danger');
                return false;
            }
            $("#container-estadisticas").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Tema</th>
                <th class="text-uppercase">Fecha y Hora inicio</th>
                <th class="text-uppercase">Fecha y Hora termino</th>
                <th class="text-uppercase">Descripcion</th>
                <th class="text-uppercase">Estatus</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-estadisticas")
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Tema_sesion", className: 'text-vertical text-center' },
                    { "data": "Fecha_Hora_Inicio", className: 'text-vertical text-center' },
                    { "data": "Fecha_Hora_Termino", className: 'text-vertical text-center' },
                    { "data": "Descripcion", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let tipo_estatus = (data.Estatus == 1) ? 'Habilitado' : 'Desabilitado';
                            return tipo_estatus;
                        },
                        className: 'text-vertical text-center'
                    },
                    {
                        data: null,
                        render: function (data) {
                            botones = `
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-between align-items-center" >
                                <button data-id="${btoa(btoa(data.id_sesiones))}" data-bs-toggle="tooltip" title="estadisticas" type="button" class="btn btn-estadisticas btn-info"><i class="fa-solid fa-chart-simple"></i></button>
                            </div>`;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }

                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-estadisticas')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsConferencia();
    $(document).on('click', '.btn-estadisticas', async function () {
        var id_sesiones = $(this).data('id');
        $.ajax({
            url: servidor + 'admin/conteoLogeo/' + id_sesiones,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#modalEstadisticas').modal('show');
                $('#total-asistentes').text(response[0].Total_Usuarios_Logeados);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        await cardsPais(id_sesiones);
        await cardsConectividad(id_sesiones);
        await cardsEstado(id_sesiones);
        await cardsVotacion(id_sesiones);
    });
    async function cardsPais(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/tablaPaisesLogeoados/${id_sesiones}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin paises asignados</h3>`).appendTo("#container-pais").addClass('text-danger');
                return false;
            }
            $("#container-pais").empty();
            jQuery(`
                <h4>Pais</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <button class="btn btn-info btn-copiar-pais" onclick="copiarTexto('info-table-pais')">COPY <i class="fa-solid fa-copy"></i></button>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <button class="btn btn-success btn-excel-pais">EXCEL <i class="fa-solid fa-table"></i></button>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <button class="btn btn-danger btn-pdf-pais">PDF <i class="fa-solid fa-file-pdf"></i></button>
                    </div>
                </div>
                <table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-pais">
                    <thead><tr>
                        <th class="text-uppercase">Nombre Completo</th>
                        <th class="text-uppercase">Pais</th>
                        <th class="text-uppercase">Fecha Y Hora</th>
                    </tr></thead>
                </table>
                `)
                .appendTo("#container-pais")
                .removeClass("text-danger");
            $('#info-table-pais').DataTable({
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Nombre_Completo", className: 'text-vertical text-center' },
                    { "data": "Pais", className: 'text-vertical text-center' },
                    { "data": "Fecha_y_Hora", className: 'text-vertical text-center' }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(document).on('click', '.btn-pdf-pais, .btn-pdf-conectividad, .btn-pdf-estado', function () {
        let tablaData = [];
        let tableId = '';
    
        if ($(this).hasClass('btn-pdf-pais')) {
            tableId = '#info-table-pais';
        } else if ($(this).hasClass('btn-pdf-conectividad')) {
            tableId = '#info-table-conectividad';
        } 
    
        $(tableId + ' tbody tr').each(function () {
            let rowData = [];
            $(this).find('td').each(function () {
                rowData.push($(this).text());
            });
            tablaData.push(rowData);
        });
    
        if (tablaData.length > 0) {
            $.ajax({
                type: "POST",
                url: servidor + "admin/generarPDF",
                dataType: "json",
                data: { tablaData: JSON.stringify(tablaData) },
                success: function (response) {
                    var link = document.createElement('a');
                    link.href = servidor + "" + response.url;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            console.error("Error: No se encontraron datos en la tabla.");
        }
    });
    async function cardsEstado(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/tablaEstadoLogeados/${id_sesiones}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin estados asignados</h3>`).appendTo("#container-estado").addClass('text-danger');
                return false;
            }
            $("#container-estado").empty();
            jQuery(`
            <h4>Estado</h4>
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-info btn-copiar-conectividad" onclick="copiarTexto('info-table-estado')">COPY <i class="fa-solid fa-copy"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-success btn-excel-estado">EXCEL <i class="fa-solid fa-table"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-danger btn-pdf-estado">PDF <i class="fa-solid fa-file-pdf"></i></button>
                </div>
            </div>
            <table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-estado">
                <thead><tr>
                    <th class="text-uppercase">Estado</th>
                    <th class="text-uppercase">Conteo</th>
                </tr></thead>
            </table>
            `)
                .appendTo("#container-estado")
                .removeClass("text-danger");
            $('#info-table-estado').DataTable({
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Estado", className: 'text-vertical text-center' },
                    { "data": "Conteo", className: 'text-vertical text-center' }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario');
                    tablaData.push(data); // Agregar datos de la fila al arreglo
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(document).on('click', '.btn-pdf-estado', function () {
        let tablaData = [];
        let tableId = '';
    
        tableId = '#info-table-estado'; 
    
        $(tableId + ' tbody tr').each(function () {
            let rowData = [];
            $(this).find('td').each(function () {
                rowData.push($(this).text());
            });
            tablaData.push(rowData);
        });
    
        if (tablaData.length > 0) {
            $.ajax({
                type: "POST",
                url: servidor + "admin/generarPDFEstado",
                dataType: "json",
                data: { tablaData: JSON.stringify(tablaData) },
                success: function (response) {
                    var link = document.createElement('a');
                    link.href = servidor + "" + response.url;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            console.error("Error: No se encontraron datos en la tabla.");
        }
    });
    async function cardsConectividad(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/tablaConectividadLogeados/${id_sesiones}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin conectividad asignados</h3>`).appendTo("#container-logeos").addClass('text-danger');
                return false;
            }
            $("#container-logeos").empty();
            jQuery(`
            <h4>Conectividad</h4>
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-info btn-copiar-conectividad" onclick="copiarTexto('info-table-conectividad')">COPY <i class="fa-solid fa-copy"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-success btn-excel-conectividad">EXCEL <i class="fa-solid fa-table"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-danger btn-pdf-conectividad">PDF <i class="fa-solid fa-file-pdf"></i></button>
                </div>
            </div>
            <table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-conectividad">
                <thead><tr>
                    <th class="text-uppercase">Nombre Completo</th>
                    <th class="text-uppercase">Dispositivo</th>
                    <th class="text-uppercase">Fecha y Hora</th>
                </tr></thead>
            </table>
            `)
                .appendTo("#container-logeos")
                .removeClass("text-danger");
            $('#info-table-conectividad').DataTable({
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Nombre_Completo", className: 'text-vertical text-center' },
                    { "data": "Dispositivo", className: 'text-vertical text-center' },
                    { "data": "Fecha_y_Hora", className: 'text-vertical text-center' }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario');
                    tablaData.push(data); // Agregar datos de la fila al arreglo
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    async function cardsVotacion(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/tablaVotosLogeados/${id_sesiones}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin votaciones asignados</h3>`).appendTo("#container-votaciones").addClass('text-danger');
                return false;
            }
            $("#container-votaciones").empty();
            jQuery(`
            <h4>Votos</h4>
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-info btn-copiar-votacion" onclick="copiarTexto('info-table-votos')">COPY <i class="fa-solid fa-copy"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-success btn-excel-votacion">EXCEL <i class="fa-solid fa-table"></i></button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-danger btn-pdf-votacion">PDF <i class="fa-solid fa-file-pdf"></i></button>
                </div>
            </div>
            <table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-votos">
                <thead><tr>
                    <th class="text-uppercase">Nombre completo</th>
                    <th class="text-uppercase">Contestadas</th>
                    <th class="text-uppercase">Aciertos</th>
                </tr></thead>
            </table>
            `)
                .appendTo("#container-votaciones")
                .removeClass("text-danger");
            $('#info-table-votos').DataTable({
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Nombre_completo", className: 'text-vertical text-center' },
                    { "data": "Contestadas", className: 'text-vertical text-center' },
                    { "data": "Aciertos", className: 'text-vertical text-center' }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario');
                    tablaData.push(data); // Agregar datos de la fila al arreglo
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(document).on('click', '.btn-pdf-votacion', function () {
        let tablaData = [];
        let tableId = '';
    
        tableId = '#info-table-votos';
    
        $(tableId + ' tbody tr').each(function () {
            let rowData = [];
            $(this).find('td').each(function () {
                rowData.push($(this).text());
            });
            tablaData.push(rowData);
        });
    
        if (tablaData.length > 0) {
            $.ajax({
                type: "POST",
                url: servidor + "admin/generarPDFVotos",
                dataType: "json",
                data: { tablaData: JSON.stringify(tablaData) },
                success: function (response) {
                    var link = document.createElement('a');
                    link.href = servidor + "" + response.url;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            console.error("Error: No se encontraron datos en la tabla.");
        }
    });
    $(document).on('click', '.btn-excel-pais', function () {
        const tableData = $('#info-table-pais').DataTable().rows().data().toArray();
        descargarExcelPais(tableData, 'tabla_pais.xlsx');
    });
    $(document).on('click', '.btn-excel-conectividad', function () {
        const tableData = $('#info-table-conectividad').DataTable().rows().data().toArray();
        descargarExcelConectividad(tableData, 'tabla_conectividad.xlsx');
    });
    $(document).on('click', '.btn-excel-estado', function() {
        const tableData = $('#info-table-estado').DataTable().rows().data().toArray();
        descargarExcelEstado(tableData, 'tabla_estado.xlsx');
    });
    $(document).on('click', '.btn-excel-votacion', function() {
        const tableData = $('#info-table-votos').DataTable().rows().data().toArray();
        descargarExcelVotacion(tableData, 'tabla_votacion.xlsx');
    });
});
function copiarTexto(tableId) {
    var table = document.getElementById(tableId);
    var range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand("copy");
    window.getSelection().removeAllRanges();
    alert("La tabla ha sido copiada!");
}
function descargarExcelPais(data, filename) {
    const dataArray = data.map(obj => [obj.Nombre_Completo, obj.Pais, obj.FechaLogeo]);
    dataArray.unshift(['Nombre_Completo', 'Pais', 'Fecha y Hora']);
    const ws = XLSX.utils.aoa_to_sheet(dataArray);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, filename);
}
function descargarExcelConectividad(data, filename) {
    const dataArray = data.map(obj => [obj.Nombre_Completo, obj.Dispositivo, obj.Fecha_y_Hora]);
    dataArray.unshift(['Nombre_Completo', 'Dispositivo', 'Fecha y Hora']);
    const ws = XLSX.utils.aoa_to_sheet(dataArray);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, filename);
}

function descargarExcelEstado(data, filename) {
    const dataArray = data.map(obj => [obj.Estado, obj.Conteo]);
    dataArray.unshift(['Estado', 'Conteo']);
    const ws = XLSX.utils.aoa_to_sheet(dataArray);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, filename);
}

function descargarExcelVotacion(data, filename) {
    const dataArray = data.map(obj => [obj.Nombre_completo, obj.Contestadas, obj.Aciertos]);
    dataArray.unshift(['Nombre_completo', 'Contestadas', 'Aciertos']);
    const ws = XLSX.utils.aoa_to_sheet(dataArray);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, filename);
}