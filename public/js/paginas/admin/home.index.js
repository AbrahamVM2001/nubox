$(function () {
    // mostrar en el contenedor de ventas totales
    async function cardsVentasTotales() {
        try {
            let peticion = await fetch(servidor + `admin/containerVentasTotales`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay ventas todavía</h3>`)
                    .appendTo("#container-ventas-totales")
                    .addClass('text-danger');
                return false;
            }
            $("#container-ventas-totales").empty();
            let texto = `
                <div class="card p-3" style="background-color: #1b1b1b;">
                    <div class="card-title">
                        <p class="fw-bolder" style="color: #fff; font-size: 32px;">Ventas Totales</p>
                    </div>
                    <div class="card-body">
                        <p class="fw-bold" style="color: #ff0000; font-size: 22px;">${response.total_monto}</p>
                    </div>
                </div>
            `;
            $("#container-ventas-totales").append(texto);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsVentasTotales();
    // mostrar en el contenedor espacios
    async function cardsEspacios() {
        try {
            let peticion = await fetch(servidor + `admin/containerEspacios`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay espacios rentados</h3>`)
                    .appendTo("#container-espacios")
                    .addClass('text-danger');
                return false;
            }
            $("#container-espacios").empty();
            jQuery('<canvas id="chartEspacios"></canvas>').appendTo("#container-espacios");
            let nombres = response.map(item => item.nombre);
            let totalReservaciones = response.map(item => item.total_reservaciones);
            let ctx = document.getElementById('chartEspacios').getContext('2d');
            let chartEspacios = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Total Reservaciones',
                        data: totalReservaciones,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
    
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsEspacios();
    // mostrar en el contenedor de espacios
    async function cardsClientes() {
        try {
            let peticion = await fetch(servidor + `admin/containerClientes`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay clientes</h3>`)
                    .appendTo("#container-clientes")
                    .addClass('text-danger');
                return false;
            }
            $("#container-clientes").empty();
            let card = `
                <div class="card p-3" style="background-color: #1b1b1b;">
                    <div class="card-title">
                        <p class="fw-bolder" style="color: #fff; font-size: 32px;">Ventas Totales</p>
                    </div>
                    <div class="card-body">
                        <p class="fw-bold" style="color: #ff0000; font-size: 22px;">${response.numero_total}</p>
                    </div>
                </div>
            `;
            $("#container-clientes").append(card);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsClientes();
    // mostrar en el contenedor clientes
    async function cardsClientesSinAsignar() {
        try {
            let peticion = await fetch(servidor + `admin/containerClientesSinAsignar`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay clientes sin asignar</h3>`)
                    .appendTo("#container-clientes-sin-asignar")
                    .addClass('text-danger');
                return false;
            }
            $("#container-clientes-sin-asignar").empty();
            jQuery('<canvas id="chartClientes"></canvas>').appendTo("#container-clientes-sin-asignar");
            let nombres = response.map(item => `${item.nombre_usuario} ${item.apellido_paterno} ${item.apellido_materno}`);
            let espacios = response.map(item => item.nombre_espacio);
            let ctx = document.getElementById('chartClientes').getContext('2d');
            let chartClientes = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Espacios no asignados',
                        data: espacios.map(() => 1),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsClientesSinAsignar();
});