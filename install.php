#!/usr/bin/env php
<?php

	// Where is the configfile repo?
	$repo = "https://raw.githubusercontent.com/bespired/laravel-website-install/master/repository";

	$verbose = "--loglevel error";

	// Do we have the laravel installer?
	$laravel = "/usr/local/bin/laravel";
	if ( !file_exists( $laravel ) ){
		die( "\nYou'll need the laravel installer\nVisit http://laravel.com/docs/quick#installation\nto find the Laravel installer PHAR archive.\n\n");
	}

	// Do we know enough about where to install?
	$force = ''; $mamp  = ''; $admin = '';
	if ( !isset( $argv[1] ))  die( "\nusage :" .$argv[0]. " 'name'\n\n");
	foreach( $argv as $key => $arg )
	{
		if ( $arg == '-force' ) $force = '-force';
		if ( $arg == '-mamp' )  $mamp  = '-mamp';
		if ( $arg == '-admin' ) $admin = '-admin';
	}
	
	$website = trim($argv[1]);

	if ( file_exists( $website ) ){
		if ( $force != "-force" ){
			die( "\n$website exists, use $argv[0] $argv[1] -force for a clean install.\n\n" );
		}
		echo "\nDeleting old instance of $website\n";
		system( "sudo rm -R " . $website );
	}


// INSTALL LARAVEL
	echo "\nInstall Laravel Website $website\n\n";
 	system ( "laravel new " . $website );

 	// fail or succes?
 	if ( !file_exists ( $website . "/artisan" ) ){
 		die('Laravel installation failed?');
 	}

// ADJUST LARAVEL
 	echo "But not just yet.\n\n";
	echo "create extra folders\n";
	$folders = Array(
		"app/assets",
		"app/assets/fonts","app/assets/images","app/assets/javascripts","app/assets/scss","app/assets/js","app/assets/stylesheets",
		"public/fonts", "public/images", "public/javascripts", "public/stylesheets",
		"app/libraries"
	);
	foreach ($folders as $folder) {
		echo $folder. "\n";
		@mkdir( $website."/".$folder );
	}
// SCSS STRUCTURE
	echo "\ndownload and unzip scss setup\n";
	if ( copy( $repo."/scss.zip" , "$website/app/assets/scss/scss.zip" ) ) 	echo "/scss.zip\n";
 	system( "cd $website/app/assets/scss; unzip scss.zip");
	system( "rm $website/app/assets/scss/scss.zip");

// JS Mainfile
	echo "\ndownload and unzip javascript setup\n";
	if ( copy( $repo."/application.zip" , "$website/app/assets/javascripts/unpack.zip" ) ) 	echo "/application.zip\n";
	system( "cd $website/app/assets/javascripts;unzip unpack.zip");
	system( "rm $website/app/assets/javascripts/unpack.zip");

// INSTALL COMPOSER
	echo "\ninstall local composer\n";
 	system( 'cd '.$website. ';sudo npm install composer '.$verbose );

//  add app/libraries to dump-autoload
	$search=            'app/database/migrations';
	$insert='			"app/libraries",';
	adjustFile( $website , "composer.json", $search, $insert );

	$search=   "app_path().'/models',";
	$insert="	app_path().'/libraries',";
	adjustFile( $website , "app/start/global.php", $search, $insert );

// INSTALL BOWER
	echo "\n\ninstall local bower\n";
 	system( 'cd '.$website. ';sudo npm install bower '.$verbose );


// DO BOWER
	echo "\n\ndownload bower files\n";
	if ( copy( $repo."/bowerrc"   , "$website/.bowerrc"   ) ) 	echo "/.bowerrc\n";
	if ( copy( $repo."/bower.json", "$website/bower.json" ) ) 	echo "/bower.json\n";

	nameReplace( "$website/.bowerrc", $website );
	
	echo "\nbower install\n";
 	system( 'cd '.$website. ';bower install' );

	echo "\ncopy bower components\n";
	system( "cp $website/app/assets/bower_components/font-awesome/fonts/fontawesome-webfont.* $website/app/assets/fonts/" );
	

// DO GRUNT
	echo "\n\ndownload grunt file\n";
	if ( copy( $repo."/Gruntfile.js"   , "$website/Gruntfile.js"   ) ) 	echo "/Gruntfile.js\n";
	if ( copy( $repo."/package.json"   , "$website/package.json"   ) ) 	echo "/package.json\n";
	nameReplace( "$website/Gruntfile.js", $website );
	nameReplace( "$website/package.json", $website );
	
// INSTALL GRUNT
	echo "\n\ninstall local grunt\n";
 	system( 'cd '.$website. ';sudo npm install grunt '.$verbose );

