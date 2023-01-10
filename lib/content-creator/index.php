<?php

use Cocur\Slugify\Slugify;
use Dotenv\Dotenv;
use Tmdb\Repository\MovieRepository;


require_once( dirname( __FILE__ ) . '/../../vendor/autoload.php' );
require_once( dirname( __FILE__ ) . '/setup-client.php' );

/**
 * Create the XML ready to be imported into a WordPress site.
 */
function createXML() {
	$client     = setup_client();
	$slugify    = new Slugify();
	$moviesDom  = createDom();
	$actorsDom  = createDom();
	$repository = new MovieRepository( $client );
	$movies     = addMovies( $moviesDom, $repository, $slugify );
	$moviesDom  = $movies['dom'];
	$moviesDom->save( 'wp_sampledata_movies.xml' );
	echo PHP_EOL . 'Movies xml file created 🍿🎬' . PHP_EOL;
	$cast      = addActors( $actorsDom, $repository, $slugify, $movies['movies'] );
	$actorsDom = updateActors( $cast['dom'], $cast['actors'], $slugify );
	$actorsDom->save( 'wp_sampledata_actors.xml' );
	echo PHP_EOL . 'Actors xml file created 🍿🧍' . PHP_EOL;
}

/**
 * Create a DOMDocument with the basic structure for a XML import in a WordPress site.
 *
 * @return DOMDocument
 */
function createDom() {
	$dom                     = new DOMDocument( '1.0', 'UTF-8' );
	$dom->formatOutput       = true;
	$dom->preserveWhiteSpace = false;
	$dom->validateOnParse    = true;
	$root                    = $dom->createElement( 'rss' );
	$root->setAttribute( 'version', '2.0' );
	$root->setAttribute( 'xmlns:excerpt', 'http://wordpress.org/export/1.2/excerpt/' );
	$root->setAttribute( 'xmlns:content', 'http://purl.org/rss/1.0/modules/content/' );
	$root->setAttribute( 'xmlns:wfw', 'http://wellformedweb.org/CommentAPI/' );
	$root->setAttribute( 'xmlns:dc', 'http://purl.org/dc/elements/1.1/' );
	$root->setAttribute( 'xmlns:wp', 'http://wordpress.org/export/1.2/' );
	$dom->appendChild( $root );
	$channel = $dom->createElement( 'channel' );
	$root->appendChild( $channel );
	$title = $dom->createElement( 'title', 'WP Movies Demo' );
	$channel->appendChild( $title );
	$link = $dom->createElement( 'link', 'http:' );
	$channel->appendChild( $link );
	$description = $dom->createElement( 'description', 'Just another WordPress Movies site' );
	$channel->appendChild( $description );
	$pubDate = $dom->createElement( 'pubDate', date( 'Y-m-d H:i:s' ) );
	$channel->appendChild( $pubDate );
	$language = $dom->createElement( 'language', 'en-US' );
	$channel->appendChild( $language );
	$wp_wxr_version = $dom->createElement( 'wp:wxr_version', '1.2' );
	$channel->appendChild( $wp_wxr_version );
	$wp_base_site_url = $dom->createElement( 'wp:base_site_url', 'https:' );
	$channel->appendChild( $wp_base_site_url );
	$wp_base_blog_url = $dom->createElement( 'wp:base_blog_url', 'https:' );
	$channel->appendChild( $wp_base_blog_url );
	$generator = $dom->createElement( 'generator', 'https://wordpress.org/?v=6.1.1' );
	$channel->appendChild( $generator );
	$author = $dom->createElement( 'wp:author' );
	$channel->appendChild( $author );
	$author_id = $dom->createElement( 'wp:author_id', '2' );
	$author->appendChild( $author_id );
	$author_login = $dom->createElement( 'wp:author_login', 'moviefan' );
	$author->appendChild( $author_login );
	$author_email = $dom->createElement( 'wp:author_email', 'moviefan@wordpress.org' );
	$author->appendChild( $author_email );
	$author_display_name = $dom->createElement( 'wp:author_display_name', 'moviefan' );
	$author->appendChild( $author_display_name );
	$author_first_name = $dom->createElement( 'wp:author_first_name', 'moviefan' );
	$author->appendChild( $author_first_name );
	$author_last_name = $dom->createElement( 'wp:author_last_name', '' );
	$author->appendChild( $author_last_name );
	return $dom;
}

/**
 * Add movies to the XML.
 *
 * @param DOMDocument     $dom
 * @param MovieRepository $repository - of movies created by the TMDb API PHP library.
 * @param Slugify         $slugify - library to convert any string into a slug.
 * @return DOMDocument with the movies added.
 */
