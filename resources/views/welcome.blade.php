<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        html,
        body {
            background: linear-gradient(to right, #c5acc5, #bab1d2, #b0b5e3);
        }

        .input-url{
            height: 50px;
        }

        
    </style>
</head>

<body class="bg-light">

    <nav class="navbar shadow-sm" style="background-color: #ffffff;">

        <div class="container-fluid">
            <a class="navbar-brand">Navbar</a>
            <div class="d-flex ms-auto">
                <a href="{{ route ('login') }}" id="btn-login" class="btn btn-outline-primary me-2">Iniciar sesión</a>
                <a href="{{ route ('login') }}" id="btn-register" class="btn btn-outline-success">Registrarse</a>
                <a href="#" id="btn-logout" class="btn btn-outline-danger d-none">Cerrar sesión</a>
            </div>

        </div>
    </nav>

    <div class="container my-5">
        <!-- Card superior -->
        <div class="row mb-4">
            <div class="col-12 mx-auto">
                <div class="card p-4 shadow">
                    <h1 class="card-title text-center">Acortador de URL</h1>

                    <form action="" method="" id="urlForm">
                        <div class="mb-3">
                            <label for="original_url" class="form-label">Ingresa tu URL</label>
                            <input type="url" class="form-control input-url" id="original_url" name="original_url" required
                                placeholder="https://ejemplo.com">
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #b67ab8; color: white;">Acortar</button>
                    </form>

                    <div id="resultado" class="mt-3 text-center text-success" style="display:none;"></div>
                </div>
            </div>
        </div>

        <!-- Fila con dos cards -->
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
                                            aria-label="Estatsus">
                                            Estatus
                                        </th>
                                        <th class="min-w-125px" tabindex="0" aria-label="Vistas">
                                            Vistas
                                        </th>
                                        <th class="text-end pe-3 min-w-100px" aria-label="Acciones">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="urlTableBody" class="text-gray-600 fw-semibold">
                                    <tr>
                                        <td valign="top" colspan="4" class="text-center dataTables_empty">
                                            Cargando...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- estaduisticas --}}
            <div class="col-md-5 mb-4">
                <div class="card p-4 shadow">
                    <h5 class="card-title text-center">Estadísticas</h5>
                    <p>Aquí podria mostrar visitas totales, clics, etc.</p>
                    {{-- begin::Menu --}}
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Acciones
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Reactivar</a></li>
                        </ul>
                    </div>
                    {{-- end::Menu --}}
                </div>
            </div>
        </div>
    </div>



    <script>
        //botones cerrar sesion
        document.addEventListener("DOMContentLoaded", () => {
            const token = localStorage.getItem("token");

            if (token) {
                document.getElementById("btn-login").classList.add("d-none");
                document.getElementById("btn-register").classList.add("d-none");
                document.getElementById("btn-logout").classList.remove("d-none");
            }

            document.getElementById("btn-logout").addEventListener("click", async () => {
                try {
                    await fetch("/api/logout", {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Accept": "application/json"
                        }
                    });
                    localStorage.removeItem("token");
                    location.reload(); // o redirige
                } catch (e) {
                    alert("Error cerrando sesión");
                }
            });
        });
        // llamada post para generar la url
        document.getElementById('urlForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const token = localStorage.getItem("token");
            const url = document.getElementById('original_url').value;

            try {
                const headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                };

                let res;
                // Si hay token JWT, usa Authorization y omite CSRF
                if (token) {
                    headers["Authorization"] = "Bearer " + token;
                    res = await fetch('{{ route('url.short.api') }}', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            original_url: url
                        }),
                    });
                } else {
                    // Si no hay token, la sesión es web y necesita CSRF
                    headers["X-CSRF-TOKEN"] = '{{ csrf_token() }}';

                    res = await fetch('{{ route('url.short') }}', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            original_url: url
                        }),
                    });
                }

                const data = await res.json();

                document.getElementById('resultado').style.display = 'block';
                document.getElementById('resultado').innerHTML =
                    `Tu URL acortada es: <a href="${data.short_url}" target="_blank">${data.short_url}</a>`;

            } catch (error) {
                console.error('Ocurrió un error:', error);
            }
        });

        //traer urls
        async function cargarUrls() {
            const token = localStorage.getItem("token");

            const tableBody = document.getElementById("urlTableBody");
            tableBody.innerHTML = ""; // Limpiar antes de cargar

            if (token) {
                const res = await fetch("{{ route('url.urls.user') }}", {
                    method: "GET",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Accept": "application/json",
                    },
                });
                const urls = await res.json();

                if (urls.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">No se han encontrado registros</td>
                        </tr>
                    `;
                    return;
                }

                urls.forEach((url, index) => {
                    const estadoBadge = url.is_active ?
                        `<span class="badge bg-success">Activo</span>` :
                        `<span class="badge bg-danger">Desactivado</span>`;

                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>
                            <a href="${url.short_url}" target="_blank">${url.short_url}</a>
                        </td>
                        <td>${estadoBadge}</td>
                        <td>${url.visits}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenu${index}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Acciones
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu${index}">
                                    <li><a class="dropdown-item reactivar-btn" href="#" data-code="${url.short_code}">Reactivar</a></li>
                                    <li><a class="dropdown-item deactivate-btn" href="#" data-code="${url.short_code}">Desactivar</a></li>
                                </ul>
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            }else{
                tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">Inicia sesión para ver tus urls</td>
                        </tr>
                    `;
                    return;
            }
            
        }

        //reactivar o desactivar url
        document.getElementById('urlTableBody').addEventListener('click', function(e) {
            const token = localStorage.getItem("token");
            if (e.target.classList.contains('reactivar-btn')) {
                e.preventDefault();
                const code = e.target.getAttribute('data-code');

                fetch(`api/reactivate-url/${code}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            "Authorization": `Bearer ${token}`
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error("Error al reactivar la URL");
                        return res.json();
                    })
                    .then(data => {
                        alert("URL reactivada con éxito");
                        cargarUrls();
                    })
                    .catch(err => {
                        alert("Hubo un error: " + err.message);
                    });
            } else if (e.target.classList.contains("deactivate-btn")) {
                e.preventDefault();
                const code = e.target.getAttribute("data-code");

                fetch(`api/deactivate-url/${code}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            "Authorization": `Bearer ${token}`
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error("Error al desactivar la URL");
                        return res.json();
                    })
                    .then(data => {
                        alert("URL desactivada con éxito");
                        cargarUrls();
                    })
                    .catch(err => {
                        alert("Hubo un error: " + err.message);
                    });
            }
        });

        
        // Cargar al iniciar
        cargarUrls();
    </script>

</body>

</html>