// INSTALL GRUNT FILES
	echo "\n\ninstall grunt plugins\n";
 	system( 'cd '.$website. ';sudo npm update '.$verbose );
	
/*

	echo "\ninstall local grunt plugins";
	$plugins= Array(
		"grunt-contrib-watch",
		"grunt-contrib-sass",
		"grunt-contrib-uglify",
		"grunt-contrib-copy",
		"jpegtran-bin"
	);
	foreach ($plugins as $key => $plugin) {
		echo "\ninstall local grunt plugin $plugin\n";
		system( "cd $website;sudo npm install $plugin $verbose" );
	}

*/
	
// RUN GRUNT	
    echo "\nrun grunt\n";
	system( 'cd '.$website. ';grunt sass' );
	system( 'cd '.$website. ';grunt uglify' );
	system( 'cd '.$website. ';grunt copy' );


// DO MAMP CHANGES
	$unames = posix_uname();
	//'nodename' => string 'Joeris-MacBook-Pro.local' (length=24)
	$local  = $unames['nodename'];
	$local  = str_replace('.local', '', $local );
	$local  = str_replace('-', '', $local );


// DO LARAVEL CHANGES
	$insert = "\$env = \$app->detectEnvironment(function () {\n\n\treturn isset(\$_SERVER['LARAVEL_ENV']) ? \$_SERVER['LARAVEL_ENV'] : 'production';\n\n});";
	swapfromFile( $website , "bootstrap/start.php", "\$env = \$app->detectEnvironment", "));", $insert );

	//'nodename' => string 'Joeris-MacBook-Pro.local' (length=24)
	$unames = posix_uname();
	$local  = $unames['nodename'];
	$local  = str_replace('.local', '', $local );
	$local  = str_replace('-', '', $local );
	$local  = "dev_" . $local;

	if ( $mamp != '' ){
		setenv( $local );
		if ( copy( $repo."/$local.zip" , "$website/app/config/dev_pack.zip" ) ) echo "/dev_pack.zip\n";
		system( "cd $website/app/config/; unzip dev_pack.zip");
		system( "rm $website/app/config/dev_pack.zip");
	}


	if ( $admin != '' ){
		
	}

	echo "\n\nNow build something amazing...\n";

	exit;




function nameReplace( $filename, $website )
{
	$filedata = file_get_contents( $filename );
	$filedata = str_replace( "%name%", $website, $filedata );
	file_put_contents( $filename , $filedata );
}

function adjustFile( $website , $filename, $search, $insert )
{
	$filedata = file_get_contents( "$website/$filename" );
	$lines = explode( "\n", $filedata );
	foreach ($lines as $key => $code) {
		if ( strpos( $code , $search ) >-1 ){
			array_splice( $lines, $key-1, 0, $insert );
		}
	}
	$filedata = join( "\n", $lines );
	file_put_contents( "$website/$filename" , $filedata );

}

function swapfromFile( $website , $filename, $startsearch, $endsearch, $insert )
{
	$startline = -1;
	$endline   = -1;
	
	$filedata = file_get_contents( "$website/$filename" );
	$lines = explode( "\n", $filedata );
	foreach ($lines as $key => $code) {
		if ( $startline == -1 ){
			if ( strpos( $code , $startsearch ) >-1 ){ $startline = $key; }
		}else{
			if ( $endline == -1 ){
				if ( strpos( $code , $endsearch ) >-1 ){ $endline = $key; }
			}
		}
	}
	if (( $startline > -1 ) && ( $endline > -1 )) {
		array_splice( $lines, $endline + 1, 0, '*/' );
		array_splice( $lines, $startline , 0, '/*' );
		array_splice( $lines, $startline , 0, $insert );
		
		$filedata = join( "\n", $lines );
		file_put_contents( "$website/$filename" , $filedata );
	}
	
}

function setenv( $name ){

	$filedata = file_get_contents( "/Applications/MAMP/conf/apache/httpd.conf" );
	$lines = explode( "\n", $filedata );
	// if 'SetEnv LARAVEL '.$name exists then return
	foreach ($lines as $key => $code) {
		if ( $code == 'SetEnv LARAVEL '.$name ) return;
	}

	foreach ($lines as $key => $code) {
		if ( strpos( $code , "SetEnv LARAVEL" ) == 0 ){
			$lines[ $key ] = '#'.$code;
			array_splice( $lines, $key, 0, 'SetEnv LARAVEL '.$name );
		}
	}
	$filedata = join( "\n", $lines );
	file_put_contents( "/Applications/MAMP/conf/apache/httpd.conf" , $filedata );

}


?>