<?php


function update_header( $root ) {
	$root->getElementsByTagName( 'title' )->item( 0 )->textContent         = 'WP Movies Demo';
	$root->getElementsByTagName( 'link' )->item( 0 )->textContent          = 'http:';
	$root->getElementsByTagName( 'base_site_url' )->item( 0 )->textContent = 'https:';
	$root->getElementsByTagName( 'base_blog_url' )->item( 0 )->textContent = 'https:';
	$root->getElementsByTagName( 'author_email' )->item( 0 )->textContent  = 'moviefan@wordpress.org';
}

function update_links( $root ) {
	$links = $root->getElementsByTagName( 'link' );
	foreach ( $links as $link ) {
		$link_args         = parse_url( $link->textContent );
		$link->textContent = $link_args['path'] . $link_args['query'] . $link_args['fragment'];
	}
}

function update_guids( $root ) {
	$guids = $root->getElementsByTagName( 'guid' );
	foreach ( $guids as $guid ) {
		$guid_args         = parse_url( $guid->textContent );
		$guid->textContent = $guid_args['path'] . $guid_args['query'] . $guid_args['fragment'];
	}
}
// Update movies
$moviesDom = new DOMDocument();
$moviesDom->load( getcwd() . '/wp_sampledata_movies.xml' );
$moviesRoot = $moviesDom->documentElement;
update_links( $moviesRoot );
update_guids( $moviesRoot );
update_header( $moviesRoot );
$moviesDom->save( getcwd() . '/wp_sampledata_movies.xml' );

// Update actors
$actorsDom = new DOMDocument();
$actorsDom->load( getcwd() . '/wp_sampledata_actors.xml' );
$actorsRoot = $actorsDom->documentElement;
update_links( $actorsRoot );
update_guids( $actorsRoot );
update_header( $actorsRoot );
$actorsDom->save( getcwd() . '/wp_sampledata_actors.xml' );

// Update media
$mediaDom = new DOMDocument();
$mediaDom->load( getcwd() . '/wp_sampledata_media.xml' );
$mediaRoot = $mediaDom->documentElement;
update_guids( $mediaRoot );
update_header( $mediaRoot );

$items = $mediaRoot->getElementsByTagName( 'item' );

foreach ( $items as $item ) {
	$img_source     = '';
	$postmeta_array = $item->getElementsByTagName( 'postmeta' );
	foreach ( $postmeta_array as $postmeta ) {
		if ( $postmeta->getElementsByTagName( 'meta_key' )->item( 0 )->textContent === '_wpmovies_img_source' ) {
			$img_source = $postmeta->getElementsByTagName( 'meta_value' )->item( 0 )->textContent;
		}
	};
	// $initial_link_node = $item->getElementsByTagName('link');
	// $new_link_node = $mediaDom->createTextNode($img_source);
	// $item->replaceChild($new_link_node, $initial_link_node);

	// $item->link = $img_source;

	$item->getElementsByTagName( 'link' )->item( 0 )->textContent           = $img_source;
	$item->getElementsByTagName( 'attachment_url' )->item( 0 )->textContent = $img_source;
}

$mediaDom->save( getcwd() . '/wp_sampledata_media.xml' );
