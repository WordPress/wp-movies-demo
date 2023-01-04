<?php

use Tmdb\Repository\MovieRepository;
use Cocur\Slugify\Slugify;

require_once( __DIR__ . '/../vendor/autoload.php' );

function createXML() {
	$client     = require_once( __DIR__ . '/setup-client.php' );
	$slugify    = new Slugify();
	$moviesDom  = createDom();
	$actorsDom  = createDom();
	$repository = new MovieRepository( $client );
	$moviesDom  = addMovies( $moviesDom, $repository, $slugify );
	$moviesDom->save( 'wp_sampledata_movies.xml' );
	echo PHP_EOL . 'Movies xml file created 🍿🎬' . PHP_EOL;
	$cast      = addActors( $actorsDom, $repository );
	$actorsDom = updateActors( $cast['dom'], $cast['actors'], $slugify );
	$actorsDom->save( 'wp_sampledata_actors.xml' );
	echo PHP_EOL . 'Actors xml file created 🍿🧍' . PHP_EOL;
}

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

function addMovies( $dom, $repository, $slugify ) {
	for ( $i = 1; $i <= 2; $i++ ) {
		$movies = $repository->getPopular( array( 'page' => $i ) );
		foreach ( $movies as $movie ) {
			$item_attachment = addMovieAttachment( $movie, $dom );
			$item            = addMovie( $movie, $dom );
			echo 'Adding ' . $movie->getTitle() . PHP_EOL;
			$credits    = $repository->getCredits( $movie->getId() );
			$castNumber = 0;
			foreach ( $credits->getCast() as $person ) {
				if ( $castNumber < 5 ) {
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
	return $dom;
}

function addActors( $dom, $repository ) {
	for ( $i = 1; $i <= 1; $i++ ) {
		$movies = $repository->getPopular( array( 'page' => $i ) );
		$actors = array();
		foreach ( $movies as $movie ) {
			$credits    = $repository->getCredits( $movie->getId() );
			$castNumber = 0;
			foreach ( $credits->getCast() as $person ) {
				if ( $castNumber < 5 ) {
					if ( ! array_key_exists( $person->getId(), $actors ) ) {
						echo 'Adding ' . $person->getName() . PHP_EOL;
						$item_attachment = addMovieAttachment( $person, $dom, true );
						$item            = addMovie( $person, $dom, true );
						$channel         = $dom->getElementsByTagName( 'channel' )->item( 0 );
						$channel->appendChild( $item );
						if ( $item_attachment ) {
							$channel->appendChild( $item_attachment );
						}
						$castNumber++;
					}
					$actors[ $person->getId() ][] = $movie->getTitle();
				}
			}
		}
	}
	return array(
		'dom'    => $dom,
		'actors' => $actors,
	);
}

function updateActors( $dom, $actors, $slugify ) {
	echo 'Updating Actors with extra films...' . PHP_EOL;
	foreach ( $actors as $actorId => $movies ) {
		$channel = $dom->getElementsByTagName( 'channel' )->item( 0 );
		$items   = $channel->getElementsByTagName( 'item' );
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

function addMovie( $movie, $dom, $is_actor = false ) {
	if ( $dom ) {
		$slugify     = new Slugify();
		$movie_title = htmlspecialchars( $is_actor ? $movie->getName() : $movie->getTitle() );
		$item        = $dom->createElement( 'item' );
		$guid        = $dom->createElement( 'guid', $movie->getId() );
		$guid->setAttribute( 'isPermaLink', 'false' );
		$item->appendChild( $guid );
		$title = $dom->createElement( 'title', $movie_title );
		$item->appendChild( $title );
		if ( ! $is_actor ) {
			$content_encoded = $dom->createElement( 'content:encoded', $movie->getOverview() );
			$item->appendChild( $content_encoded );
		}
		if ( ! $is_actor && $movie->getTagline() ) {
			$excerpt_encoded = $dom->createElement( 'excerpt:encoded', $movie->getTagline() );
			$item->appendChild( $excerpt_encoded );
		}
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'publish' );
		$item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', $is_actor ? 'actors' : 'movies' );
		$item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $slugify->slugify( $movie_title ) );
		$item->appendChild( $wp_post_name );
		if ( ! $is_actor ) {
			$wp_post_date = $dom->createElement( 'wp:post_date', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date );
			$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date_gmt );
		} else {
			$wp_post_date = $dom->createElement( 'wp:post_date', date( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date );
			$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', date( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date_gmt );
		}
		$wp_post_parent = $dom->createElement( 'wp:post_parent', '0' );
		$item->appendChild( $wp_post_parent );
		$wp_menu_order = $dom->createElement( 'wp:menu_order', '0' );
		$item->appendChild( $wp_menu_order );
		$wp_postmeta = addPostMeta( '_wp_attachment_image_alt', $movie_title, $dom );
		$item->appendChild( $wp_postmeta );
		$wp_postmeta = addPostMeta( '_thumbnail_id', $movie->getId(), $dom, true );
		$item->appendChild( $wp_postmeta );
		return $item;
	}
}

function addMovieAttachment( $movie, $dom, $is_actor = false ) {
	if ( $dom ) {
		$slugify     = new Slugify();
		$movie_title = htmlspecialchars( $is_actor ? $movie->getName() : $movie->getTitle() );
		if ( $is_actor ) {
			if ( $movie->getProfileImage()->getFilePath() ) {
				$poster_path = 'https://image.tmdb.org/t/p/original' . $movie->getProfileImage();
			} else {
				return;
			}
		} else {
			$poster_path = 'https://image.tmdb.org/t/p/original' . $movie->getPosterPath();
		}
		$item  = $dom->createElement( 'item' );
		$title = $dom->createElement( 'title', $movie_title );
		$item->appendChild( $title );
		$link = $dom->createElement( 'link', $poster_path );
		$item->appendChild( $link );
		if ( ! $is_actor ) {
			$wp_post_date = $dom->createElement( 'wp:post_date', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date );
			$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date_gmt );
		} else {
			$wp_post_date = $dom->createElement( 'wp:post_date', date( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date );
			$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', date( 'Y-m-d H:i:s' ) );
			$item->appendChild( $wp_post_date_gmt );
		}
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'inherit' );
		$item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', 'attachment' );
		$item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $slugify->slugify( $movie_title ) . '.jpg' );
		$item->appendChild( $wp_post_name );
		$attachment_url = $dom->createElement( 'wp:attachment_url', $poster_path );
		$item->appendChild( $attachment_url );
		$post_id = $dom->createElement( 'wp:post_id', $movie->getId() );
		$item->appendChild( $post_id );
		$wp_post_parent = $dom->createElement( 'wp:is_sticky', '0' );
		$item->appendChild( $wp_post_parent );
		$wp_postmeta = addPostMeta( '_wc_attachment_source', $poster_path, $dom, true );
		$item->appendChild( $wp_postmeta );
		return $item;
	}
}
createXML();
exit;
