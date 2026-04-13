# Servicio Meléndez

<p align="center">
    <img src="resources/img/logo.png" alt="Logo Servicio Meléndez" width="200"/>
</p>

## Descripción

Sistema Servicio Meléndez

## Requisitos

1. MySQL
1. Composer (PHP 8.0+)
1. npm (Node.js 12.0+)

## Instalación

1. Clonar este repositorio:

    ```
    $ git clone git@github.com:BetoOrtiz/vales-serviciomelendez.git
    ```

2. Entrar en el directorio del repositorio:

    ``` 
    $ cd vales-serviciomelendez
    ```

3. Instalar dependencias:

    ```
    $ npm install
    $ composer install
    ```

## Configuración

1. Crear una base de datos en MySQL (se recomienda usar una contraseña más fuerte):

    ```
    $ mysql -u root -p
    mysql> CREATE DATABASE vales_serviciomelendez;
    mysql> CREATE USER 'melendez'@'localhost' IDENTIFIED BY 'C0n7r4s3ña!';
    mysql> GRANT ALL ON vales_serviciomelendez.* TO 'melendez'@'localhost';
    mysql> FLUSH PRIVILEGES;
    mysql> QUIT
    ```

2. Configurar variables de entorno:

    1. Copiar el archivo `.env.example` a `.env`
        ```
        $ cp .env.example .env
        ```

    2. Modificar el archivo `.env` con las variables de entorno de la base de datos:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=vales_serviciomelendez
        DB_USERNAME=melendez
        DB_PASSWORD=C0n7r4s3ña!
        ```

    3. Generar la clave de encriptación de la aplicación:

        ```
        $ php artisan key:generate
        ```

    4. Realizar migraciones:

        ```
        $ php artisan migrate:refresh --seed
        ```

3. Correr la aplicación. Esto genera un servidor local (por default en el puerto 8000), así como un watcher para generar automáticamente nuevos archivos públicos `.js` y `.css` al modificar los archivos `.vue`:

    ```
    $ npm run serve
    ```

4. Ir a la página principal e iniciar sesión:

    ```
    http://localhost:8000/
    ```

    - _Usuario:_ `correo@admin.com`
    - _Contraseña:_ `C0n7r4s3ña!`

5. Para producción se recomienda usar un servidor como NGINX, Apache, etc.:

    ```
    $ npm run production
    ```

6. Una vez instalado, ejecute el siguiente comando para correr en segundo plano el demonio de la cola de tareas:

    ```
    $ nohup php artisan queue:work &
    ```

7. Se recomienda crear un servicio para ejecutar el demonio de la cola de tareas (ejemplo para Ubuntu con systemd):

    1. Abrir o crear el archivo `serviciomelendez-queue.service` en `/etc/systemd/system`

        ```
        $ sudo nano /etc/systemd/system/serviciomelendez-queue.service
        ```

    2. Copiar el siguiente contenido:

        ```
        [Unit]
        Description=Servicio Meléndez Queue Worker
        After=network.target

        [Service]
        User=www-data
        Group=www-data
        WorkingDirectory=/var/www/vales-serviciomelendez
        ExecStart=/usr/bin/php /var/www/vales-serviciomelendez/artisan queue:work --sleep=3 --tries=3
        Restart=always

        [Install]
        WantedBy=multi-user.target
        ```

    3. Guardar y cerrar el archivo.

    4. Habilitar el servicio:

        ```
        $ sudo systemctl start serviciomelendez-queue
        $ sudo systemctl enable serviciomelendez-queue
        ```
