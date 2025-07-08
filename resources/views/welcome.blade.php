@extends('layouts.app')

@section('content')
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
                        <button type="submit" class="btn w-100 btn-primary-custom">Acortar</button>
                    </form>

                    <div id="resultado" class="mt-3 text-center text-success" style="display:none;"></div>
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
        // llamada post para generar la url
        document.getElementById('urlForm').addEventListener('submit', async function (e) {
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
                    res = await fetch("{{ route('url.short.api') }}", {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            original_url: url
                        }),
                    });
                } else {
                    // Si no hay token, la sesión es web y necesita CSRF
                    headers["X-CSRF-TOKEN"] = '{{ csrf_token() }}';

                    res = await fetch("{{ route('url.short') }}", {
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
    </script>
@endpush