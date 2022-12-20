<?php

use Tmdb\Repository\MovieRepository;

require_once __DIR__.'/vendor/autoload.php';

/** @var Tmdb\Client $client */
$client     = require_once( __DIR__. '/setup-client.php' );
$repository = new MovieRepository( $client );
$movie      = $repository->load( 87421 );

var_dump( $movie->getTitle() );
