# Proyecto_final_programci-n_ms-auth

## Configuración

`public/index.php` carga la configuración de base de datos y CORS para todas las rutas.
La base de datos usa MySQL con estos valores predeterminados: host `127.0.0.1`, puerto
`3306`, base `mi_base`, usuario `root` y contraseña vacía. Se pueden reemplazar con
las variables de entorno `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y
`DB_PASSWORD`. MySQL debe estar iniciado y la base indicada debe existir.

Por defecto, CORS permite cualquier origen sin credenciales. Para habilitar credenciales,
define `CORS_ALLOWED_ORIGINS` con una lista separada por comas de orígenes permitidos,
por ejemplo `http://localhost:3000,http://localhost:5173`. Las solicitudes `OPTIONS`
se responden directamente con estado `204`.