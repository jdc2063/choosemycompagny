echo "RUNNING APP..."
echo "---------"
winpty docker-compose exec app php /var/app/src/app.php || echo "Error while running app..."

echo "---------"
echo 'DONE.'