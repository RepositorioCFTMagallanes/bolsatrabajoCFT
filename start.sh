#!/bin/sh
set -e

echo "=== [start] runtime $(date) ==="

# 1) Mostrar si Cloud Run está entregando ENV (sin exponer secretos)
echo "APP_ENV=${APP_ENV:-<null>}"
echo "DB_CONNECTION=${DB_CONNECTION:-<null>}"
echo "DB_HOST=${DB_HOST:-<null>}"
echo "DB_PORT=${DB_PORT:-<null>}"
echo "DB_DATABASE=${DB_DATABASE:-<null>}"
echo "DB_USERNAME=${DB_USERNAME:-<null>}"

# 2) Limpiar caches Laravel (explícito)
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true


# 3) (Opcional por ahora) NO cachear config hasta confirmar que funciona
# php artisan config:cache

# 4) Test rápido de conexión DB (sin imprimir password)
php -r '
$host=getenv("DB_HOST"); $port=getenv("DB_PORT"); $db=getenv("DB_DATABASE");
$user=getenv("DB_USERNAME"); $pass=getenv("DB_PASSWORD");
if(!$host||!$db||!$user){ fwrite(STDOUT,"[dbtest] missing env\n"); exit(0); }
try{
  $dsn="pgsql:host=$host;port=$port;dbname=$db";
  $pdo=new PDO($dsn,$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $n=$pdo->query("select count(*) as c from usuarios")->fetch(PDO::FETCH_ASSOC);
  fwrite(STDOUT,"[dbtest] OK usuarios.count=".$n["c"]."\n");
}catch(Exception $e){
  fwrite(STDOUT,"[dbtest] FAIL ".$e->getMessage()."\n");
}
'

echo "=== [start] launching apache ==="
exec apache2-foreground
