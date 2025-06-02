<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex justify-content-center mt-5">
        <div class="row w-100">
            <div class="col-10 col-md-11 mx-auto">
                <div class="card p-4 shadow">
                    <h4 class="card-title text-center">Acortador de URL</h4>

                    <form action="" method="" id="urlForm">
                        <div class="mb-3">
                            <label for="original_url" class="form-label">Ingresa tu URL</label>
                            <input type="url" class="form-control" id="original_url" name="original_url" required placeholder="https://ejemplo.com">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Acortar</button>
                    </form>

                    <div id="resultado" class="mt-3 text-center text-success" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('urlForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const url = document.getElementById('original_url').value;

            try {
                const res = await fetch('{{route('url.short')}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ original_url: url }),
                });

                const data = await res.json();
                document.getElementById('resultado').style.display = 'block';
                document.getElementById('resultado').innerHTML = `Tu URL acortada es: <a href="${data.short_url}" target="_blank">${data.short_url}</a>`;
            } catch (error) {
                console.error('Ocurrió un error:', error);
                
            }

        });
    </script>

</body>
</html>