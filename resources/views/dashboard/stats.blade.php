@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Url del usuario -->
        <div class="col-md-7 mb-4">
            <div class="card p-4 shadow">
                <h2 class="card-title text-center">URLs más visitadas</h2>
                {{-- <ul id="urlList" class="list-group"></ul> --}}
                <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px" tabindex="0" aria-controls="kt_table_users"
                                        aria-label="Urls">
                                        Urls
                                    </th>
                                    <th class="min-w-125px" tabindex="0" aria-controls="kt_table_users"
                                        aria-label="Estatsus">
                                        Estatus
                                    </th>
                                    <th class="min-w-125px" tabindex="0" aria-label="Vistas">
                                        Vistas
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="urlTableBody" class="text-gray-600 fw-semibold">
                                <tr>
                                    <td valign="top" colspan="3" class="text-center dataTables_empty">
                                        Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- estadisticas --}}
        <div class="col-md-5 mb-4">
            <div class="card p-4 shadow">
                <h3 class="card-title text-center">Estadísticas</h3>
                <p>Selecciona una url para ver sus estadísticas</p>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        //funcion para fetch
        async function secureFetch(url, options = {}) {
            const token = localStorage.getItem("token");

            if (token) {
                options.headers = {
                    ...options.headers,
                    "Authorization": `Bearer ${token}`,
                    "Accept": "application/json"
                };
            }

            const res = await fetch(url, options);

            if (res.status === 401) {
                // El token expiró o no es válido
                localStorage.removeItem("token");
                alert("Tu sesión ha expirado. Por favor inicia sesión de nuevo.");
                window.location.href = "/login"; // Redirige al login
                return;
            }

            return res;
        }

        //grafica
        let myChart;

        function updateChart(labels, data, code) {
            const ctx = document.getElementById('myChart').getContext("2d");

            if (myChart) {
                myChart.data.datasets[0].data = data;
                myChart.options.plugins.title.text = `Visitas de ${code}`;
                myChart.update();
            } else {
                myChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Visitas por mes',
                            data: data,
                            backgroundColor: 'rgba(182,122,184,0.3)',
                            borderColor: 'rgb(182,122,184)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: `Visitas de ${code}`,
                                font: {
                                    size: 18
                                }
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        //seleccionar url para grafica
        const tabla = document.getElementById('kt_table_users');
        if (tabla) {
            console.log('entro');
            tabla.addEventListener('click', async function (e) {
                const row = e.target.closest('tr');
                if (!row) return;

                const code = row.getAttribute('data-code');
                if (!code) return;

                try {
                    const res = await secureFetch(`/api/url/${code}/visits-per-month`);

                    const data = await res.json();

                    // Formatear datos para la gráfica
                    const labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    const values = data.map(item => item.total);

                    // Actualizar la gráfica
                    updateChart(labels, values, code);
                } catch (error) {
                    console.error("Error al obtener visitas:", error);
                }
            });
        }

        //traer urls
        async function cargarUrls() {

            const tableBody = document.getElementById("urlTableBody");
            tableBody.innerHTML = ""; // Limpiar antes de cargar

            const res = await secureFetch("{{ route('url.by.rating') }}", {
                method: "GET",
                Accept: "application/json"
            });
            const urls = await res.json();


            if (urls.length === 0) {
                tableBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center">No se han encontrado registros</td>
                            </tr>
                        `;
                return;
            }

            urls.forEach((url, index) => {
                const estadoBadge = url.is_active ?
                    `<span class="badge badge-success-soft">Activo</span>` :
                    `<span class="badge badge-danger-soft">Desactivo</span>`;

                const row = document.createElement("tr");
                row.setAttribute('data-code', url.short_code);

                row.innerHTML = `
                            <td>
                                <span class="badge badge-source">${url.domain}</span><br>
                                <a href="${url.short_url}" target="_blank" >${url.short_url}</a>
                            </td>
                            <td>${estadoBadge}</td>
                            <td>${url.visits}</td>
                        `;

                tableBody.appendChild(row);
            });

        }

        // Cargar al iniciar
        cargarUrls();
    </script>
@endpush