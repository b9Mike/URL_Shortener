<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Login and Registration Form in HTML | CodingNepal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: linear-gradient(to right, #c5acc5, #bab1d2, #b0b5e3);

        }

        ::selection {
            background: #5e42fa;
            color: #fff;
        }

        .wrapper {
            overflow: hidden;
            max-width: 390px;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
        }

        .wrapper .title-text {
            display: flex;
            width: 200%;
        }

        .wrapper .title {
            width: 50%;
            font-size: 35px;
            font-weight: 600;
            text-align: center;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .wrapper .slide-controls {
            position: relative;
            display: flex;
            height: 50px;
            width: 100%;
            overflow: hidden;
            margin: 30px 0 10px 0;
            justify-content: space-between;
            border: 1px solid lightgrey;
            border-radius: 5px;
        }

        .slide-controls .slide {
            height: 100%;
            width: 100%;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            line-height: 48px;
            cursor: pointer;
            z-index: 1;
            transition: all 0.6s ease;
        }

        .slide-controls label.signup {
            color: #000;
        }

        .slide-controls .slider-tab {
            position: absolute;
            height: 100%;
            width: 50%;
            left: 0;
            z-index: 0;
            border-radius: 5px;
            background: linear-gradient(to right, #c5acc5, #b0b5e3);
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        input[type="radio"] {
            display: none;
        }

        #signup:checked~.slider-tab {
            left: 50%;
        }

        #signup:checked~label.signup {
            color: #fff;
            cursor: default;
            user-select: none;
        }

        #signup:checked~label.login {
            color: #000;
        }

        #login:checked~label.signup {
            color: #000;
        }

        #login:checked~label.login {
            cursor: default;
            user-select: none;
        }

        .wrapper .form-container {
            width: 100%;
            overflow: hidden;
        }

        .form-container .form-inner {
            display: flex;
            width: 200%;
        }

        .form-container .form-inner form {
            width: 50%;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .form-inner form .field {
            height: 50px;
            width: 100%;
            margin-top: 20px;
        }

        .form-inner form .field input {
            height: 100%;
            width: 100%;
            outline: none;
            padding-left: 15px;
            border-radius: 5px;
            border: 1px solid lightgrey;
            border-bottom-width: 2px;
            font-size: 17px;
            transition: all 0.3s ease;
        }

        .form-inner form .field input:focus {
            border-color: #0f2027;
            /* box-shadow: inset 0 0 3px #fb6aae; */
        }

        .form-inner form .field input::placeholder {
            color: #999;
            transition: all 0.3s ease;
        }

        form .field input:focus::placeholder {
            color: #b3b3b3;
        }

        .form-inner form .pass-link {
            margin-top: 5px;
        }

        .form-inner form .signup-link {
            text-align: center;
            margin-top: 30px;
        }

        .form-inner form .pass-link a,
        .form-inner form .signup-link a {
            color: #ce92dd;
            text-decoration: none;
        }

        .form-inner form .pass-link a:hover,
        .form-inner form .signup-link a:hover {
            text-decoration: underline;
        }

        form .btn {
            height: 50px;
            width: 100%;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        form .btn .btn-layer {
            height: 100%;
            width: 300%;
            position: absolute;
            left: -100%;
            background: linear-gradient(to right, #c5acc5, #bab1d2, #b0b5e3);
            border-radius: 5px;
            transition: all 0.4s ease;
            ;
        }

        form .btn:hover .btn-layer {
            left: 0;
        }

        form .btn input[type="submit"] {
            height: 100%;
            width: 100%;
            z-index: 1;
            position: relative;
            background: none;
            border: none;
            color: #fff;
            padding-left: 0;
            border-radius: 5px;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="title-text">
            <div class="title login">
                Inicia sesión
            </div>
            <div class="title signup">
                Registrate
            </div>
        </div>
        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Inicia sesión</label>
                <label for="signup" class="slide signup">Registrate</label>
                <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
                <form action="#" class="login">
                    <div class="field">
                        <input id="login-email" type="text" placeholder="Correo electrónico" required>
                    </div>
                    <div class="field">
                        <input id="login-password" type="password" placeholder="Contraseña" required>
                    </div>
                    <div class="pass-link">
                        <a href="#">Bienvenido</a>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input id="btn-login" type="submit" value="Ingresar">
                    </div>
                    <div class="signup-link">
                        ¿No estas registrado? <a href="">Registrate ahora</a>
                    </div>
                </form>
                <form action="#" class="signup">
                    <div class="field">
                        <input type="text" placeholder="Usuario" id="register-user" required>
                    </div>
                    <div class="field">
                        <input type="text" placeholder="Correo electrónico" id="register-email" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Contraseña" id="register-password" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Confirmar Contraseña" id="register-confirm-password"
                            required>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input id="btn-register" type="submit" value="Registrate">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const loginText = document.querySelector(".title-text .login");
        const loginForm = document.querySelector("form.login");
        const loginBtn = document.querySelector("label.login");
        const signupBtn = document.querySelector("label.signup");
        const signupLink = document.querySelector("form .signup-link a");
        signupBtn.onclick = (() => {
            loginForm.style.marginLeft = "-50%";
            loginText.style.marginLeft = "-50%";
        });
        loginBtn.onclick = (() => {
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
        });
        signupLink.onclick = (() => {
            signupBtn.click();
            return false;
        });



        //registrar
        document.getElementById("btn-register").addEventListener("click", (e) => {
            e.preventDefault();

            const username = document.getElementById('register-user').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const password2 = document.getElementById('register-confirm-password').value;

            fetch(`/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: username,
                        email: email,
                        password: password,
                        password_confirmation: password2
                    }),
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(data => {
                            console.error("Errores de validación o servidor:", data);
                            throw new Error("Error en la solicitud");
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    alert("Usuario registrado exitosamente");
                    window.location.href = "/login";
                })
                .catch(err => {
                    alert("Hubo un error: " + err.message);
                });

        });

        //login
        document.getElementById("btn-login").addEventListener("click", async (e) =>{
            e.preventDefault();

            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            try {
                const response = await fetch("/api/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({
                        email,
                        password,
                    }),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error("Error:", errorData);
                    alert(errorData.message || "Credenciales inválidas");
                    return;
                }

                const data = await response.json();
                console.log("Token recibido:", data.token);

                // Guardar el token en localStorage para futuras peticiones
                localStorage.setItem("token", data.token);

                // Redirigir o mostrar mensaje
                alert("Inicio de sesión exitoso");
                window.location.href = "/"; // Si quieres redirigir

            } catch (error) {
                console.error("Error de red:", error);
                alert("Ocurrió un error al iniciar sesión");
            }

        });


    </script>
</body>

</html>
