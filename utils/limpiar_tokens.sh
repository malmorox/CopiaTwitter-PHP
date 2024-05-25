#!/bin/bash

# Credenciales de la base de datos
DB_HOST="localhost"
DB_NOMBRE="practicando"
DB_USUARIO="malmorox"
DB_CONTRA="1234"

# Ejecuccion del comando SQL
mysql -h "$DB_HOST" -u "$DB_USUARIO" -p"$DB_CONTRA" "$DB_NOMBRE" -e "
DELETE FROM tokens
WHERE consumido = TRUE OR fecha_validez < NOW();
"