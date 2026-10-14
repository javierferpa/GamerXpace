# Despliegue de GamerXpace · 14 de octubre de 2026

- Web: **https://gamerxpace.alwaysdata.net/**, redirigida a `/tfg/index.php`.
- Alojamiento: alwaysdata **Free / 0 €**, uso personal, 1 GB de disco.
- Sitio: `gamerxpace.alwaysdata.net`, PHP 8.4 con actualizaciones de versión menor automáticas.
- Base: `gamerxpace_tfg`, MariaDB 11.8, en `mysql-gamerxpace.alwaysdata.net`.
- Raíz pública: `/home/gamerxpace/gamerxpace-migration/releases/release-g04t6v/public/`.
- Conexión privada: `../private/database.php`, permisos 600 y directorio privado 700, fuera de la raíz pública.
- HTTPS forzado, cookies Secure/HttpOnly/SameSite=Lax, errores PHP ocultos en las respuestas y registrados en el servidor.
- WAF Full, sin exclusiones. `mod_substitute` activado en Apache para sustituir las URL antiguas sin editar el TFG.

## Procedencia

Los 49 archivos originales de `TFG/` mantienen los hashes del commit `a29de7b3fd0c3710d7a0eb28aaae58660f659683`. Los ajustes de despliegue modifican solamente la copia de los datos de conexión, conservando su clase y la lógica de la aplicación. Se verificaron por SHA-256 los 49 archivos subidos del paquete, incluidos recursos y configuración del alojamiento.

Se restauró una vez, en una base nueva y vacía, la copia SQL original del 17 de junio de 2021. La restauración comprobó **8 usuarios, 4 hilos, 24 mensajes, 23 likes y 5 claves foráneas**. El volcado y el comprobante de restauración se conservan en `private/`, sin acceso HTTP.

## Verificación

- Sintaxis de los 16 archivos PHP publicados validada con PHP 8.4.26.
- Inicio, login, registro, foro y ambas pantallas de juego responden correctamente, con enlaces al nuevo dominio.
- Registro, login y cierre de sesión con un usuario ficticio; puntuaciones de ambos juegos verificadas directamente en MariaDB.
- Creación de hilos, mensajes y likes/unlikes verificada por HTTP y mediante la interfaz del navegador.
- Arkanoid y Space Invaders iniciados en el navegador, con controles de teclado y recursos gráficos cargados.
- Configuración, conexión, SQL y archivos ocultos devuelven 403/404 por HTTP.
- HTTP redirige a HTTPS. Los filtros WAF rechazan pruebas inocuas de SQL injection y XSS.
- Las pruebas utilizan únicamente una cuenta ficticia. Tras retirarla, se comprueban los hashes de todos los registros originales para asegurar su conservación.

La verificación confirma la migración; no constituye una auditoría completa de seguridad ni una modernización del TFG.

Se comprobó un fallo visual ya presente en `TFG/perfil.php`: el perfil intercambia los valores mostrados como puntuación total y máxima de Space Invaders. Los datos de la base y los rankings de la pantalla del juego son correctos. Se conserva el código original conforme a la indicación del propietario; corregir esas dos etiquetas requeriría modificar la aplicación.

## Registros de ejemplo

Después de retirar la cuenta temporal y comprobar que los registros originales mantenían sus hashes, se añadieron los ejemplos solicitados por el propietario: **5 perfiles, 5 hilos, 22 mensajes y 45 likes**. Son datos ficticios, con nombres naturales, conversaciones sobre las mecánicas reales, fechas coherentes y puntuaciones máximas inferiores a sus totales acumulados.

El lote se importó de forma transaccional con `seed-demo.php`, sin modificar ninguna fila anterior. El comprobante privado `private/demo-seed.json` permite verificar sus filas y evita una segunda inserción. La base queda con **13 usuarios, 9 hilos, 46 mensajes y 68 likes**. El código y la estructura de las cuatro tablas originales se conservan.

## Mantenimiento

Inicia sesión en el [panel de alwaysdata](https://admin.alwaysdata.com/) al menos cada **120 días durante el primer año** para evitar su suspensión por inactividad. Consulta [las condiciones vigentes](https://help.alwaysdata.com/en/docs/admin-billing/profile/suspension/).

Para actualizar archivos, prepara otra versión con `hosting/package.mjs`, mantén las credenciales fuera de Git, sube el paquete y cambia la raíz del sitio tras verificarlo. **No vuelvas a importar la copia de 2021 sobre una base con actividad nueva.** El proveedor mantiene tres días de copias; descarga también una copia privada de la base antes de modificar o restaurar datos.

Las nuevas credenciales no se incluyen en este documento ni en el repositorio. La copia local temporal usada durante la migración se elimina al finalizar; la configuración privada del servidor permanece para que la aplicación pueda conectar.
