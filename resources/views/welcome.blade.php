@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <!-- Card superior -->
        <div class="row mb-4">
            <div class="col-12 mx-auto">
                <div class="card p-4 shadow">
                    <h1 class="card-title text-center">Acortador de URL</h1>

                    <form id="urlForm" >
                        <div class="mb-3">
                            <label for="original_url" class="form-label fw-bold fs-5 text-center d-block">Ingresa tu URL</label>
                            <div class="input-group">
                                <input type="url" class="form-control form-control-lg" id="original_url" name="original_url"
                                    required placeholder="https://ejemplo.com">
                                <button type="submit" class="btn btn-lg btn-primary-custom" aria-label="Acortar URL">Acortar</button>
                            </div>
                        </div>

                        <div class="mb-3 text-center">
                            <div class="g-recaptcha d-inline-block" data-sitekey="6Le8rX0rAAAAAEZ-r7xIsj7pTlOsAETMtvfby5Qu"></div>
                        </div>

                    </form>
                    <div id="resultado" class="mt-3 text-center text-success" style="display:none;"></div>

                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                const captchaToken = grecaptcha.getResponse();

                if (!captchaToken) {
                    alert("Por favor completa el reCAPTCHA.");
                    return;
                }

                const headers = {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                };

                const payload = {
                    original_url: url,
                    'g-recaptcha-response': captchaToken
                };

                let res;

                if (token) {
                    headers["Authorization"] = "Bearer " + token;
                    res = await fetch("{{ route('url.short.api') }}", {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(payload),
                    });
                } else {
                    headers["X-CSRF-TOKEN"] = '{{ csrf_token() }}';
                    res = await fetch("{{ route('url.short') }}", {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(payload),
                    });
                }

                const data = await res.json();

                if (!res.ok) {
                    alert(data.error || "Ocurrió un error con el captcha o la URL.");
                    return;
                }

                // Mostrar resultado
                document.getElementById('resultado').innerHTML = `
                <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
                    <div>
                    Tu URL acortada es: <a href="${data.short_url}" target="_blank">${data.short_url}</a>
                    </div>
                    <button id="copyBtn" class="btn btn-sm btn-outline-success">Copiar</button>
                </div>
                `;
                document.getElementById('resultado').style.display = 'block';

                document.getElementById('copyBtn').addEventListener('click', () => {
                    navigator.clipboard.writeText(data.short_url).then(() => {
                        alert('URL copiada al portapapeles');
                    });
                });


                grecaptcha.reset(); // 🔁 reinicia el captcha para otro intento

            } catch (error) {
                console.error('Ocurrió un error:', error);
            }
        });
    </script>
@endpush