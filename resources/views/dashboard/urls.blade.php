@extends('layouts.app')

@push('styles')

<style>
    .passwordForm{
        width: 80%; max-width: 300px;
    }
</style>

@endpush

@section('content')
    <div class="row">
        <!-- Url del usuario -->
        <div class="col-md-7 mb-4">
            <div class="card p-4 shadow">
                <h5 class="card-title text-center">URLs Existentes</h5>
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
                                        aria-label="Estatus">
                                        Estatus
                                    </th>
                                    <th class="min-w-125px" tabindex="0" aria-label="Vistas">
                                        Vistas
                                    </th>
                                    <th class="min-w-125px" tabindex="0" aria-label="Privacidad">
                                        Privacidad
                                    </th>
                                    <th class="text-end pe-3 min-w-100px" aria-label="Acciones">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="urlTableBody" class="text-gray-600 fw-semibold">
                                <tr>
                                    <td valign="top" colspan="5" class="text-center dataTables_empty">
                                        Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="paginationContainer" class="mt-3 d-flex justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- estadisticas --}}
        <div class="col-md-5 mb-4">
            <div class="card p-4 shadow">
                <h5 class="card-title text-center">Estadísticas</h5>
                <p>Aquí se muestran las visitas de la URL</p>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header justify-content-center">
                    <h5 class="modal-title text-center w-100" id="exampleModalLabel">Generar contraseña para tu URL</h5>
                    <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body d-flex justify-content-center">
                    <form id="passwordForm" >
                        <div class="mb-3 text-center">
                            <label for="password" class="form-label">Ingresa una contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 text-center">
                            <label for="confirm-password" class="form-label">Confirma la contraseña</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-modal-save">Guardar cambios</button>
                </div>

            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        let currentCodeForPassword = null;

        const token = localStorage.getItem("token");
        if(!token)
            window.location.href = "/login";

        
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


        function mostrarPaginacion(data) {
            const container = document.getElementById("paginationContainer");
            container.innerHTML = "";

            if (!data.links) return;

            data.links.forEach(link => {
                const button = document.createElement("button");
                button.classList.add("btn", "btn-sm", "mx-1");
                button.innerHTML = link.label.replace("&laquo;", "«").replace("&raquo;", "»");

                if (link.active) button.classList.add("btn-primary");
                else button.classList.add("btn-outline-primary");

                button.disabled = !link.url;

                button.addEventListener("click", () => {
                    if (link.url) cargarUrls(link.url);
                });

                container.appendChild(button);
            });
        }

        //traer urls
        async function cargarUrls(pageUrl = "{{ route('url.urls.user') }}") {
            const token = localStorage.getItem("token");
            const tableBody = document.getElementById("urlTableBody");
            tableBody.innerHTML = "";

            if (!token) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">Inicia sesión para ver tus urls</td>
                    </tr>`;
                return;
            }

            const res = await secureFetch(pageUrl, { method: "GET" });
            const json = await res.json();
            const urls = json.data;

            if (urls.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">No se han encontrado registros</td>
                    </tr>`;
                return;
            }

            urls.forEach((url, index) => {
                const estadoBadge = url.is_active
                    ? `<span class="badge bg-success">Activo</span>`
                    : `<span class="badge bg-danger">Desactivado</span>`;

                const privacyBadge = url.is_public
                    ? `<span class="badge bg-success">Público</span>`
                    : `<span class="badge bg-danger">Privado</span>`;

                let passwordBadge;
                if (!url.password) {
                    passwordBadge = `
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal" href="#" data-action="set-password" data-code="${url.short_code}">Establecer contraseña</a></li>`;
                } else {
                    passwordBadge = `
                        <li><a class="dropdown-item" href="#" data-action="remove-password" data-code="${url.short_code}">Quitar contraseña</a></li>
                        <li><a class="dropdown-item" href="#" data-action="view-password" data-code="${url.short_code}">Ver contraseña</a></li>`;
                }

                const row = document.createElement("tr");
                row.dataset.code = url.short_code;
                row.innerHTML = `
                    <td><a href="${url.short_url}" target="_blank">${url.short_url}</a></td>
                    <td>${estadoBadge}</td>
                    <td>${url.visits}</td>
                    <td>${privacyBadge}</td>
                    <td class="text-end">
                        <div class="dropdown position-static">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                Acciones
                            </button>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">Estado</li>
                                <li><a class="dropdown-item reactivar-btn" href="#" data-action="change-state" data-code="${url.short_code}">Activar/Desactivar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">Privacidad</li>
                                <li><a class="dropdown-item" href="#" data-action="change-privacy" data-code="${url.short_code}">Público/Privado</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">Contraseña</li>
                                ${passwordBadge}
                            </ul>
                        </div>
                    </td>`;

                tableBody.appendChild(row);
            });

            // Agregar paginación
            mostrarPaginacion(json);
        }


        // funcion para cambiar privacidad y estado de la url 
        document.getElementById('urlTableBody').addEventListener('click', async (e) => {

            const item = e.target.closest('a.dropdown-item');
            if (!item) return;

            const action = item.dataset.action;
            const code   = item.dataset.code;

            if (!action) return;

            e.preventDefault();                 // evita navegación en el menú

            try {
                let res, msg;
                switch (action) {

                    case 'change-privacy':
                        res = await secureFetch(`api/change-privacy-url/${code}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        msg = "Cambio de privacidad correctamente.";
                        break;
                        
                    case 'change-state':
                        res = await secureFetch(`api/change-state-url/${code}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        msg = "Cambio de estado correctamente.";
                        break;
                    
                    case 'remove-password':
                        res = await secureFetch(`api/remove-url-password/${code}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        msg = "Contraseña removida correctamente.";
                        break;
                    case 'set-password': 
                        currentCodeForPassword = code;
                        return;
                        break;
                    default:
                        return;
                }

                if (!res.ok) throw new Error("Error en la operación");

                const data = await res.json();
                alert(msg);
                cargarUrls();

            } catch (err) {
                alert('Error: ' + err.message);
            }
        });

        // funcion para establecer contraseña 
        document.getElementById("btn-modal-save").addEventListener("click", async (e) => {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const password2 = document.getElementById('confirm-password').value;

            if (!currentCodeForPassword) {
                alert("No se ha seleccionado una URL válida.");
                return;
            }

            try {
                const res = await secureFetch(`api/set-url-password/${currentCodeForPassword}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        password: password,
                        password_confirmation: password2
                    }),
                });

                if (!res.ok) throw new Error("Error al establecer la contraseña");

                const data = await res.json();
                alert("Contraseña establecida correctamente.");
                location.reload();
                currentCodeForPassword = null;
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });



        // Cargar al iniciar
        cargarUrls();
    </script>
@endpush