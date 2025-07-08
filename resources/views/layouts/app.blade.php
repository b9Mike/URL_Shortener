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

         .btn-primary-custom {
            background-color: #7b80d6;
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: #5a5ed1;
        }

        .btn-secondary-custom {
            background-color: #aaaed9;
            color: white;
            border: none;
        }

        .btn-secondary-custom:hover {
            background-color: #8f93c6;
        }

        .btn-register {
            background-color: #d97bb3;
            color: white;
            border: none;
        }

        .btn-register:hover {
            background: linear-gradient(to right, #c5acc5, #b0b5e3);
        }

        .badge-success-soft {
            background-color: #6cc9a1;
            color: white;
        }

        .badge-danger-soft {
            background-color: #e96c6c;
            color: white;
        }

        .badge-private {
            background-color: #666;
            color: white;
        }

        a {
            color: #6f75d9;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        a:hover {
            color: #4f54c3;
            text-decoration: underline;
        }
        .badge-source {
            background-color: #dad9f3;
            color: #4b4a6a;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 4px;
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
