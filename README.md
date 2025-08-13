# 🔗 Acortador de URLs

Un sistema para acortar enlaces hecho con **Laravel**, con soporte para autenticación con **JWT**, conteo de visitas y validación **Google reCAPTCHA**.

## ✨ Características
- 🪪 **Autenticación JWT** para usuarios registrados.
- 🔒 **Protección reCAPTCHA** contra bots.
- 📊 **Conteo de visitas** para cada enlace.
- 📁 **Rutas públicas y privadas**.
- 📋 **Botón de copiar** la URL acortada.
- 🎯 **API REST** para consumir desde cualquier cliente.

---

## 🚀 Instalación
1. Clona este repositorio:
   ```bash
   git clone https://github.com/b9Mike/URL_Shortener.git
   cd URL_Shortener

2. Instala las dependencias de Laravel:
    ```bash
    composer install

3. Copia el archivo .env.example y renómbralo a .env:
    ```bash
    cp .env.example .env

4. Configura tu base de datos en .env:
    ```bash
    DB_DATABASE=nombre_base
    DB_USERNAME=usuario
    DB_PASSWORD=contraseña

5. Agrega tus claves de Google reCAPTCHA:
    ```bash
    RECAPTCHA_SITE_KEY=tu_clave_publica
    RECAPTCHA_SECRET=tu_clave_secreta

6. Genera la clave de la aplicación:
    ```bash
    php artisan key:generate

7. Ejecuta las migraciones:
    ```bash
    php artisan migrate

8. Levanta el servidor:
    ```bash
    php artisan serve


## 🛠 Uso
Público: cualquier usuario puede acortar enlaces, validando con reCAPTCHA.

Privado: usuarios autenticados pueden acortar enlaces y ver estadísticas personales.

Estadísticas: puedes ver cuántas veces se ha visitado una URL.

## 📌 Rutas principales
| Método | Ruta          | Descripción                   |
|--------|--------------|--------------------------------|
| POST   | `/api/shorten` | Acorta una URL (requiere JWT) |
| POST   | `/shorten`     | Acorta una URL (público)      |
| GET    | `/{shortCode}` | Redirige a la URL original    |


## 🤝 Contribuciones
¡Las contribuciones son bienvenidas! Si quieres mejorar este proyecto, por favor abre un pull request.

# 📜 Autor y licencia
Hecho con ❤️ por [b9Mike](https://github.com/b9Mike). Este proyecto está bajo la licencia MIT. Puedes usarlo y modificarlo libremente. 