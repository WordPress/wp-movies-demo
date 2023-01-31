<?php

use Dotenv\Dotenv;
use Tmdb\Client;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\Listener\RequestListener;
use Tmdb\Event\RequestEvent;
use Tmdb\Token\Api\ApiToken;

/**
 * Setup the client needed to work with TMDB API.
 *
 * @return Client
 */
function setup_client() {
	$dotenv = Dotenv::createImmutable( __DIR__ . '/../..' );
	$dotenv->load();

	$api_key = $_ENV['TMDB_API_KEY'];
	if ( $api_key === false || $api_key === '' ) {
		echo 'No API key provided, please update your .env file, exiting.' . PHP_EOL;
		exit( 1 );
	}

	$token = new ApiToken( $api_key );

	$ed = new Symfony\Component\EventDispatcher\EventDispatcher();

	$client = new Client(
		array(
			/** @var ApiToken */
			'api_token'        => $token,
			'event_dispatcher' => array(
				'adapter' => $ed,
			),
			// We make use of PSR-17 and PSR-18 auto discovery to automatically guess these, but preferably set these explicitly.
			'http'             => array(
				'client'           => null,
				'request_factory'  => null,
				'response_factory' => null,
				'stream_factory'   => null,
				'uri_factory'      => null,
			),
		)
	);

	/**
	 * Required event listeners and events to be registered with the PSR-14 Event Dispatcher.
	 */
	$requestListener = new RequestListener( $client->getHttpClient(), $ed );
	$ed->addListener( RequestEvent::class, $requestListener );

	$apiTokenListener = new ApiTokenRequestListener( $client->getToken() );
	$ed->addListener( BeforeRequestEvent::class, $apiTokenListener );

	$acceptJsonListener = new AcceptJsonRequestListener();
	$ed->addListener( BeforeRequestEvent::class, $acceptJsonListener );

	$jsonContentTypeListener = new ContentTypeJsonRequestListener();
	$ed->addListener( BeforeRequestEvent::class, $jsonContentTypeListener );

	$userAgentListener = new UserAgentRequestListener();
	$ed->addListener( BeforeRequestEvent::class, $userAgentListener );

	return $client;
}