function addMovies( $dom, $repository, $slugify ) {
	$dotenv = Dotenv::createImmutable( __DIR__ . '/../..' );
	$dotenv->load();
	$pages             = isset( $_ENV['MOVIE_PAGES'] ) ? intval( $_ENV['MOVIE_PAGES'] ) : 1;
	$actors_per_movie  = isset( $_ENV['ACTORS_PER_MOVIE'] ) ? intval( $_ENV['ACTORS_PER_MOVIE'] ) : 5;
	$movies_for_actors = array();
	for ( $i = 1; $i <= $pages; $i++ ) {
		$movies = $repository->getPopular( array( 'page' => $i ) );
		foreach ( $movies as $movie ) {
			$item_attachment = addItemAttachment( $movie, $dom, $slugify );
			$item            = addItem( $movie, $dom, $slugify );
			echo 'Adding ' . $movie->getTitle() . PHP_EOL;
			$credits                              = $repository->getCredits( $movie->getId() );
			$castNumber                           = 0;
			$movies_for_actors[ $movie->getId() ] = $movie->getTitle();
			foreach ( $credits->getCast() as $person ) {
				if ( $castNumber < $actors_per_movie ) {
					$category = $dom->createElement( 'category' );
					$category->setAttribute( 'domain', 'actors_tax' );
					$category->setAttribute( 'nicename', $slugify->slugify( $person->getName() ) );
					$category->appendChild( $dom->createCDATASection( $person->getName() ) );
					$item->appendChild( $category );
					$castNumber++;
				}
			}
			$channel = $dom->getElementsByTagName( 'channel' )->item( 0 );
			$channel->appendChild( $item );
			if ( $item_attachment ) {
				$channel->appendChild( $item_attachment );
			}
		}
	}
	return array(
		'dom'    => $dom,
		'movies' => $movies_for_actors,
	);
}
/**
 * Add actors to the XML.
 *
 * @param DOMDocument     $dom
 * @param MovieRepository $repository - of movies created by the TMDb API PHP library.
 * @param Slugify         $slugify - library to convert any string into a slug.
 * @return array with two keys, one for the DOMDocument with the actors added and the other for the array of actors.
 */

function addActors( $dom, $repository, $slugify, $movies_for_actors ) {
	for ( $i = 1; $i <= 1; $i++ ) {
		$dotenv = Dotenv::createImmutable( __DIR__ . '/../..' );
		$dotenv->load();
		$actors           = array();
		$actors_per_movie = isset( $_ENV['ACTORS_PER_MOVIE'] ) ? intval( $_ENV['ACTORS_PER_MOVIE'] ) : 5;
		foreach ( $movies_for_actors as $movieId => $movie_title ) {
			$credits    = $repository->getCredits( $movieId );
			$castNumber = 0;
			echo 'Doing ' . $movie_title . PHP_EOL;
			foreach ( $credits->getCast() as $person ) {
				if ( $castNumber < $actors_per_movie ) {
					if ( ! array_key_exists( $person->getId(), $actors ) ) {
						echo 'Adding ' . $person->getName() . PHP_EOL;
						$item_attachment = addItemAttachment( $person, $dom, $slugify, 'actor' );
						$item            = addItem( $person, $dom, $slugify, 'actor' );
						$channel         = $dom->getElementsByTagName( 'channel' )->item( 0 );
						$channel->appendChild( $item );
						if ( $item_attachment ) {
							$channel->appendChild( $item_attachment );
						}
						$castNumber++;
					}
					$actors[ $person->getId() ][] = $movie_title;
				}
			}
		}
	}
	return array(
		'dom'    => $dom,
		'actors' => $actors,
	);
}

/**
 * Update the actors with the extra movies they have been in.
 *
 * @param DOMDocument $dom
 * @param array       $actors - array of actors with the movies they have been in.
 * @param Slugify     $slugify - library to convert any string into a slug.
 * @return DOMDocument with the actors updated.
 */

function updateActors( $dom, $actors, $slugify ) {
	echo 'Updating Actors with extra films...' . PHP_EOL;
	foreach ( $actors as $actorId => $movies ) {
		$items = $dom->getElementsByTagName( 'item' );
		foreach ( $items as $item ) {
			$guid = $item->getElementsByTagName( 'guid' )->item( 0 );
			if ( $guid && $guid->nodeValue == $actorId ) {
				foreach ( $movies as $movie ) {
					$category = $dom->createElement( 'category' );
					$category->setAttribute( 'domain', 'movies_tax' );
					$category->setAttribute( 'nicename', $slugify->slugify( $movie ) );
					$category->appendChild( $dom->createCDATASection( $movie ) );
					$item->appendChild( $category );
				}
			}
		}
	}
	return $dom;
}

/**
 * Add WordPress post meta tags to the XML.
 *
 * @param string      $key - the key of the post meta.
 * @param string      $value - the value of the post meta.
 * @param DOMDocument $dom
 * @param boolean     $cdata - whether to wrap the value in CDATA tags.
 */

function addPostMeta( $key, $value, $dom, $cdata = false ) {
	$wp_postmeta     = $dom->createElement( 'wp:postmeta' );
	$wp_postmeta_key = $dom->createElement( 'wp:meta_key', $key );
	$wp_postmeta->appendChild( $wp_postmeta_key );
	$wp_postmeta_value = $dom->createElement( 'wp:meta_value' );
	if ( $cdata ) {
		$wp_postmeta_value_cdata = $dom->createCDATASection( $value );
		$wp_postmeta_value->appendChild( $wp_postmeta_value_cdata );
	} else {
		$wp_postmeta_value->nodeValue = $value;
	}
	$wp_postmeta->appendChild( $wp_postmeta_value );
	return $wp_postmeta;
}

