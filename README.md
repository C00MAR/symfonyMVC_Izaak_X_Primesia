symfony server:start

php bin/console messenger:consume async -vv

sass --watch \assets\styles\app.scss:\assets\styles\app.css

php bin/console doctrine:fixtures:load --no-interaction

admin@example.com
admin123

user@example.com
user123

php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
php bin/phpunit