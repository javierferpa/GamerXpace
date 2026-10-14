# Alojamiento de GamerXpace

La aplicación se conserva en PHP y MySQL, con sus rutas originales bajo `/tfg/`. GitHub Pages no puede ejecutar esta aplicación sin adaptarla. El destino elegido es alwaysdata Free: PHP, MariaDB compatible con MySQL, HTTPS y un subdominio `alwaysdata.net`, independiente de ChatGPT.

La dirección publicada es **https://gamerxpace.alwaysdata.net/**. La configuración aplicada y la verificación están documentadas en [DEPLOYMENT.md](DEPLOYMENT.md).

## Conservación del código

Los 49 archivos de `TFG/` se conservan byte a byte respecto al commit `a29de7b3fd0c3710d7a0eb28aaae58660f659683`. `source-manifest.json` registra los hashes SHA-256 del contenido original en Git; el empaquetado se detiene si alguno cambia. Usa los objetos de Git para que los filtros de saltos de línea de Windows no alteren la copia publicada y rechaza cambios locales en `TFG/`.

En la copia para el servidor, `conexion.php` conserva su clase de base de datos y cambia únicamente los valores de conexión para leerlos de un archivo privado. La lógica, los juegos, los formularios, el HTML, el CSS y JavaScript originales se mantienen.

Las URL antiguas se sustituyen en las respuestas HTML mediante `mod_substitute` de Apache. Se conserva el prefijo `/tfg/` para no cambiar enlaces ni llamadas AJAX. El módulo debe estar habilitado en el alojamiento.

En alwaysdata, el módulo está disponible pero no se carga por defecto. En **Web → Configuration → Apache → Apache global directives** se aplica:

    LoadModule substitute_module /usr/alwaysdata/apache/latest/modules/mod_substitute.so

Esta configuración del servidor evita modificar los enlaces dentro del código. Las instrucciones del proveedor están en [configuración de Apache](https://help.alwaysdata.com/en/docs/web-hosting/sites/http-servers/configure-apache/).

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
5. Configura un sitio PHP 8.4 con raíz `public/`, **Force HTTPS** y `mod_substitute`. Aplica `php.ini` en sus parámetros PHP. El despliegue usa el perfil WAF **Full**, sin exclusiones; comprueba los formularios y AJAX al activarlo.
6. Comprueba inicio, registro/login con una cuenta de prueba, foro, perfiles, ambos juegos y persistencia de puntuaciones. Verifica los enlaces de rankings y los archivos privados rechazados por HTTP.
7. Publica el enlace definitivo en el README y conserva una copia privada de recuperación. El plan Free anuncia tres días de copias del proveedor; no sustituye una copia propia.

Para futuras publicaciones conserva versiones separadas y cambia la raíz del sitio solamente después de validar la nueva. No reimportes la base de datos en una actualización de archivos. Volver a un paquete anterior no revierte los datos: cualquier restauración de base requiere conservar y revisar el estado actual.

## Condiciones del plan gratuito

La cuenta `gamerxpace` tiene una suscripción **Free de 0 €**, con 1 GB de disco, 256 MB de RAM y 0,25 CPU. No se ha contratado un plan de pago. La aplicación y la base de datos se ejecutan en alwaysdata y no dependen de una sesión de Codex ni de una suscripción de ChatGPT.

alwaysdata exige actividad del propietario en su panel: las cuentas de menos de un año deben iniciar sesión cada 120 días; entre uno y cuatro años, cada seis meses; después, una vez al año. El proveedor puede suspender una cuenta inactiva y la conexión al panel permite reactivarla. Consulta su [política de inactividad](https://help.alwaysdata.com/en/docs/admin-billing/profile/suspension/).

## Contenido de ejemplo

`demo-data.json` contiene exclusivamente datos inventados: cinco perfiles con avatares y puntuaciones, cinco hilos sobre los juegos, 22 mensajes y 45 likes. Los comentarios, fechas y marcas personales se corresponden entre sí. El contenido se añadió a petición del propietario; no representa tráfico real.

`seed-demo.php` es una utilidad de consola, fuera del código de `TFG/`. Se ejecuta con PHP y una configuración privada:

    php hosting/seed-demo.php --database-config=/RUTA/PRIVADA/database.php --data=hosting/demo-data.json --receipt=/RUTA/PRIVADA/demo-seed.json

El importador usa una transacción, consultas preparadas y un bloqueo de ejecución. Rechaza nombres existentes y conserva los registros previos. Guarda los identificadores y hashes en un comprobante privado; una ejecución posterior verifica el lote ya presente y evita duplicarlo. Si el comprobante y la base difieren, se detiene para revisar el resultado antes de cualquier reintento.

Las cuentas de ejemplo tienen hashes generados a partir de valores aleatorios que no se conservan. No existe una contraseña compartida ni se publican credenciales para iniciar sesión con ellas. Para utilizar la web, crea tu propia cuenta desde el registro habitual.

## Alcance

Esta migración no corrige ni moderniza el código original. Mantiene, entre otras cosas, su autenticación MD5 y sus consultas SQL construidas con entradas del usuario. La publicación no acredita una auditoría de seguridad de la aplicación. Usa credenciales exclusivas para el TFG; cambiar estas limitaciones exigiría una autorización distinta para modificar el código.

El perfil WAF Full añade filtros de SQL injection, XSS e inclusión de archivos, pero no reemplaza la corrección del código ni resuelve todas sus limitaciones. Véanse los [perfiles WAF del proveedor](https://help.alwaysdata.com/fr/docs/hebergement-web/sites/personnalisation/waf/).

Condiciones del alojamiento consultadas el 14 de octubre de 2026: [oferta Free](https://www.alwaysdata.com/en/offers/), [PHP](https://help.alwaysdata.com/en/docs/web-hosting/languages/php/configuration/), [SSH](https://help.alwaysdata.com/en/docs/web-hosting/remote-access/ssh/). El coste del plan es 0 € con sus límites vigentes; las condiciones del proveedor pueden cambiar.
