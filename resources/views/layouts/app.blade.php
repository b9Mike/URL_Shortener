<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
    @stack('styles')
  
</head>
<body class="bg-light">

    @include('partials.navbar')

    <main class="container my-5">
        @yield('content')
    </main>

    <script>
        //botones cerrar sesion
        document.addEventListener("DOMContentLoaded", () => {
            const token = localStorage.getItem("token");

            if (token) {
                document.getElementById("btn-login").classList.add("d-none");
                document.getElementById("btn-register").classList.add("d-none");
                document.getElementById("btn-logout").classList.remove("d-none");
                document.getElementById("btn-my-urls").classList.remove("d-none");
            }

            document.getElementById("btn-logout").addEventListener("click", async () => {
                try {
                    await secureFetch("/api/logout", {
                        method: "POST"
                    });

                    localStorage.removeItem("token");
                    location.reload(); // o redirige

                } catch (e) {
                    alert("Error cerrando sesión");
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
