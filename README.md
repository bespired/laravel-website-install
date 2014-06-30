OSX php shell script to install Laravel and stuff

use:
php install.php 'websitename' [-force] [-mamp]

creates a laravel install
with /libraries and /assets in /app

It adds npm, composer, Grunt and Bower etc.
grunt-contrib-copy, grunt-contrib-sass, grunt-contrib-uglify, grunt-contrib-watch
jquery, font-awesome, foundation an stuff.

If the -mamp flag is set it also changes the dev env
return isset($_SERVER['LARAVEL_ENV']) ? $_SERVER['LARAVEL_ENV'] : 'production';
in bootstrap/start.php
And adds SetEnv LARAVEL dev_YOURMACHINE
in MAMP httpd.conf.