/**
 * Add a post type to the XML. Can be a movie or an actor.
 *
 * @param Movie       $item - the movie or actor.
 * @param DOMDocument $dom
 * @param string      $type - the type of item, either 'movie' or 'actor'.
 * @param Slugify     $slugify - library to convert any string into a slug.
 * @return DOMElement with the attachment.
 */

function addItem( $item, $dom, $slugify, $type = 'movie' ) {
	if ( $dom ) {
		$is_actor   = $type === 'actor';
		$item_title = htmlspecialchars( $is_actor ? $item->getName() : $item->getTitle() );
		$dom_item   = $dom->createElement( 'item' );
		$guid       = $dom->createElement( 'guid', $item->getId() );
		$guid->setAttribute( 'isPermaLink', 'false' );
		$dom_item->appendChild( $guid );
		$title = $dom->createElement( 'title', $item_title );
		$dom_item->appendChild( $title );
		if ( ! $is_actor ) {
			$content_encoded = $dom->createElement( 'content:encoded', $item->getOverview() );
			$dom_item->appendChild( $content_encoded );
		}
		if ( ! $is_actor && $item->getTagline() ) {
			$excerpt_encoded = $dom->createElement( 'excerpt:encoded', $item->getTagline() );
			$dom_item->appendChild( $excerpt_encoded );
		}
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$dom_item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$dom_item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'publish' );
		$dom_item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', $is_actor ? 'actors' : 'movies' );
		$dom_item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $slugify->slugify( $item_title ) );
		$dom_item->appendChild( $wp_post_name );
		$wp_post_date = $dom->createElement( 'wp:post_date', date( 'Y-m-d H:i:s' ) );
		$dom_item->appendChild( $wp_post_date );
		$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', date( 'Y-m-d H:i:s' ) );
		$dom_item->appendChild( $wp_post_date_gmt );
		$wp_post_parent = $dom->createElement( 'wp:post_parent', '0' );
		$dom_item->appendChild( $wp_post_parent );
		$wp_menu_order = $dom->createElement( 'wp:menu_order', '0' );
		$dom_item->appendChild( $wp_menu_order );
		$wp_postmeta = addPostMeta( '_wp_attachment_image_alt', $item_title, $dom );
		$dom_item->appendChild( $wp_postmeta );
		$wp_postmeta = addPostMeta( '_thumbnail_id', $item->getId(), $dom, true );
		$dom_item->appendChild( $wp_postmeta );
		return $dom_item;
	}
}

/**
 * Add a post type attachment to the XML. Can be a movie or an actor.
 *
 * @param Movie       $item - the movie or actor.
 * @param DOMDocument $dom
 * @param string      $type - the type of item, either 'movie' or 'actor'.
 * @param Slugify     $slugify - library to convert any string into a slug.
 * @return DOMElement with the attachment.
 */

function addItemAttachment( $item, $dom, $slugify, $type = 'movie' ) {
	if ( $dom ) {
		$is_actor   = $type === 'actor';
		$item_title = htmlspecialchars( $is_actor ? $item->getName() : $item->getTitle() );
		if ( $is_actor ) {
			if ( $item->getProfileImage()->getFilePath() ) {
				$poster_path = 'https://image.tmdb.org/t/p/w500' . $item->getProfileImage();
			} else {
				return;
			}
		} else {
			$poster_path = 'https://image.tmdb.org/t/p/w500' . $item->getPosterPath();
		}
		$dom_item = $dom->createElement( 'item' );
		$title    = $dom->createElement( 'title', $item_title );
		$dom_item->appendChild( $title );
		$link = $dom->createElement( 'link', $poster_path );
		$dom_item->appendChild( $link );
		$wp_post_date = $dom->createElement( 'wp:post_date', date( 'Y-m-d H:i:s' ) );
		$dom_item->appendChild( $wp_post_date );
		$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', date( 'Y-m-d H:i:s' ) );
		$dom_item->appendChild( $wp_post_date_gmt );
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$dom_item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$dom_item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'inherit' );
		$dom_item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', 'attachment' );
		$dom_item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $slugify->slugify( $item_title ) . '.jpg' );
		$dom_item->appendChild( $wp_post_name );
		$attachment_url = $dom->createElement( 'wp:attachment_url', $poster_path );
		$dom_item->appendChild( $attachment_url );
		$post_id = $dom->createElement( 'wp:post_id', $item->getId() );
		$dom_item->appendChild( $post_id );
		$wp_post_parent = $dom->createElement( 'wp:is_sticky', '0' );
		$dom_item->appendChild( $wp_post_parent );
		$wp_postmeta = addPostMeta( '_wc_attachment_source', $poster_path, $dom, true );
		$dom_item->appendChild( $wp_postmeta );
		return $dom_item;
	}
}

// Create the XML.
createXML();

exit;
