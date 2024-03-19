# Espera hasta que la base de datos esté disponible

while ! nc -z $DB_HOST $DB_PORT; do
  echo "Esperando a que la base de datos esté disponible en $DB_HOST:$DB_PORT ..."
  sleep 1
done

# Verifica que se pueda acceder a la base de datos

if mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "USE $DB_DATABASE;" &>/dev/null; then
  echo "La base de datos $DB_DATABASE está lista y se puede acceder correctamente."
else
  echo "Error: No se puede acceder a la base de datos $DB_DATABASE en $DB_HOST:$DB_PORT."
  exit 1
fi
