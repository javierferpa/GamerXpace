# Alojamiento de GamerXpace

La aplicación se conserva en PHP y MySQL, con sus rutas originales bajo `/tfg/`. GitHub Pages no puede ejecutar esta aplicación sin adaptarla. El destino elegido es alwaysdata Free: PHP, MariaDB compatible con MySQL, HTTPS y un subdominio `alwaysdata.net`, independiente de ChatGPT.

## Conservación del código

Los 49 archivos de `TFG/` se conservan byte a byte respecto al commit `a29de7b3fd0c3710d7a0eb28aaae58660f659683`. `source-manifest.json` registra los hashes SHA-256 del contenido original en Git; el empaquetado se detiene si alguno cambia. Usa los objetos de Git para que los filtros de saltos de línea de Windows no alteren la copia publicada y rechaza cambios locales en `TFG/`.

En la copia para el servidor, `conexion.php` conserva su clase de base de datos y cambia únicamente los valores de conexión para leerlos de un archivo privado. La lógica, los juegos, los formularios, el HTML, el CSS y JavaScript originales se mantienen.

Las URL antiguas se sustituyen en las respuestas HTML mediante `mod_substitute` de Apache. Se conserva el prefijo `/tfg/` para no cambiar enlaces ni llamadas AJAX. El módulo debe estar habilitado en el alojamiento.

## Paquete

Se necesita Node 24. No hay dependencias adicionales:

    node hosting/package.mjs --verify-only
    node hosting/package.mjs --origin https://CUENTA.alwaysdata.net --data full

El comando crea una carpeta nueva en `.hosting/`, ignorada por Git. No sustituye ni elimina archivos fuente o paquetes anteriores. `--data full` conserva la copia SQL original del 17 de junio de 2021, autorizada para esta migración. `--data empty` prepara solamente su estructura. Esta copia histórica no acredita el estado del alojamiento antiguo al cerrar.

Para incluir la configuración privada del servidor:

    node hosting/package.mjs --origin https://CUENTA.alwaysdata.net --data full --database-config RUTA_FUERA_DE_GIT

El archivo JSON privado contiene `host`, `port`, `user`, `password` y `database`. No pases contraseñas como argumentos ni las guardes en el repositorio. El paquete resultante contiene:

- `public/tfg/`: 44 archivos de la aplicación y sus recursos.
- `public/.htaccess`: rutas, protección de archivos y sustitución de las URL del alojamiento anterior.
- `public/.user.ini`: configuración de PHP; aplícala también en los ajustes del sitio de alwaysdata.
- `private/database.php`: conexión, con permisos 600, fuera del directorio público. Base64 es una codificación, no cifrado.
- `private/database.sql`: copia de la base de datos, fuera del directorio público.
- `manifest.json`: procedencia y relación de archivos, sin valores de credenciales.

Los documentos de trabajo, imágenes XCF y el volcado SQL no se publican por HTTP. El archivo de conexión tampoco se puede solicitar directamente. La CI valida los hashes, la sintaxis PHP y un paquete sin secretos ni registros históricos; no despliega ni importa datos automáticamente.

## Publicación

1. Confirma en el panel que la cuenta usa **Free / 0 €/mes**. No actives Plus ni un periodo de prueba de pago.
2. Crea una base de datos y credenciales nuevas para este proyecto. Conserva los nombres de tablas, columnas, cotejamientos y claves de la copia original.
3. Sube un paquete completo mediante SSH/SFTP, con el directorio `private/` fuera de la raíz web. Verifica la huella SSH mostrada por el panel antes de conectar.
4. Importa SQL únicamente en la base nueva y vacía. No repitas una importación de resultado desconocido: consulta primero las tablas y sus recuentos.
5. Configura un sitio PHP con raíz `public/`, HTTPS y `mod_substitute`. Aplica `php.ini` en sus parámetros PHP.
6. Comprueba inicio, registro/login con una cuenta de prueba, foro, perfiles, ambos juegos y persistencia de puntuaciones. Verifica los enlaces de rankings y los archivos privados rechazados por HTTP.
7. Publica el enlace definitivo en el README y conserva una copia privada de recuperación. El plan Free anuncia tres días de copias del proveedor; no sustituye una copia propia.

Para futuras publicaciones conserva versiones separadas y cambia la raíz del sitio solamente después de validar la nueva. No reimportes la base de datos en una actualización de archivos. Volver a un paquete anterior no revierte los datos: cualquier restauración de base requiere conservar y revisar el estado actual.

## Alcance

Esta migración no corrige ni moderniza el código original. Mantiene, entre otras cosas, su autenticación MD5 y sus consultas SQL construidas con entradas del usuario. La publicación no acredita una auditoría de seguridad de la aplicación. Usa credenciales exclusivas para el TFG; cambiar estas limitaciones exigiría una autorización distinta para modificar el código.

Condiciones del alojamiento consultadas el 14 de octubre de 2026: [oferta Free](https://www.alwaysdata.com/en/offers/), [PHP](https://help.alwaysdata.com/en/docs/web-hosting/languages/php/configuration/), [SSH](https://help.alwaysdata.com/en/docs/web-hosting/remote-access/ssh/). El coste del plan es 0 € con sus límites vigentes; las condiciones del proveedor pueden cambiar.
