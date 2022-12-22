<?php

use Tmdb\Repository\MovieRepository;

require_once (__DIR__.'/../vendor/autoload.php');

function createXML() {
	$client                  = require_once( __DIR__ . '/setup-client.php' );
	$dom                     = new DOMDocument( '1.0', 'UTF-8' );
	$dom->formatOutput       = true;
	$dom->preserveWhiteSpace = false;
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
	$repository = new MovieRepository( $client );
	$counter    = readline( 'Enter first ID that will be created: ' ) + 1;
	// force counter to be at least an integer with value 1
	$counter = max( 1, (int) $counter );
	for ( $i = 1; $i <= 3; $i++ ) {
		$movies = $repository->getPopular( array( 'page' => $i ) );
		foreach ( $movies as $movie ) {
			$item_attachment = addMovieAttachment( $movie, $dom, $counter );
			$item            = addMovie( $movie, $dom, $counter );
			$channel->appendChild( $item );
			$channel->appendChild( $item_attachment );
			$counter++;
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

function addMovie( $movie, $dom, $index ) {
	if ( $dom ) {
		$movie_title = htmlspecialchars( $movie->getTitle() );
		$item        = $dom->createElement( 'item' );
		$title       = $dom->createElement( 'title', $movie_title );
		$item->appendChild( $title );
		$content_encoded = $dom->createElement( 'content:encoded', $movie->getOverview() );
		$item->appendChild( $content_encoded );
        if ( $movie->getTagline() ) {
            $excerpt_encoded = $dom->createElement( 'excerpt:encoded', $movie->getTagline() );
            $item->appendChild( $excerpt_encoded );
        }
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'publish' );
		$item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', 'movies' );
		$item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $movie_title );
		$item->appendChild( $wp_post_name );
		$wp_post_date = $dom->createElement( 'wp:post_date', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
		$item->appendChild( $wp_post_date );
		$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
		$item->appendChild( $wp_post_date_gmt );
		$wp_post_parent = $dom->createElement( 'wp:post_parent', '0' );
		$item->appendChild( $wp_post_parent );
		$wp_menu_order = $dom->createElement( 'wp:menu_order', '0' );
		$item->appendChild( $wp_menu_order );
		$wp_postmeta = addPostMeta( '_wp_attachment_image_alt', $movie_title, $dom );
		$item->appendChild( $wp_postmeta );
		$wp_postmeta = addPostMeta( '_thumbnail_id', $index, $dom, true );
		$item->appendChild( $wp_postmeta );
		return $item;
	}
}

function addMovieAttachment( $movie, $dom, $index ) {
	if ( $dom ) {
		$movie_title = htmlspecialchars( $movie->getTitle() );
		$poster_path = 'https://image.tmdb.org/t/p/original' . $movie->getPosterPath();
		$item        = $dom->createElement( 'item' );
		$title       = $dom->createElement( 'title', $movie_title . '.jpg' );
		$item->appendChild( $title );
		$link = $dom->createElement( 'link', $poster_path );
		$item->appendChild( $link );
		$wp_post_date = $dom->createElement( 'wp:post_date', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
		$item->appendChild( $wp_post_date );
		$wp_post_date_gmt = $dom->createElement( 'wp:post_date_gmt', $movie->getReleaseDate()->format( 'Y-m-d H:i:s' ) );
		$item->appendChild( $wp_post_date_gmt );
		$wp_comment_status = $dom->createElement( 'wp:comment_status', 'closed' );
		$item->appendChild( $wp_comment_status );
		$wp_ping_status = $dom->createElement( 'wp:ping_status', 'closed' );
		$item->appendChild( $wp_ping_status );
		$wp_status = $dom->createElement( 'wp:status', 'inherit' );
		$item->appendChild( $wp_status );
		$wp_post_type = $dom->createElement( 'wp:post_type', 'attachment' );
		$item->appendChild( $wp_post_type );
		$wp_post_name = $dom->createElement( 'wp:post_name', $movie_title . '.jpg' );
		$item->appendChild( $wp_post_name );
		$attachment_url = $dom->createElement( 'wp:attachment_url', $poster_path );
		$item->appendChild( $attachment_url );
		$post_id = $dom->createElement( 'wp:post_id', $index );
		$item->appendChild( $post_id );
		$wp_post_parent = $dom->createElement( 'wp:is_sticky', '0' );
		$item->appendChild( $wp_post_parent );
		$wp_postmeta = addPostMeta( '_wc_attachment_source', $poster_path, $dom, true );
		$item->appendChild( $wp_postmeta );
		return $item;
	}
}
$dom = createXML();

$dom->save( 'wpmoviesdb_sampledata.xml' );

echo PHP_EOL.'Movies xml file created 🍿🎬'.PHP_EOL;
exit;
