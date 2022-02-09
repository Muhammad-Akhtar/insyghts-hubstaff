composer require insyghts/hubstaff

For Lumen

=> inside bootstrap/app.php

Uncomment  below lines
$app->withFacades();

$app->withEloquent();

add following lines
$app->register(\Insyghts\Authentication\AuthenticationServiceProvider::class);
$app->register(\Insyghts\Hubstaff\HubstaffPkgProvider::class);

configure env for database connectivity

php artisan migrate

Test in postman


