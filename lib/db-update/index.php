<?php

use Tmdb\Repository\MovieRepository;
use Tmdb\Repository\PeopleRepository;

require_once( dirname( __FILE__ ) . '/../../vendor/autoload.php' );
require_once( dirname( __FILE__ ) . '/setup-client.php' );

require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );
require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
require_once( ABSPATH . 'wp-admin' . '/includes/media.php' );

function get_post_id_from_guid( $guid ) {
	global $wpdb;
	return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
}

// Function to download image from url, upload it, and attach it to a post
// It returns the new attachment ID
function attach_image_to_post( $url, $post_id, $image_data ) {
	$attachment_id = get_post_id_from_guid( $image_data['guid'] );
	if ( $attachment_id == null ) {
		$file             = array();
		$file['name']     = basename( $url );
		$file['tmp_name'] = download_url( $url );
		$attachment_id    = media_handle_sideload( $file, $post_id, null, $image_data );
	}
	return $attachment_id;
}

function add_actor( $actor_info, $movie_id, $movie_term_data ) {
	// 1. Insert actor post in the DB
	$actor_guid            = get_site_url() . '?tmdb_actor_id=' . $actor_info['id'];
	$actor_profile_img_url = 'https://www.themoviedb.org/t/p/w1280' . $actor_info['profile_img_path'];
	$post_id               = get_post_id_from_guid( $actor_guid );
	if ( is_null( $post_id ) ) {
		$post_id = 0;
	}

	$new_actor = array(
		'ID'           => intval( $post_id ),
		'post_title'   => wp_strip_all_tags( $actor_info['name'] ),
		'post_content' => $actor_info['biography'],
		'post_status'  => 'publish',
		'post_author'  => 1,
		'guid'         => $actor_guid,
		'post_type'    => 'actors',
		'meta_input'   => array(
			'_wpmovies_actors_homepage'              => $actor_info['homepage'],
			'_wpmovies_actors_birthday'              => $actor_info['birthday'],
			'_wpmovies_actors_popularity'            => $actor_info['popularity'],
			'_wpmovies_actors_place_of_birth'        => $actor_info['place_of_birth'],
			'_wpmovies_actor_character_' . $movie_id => $actor_info['character'],
		),
	);

	// If the guid doesn't exist, create. If not, update.
	if ( $post_id == 0 ) {
		$post_id = wp_insert_post( $new_actor );
	} else {
		wp_update_post( $new_actor );
	}

	// 2. Upload actor Featured image, attach it to the post, and set Feature Image

	if ( ! empty( $actor_info['profile_img_path'] ) ) {
		$profile_img_data = array(
			'post_title'  => wp_strip_all_tags( $actor_info['name'] ) . ' profile image',
			'post_author' => 1,
			'guid'        => get_site_url() . '?tmdb_image_id=' . $actor_info['profile_img_path'],
			'meta_input'  => array(
				'_wpmovies_img_source' => $actor_profile_img_url,
			),
		);
		$profile_img_id   = attach_image_to_post( $actor_profile_img_url, intval( $post_id ), $profile_img_data );
		set_post_thumbnail( $post_id, $profile_img_id );
	}

	// 3. Add movie category to the actor
	wp_set_object_terms( $post_id, intval( $movie_term_data['term_id'] ), 'movies_tax', true ); // IMPORTANT THE TRUE TO NOT OVERWRITE

	// 4. Create actor category if it doesn't exist and add it to the current movie
	$actor_slug = sanitize_title( $actor_info['name'] );

	$actor_term_data = term_exists( $actor_slug, 'actors_tax' );
	if ( ! $actor_term_data ) {
		$actor_term_data = wp_insert_term( $actor_info['name'], 'actors_tax', array( 'slug' => $actor_slug ) );
	}
	wp_set_object_terms( $movie_id, intval( $actor_term_data['term_id'] ), 'actors_tax', true ); // IMPORTANT THE TRUE TO NOT OVERWRITE
};

function wpmovies_add_movies() {
	// SETUP
	$client           = setup_client();
	$moviesRepository = new MovieRepository( $client );
	$peopleRepository = new PeopleRepository( $client );

	if ( ! isset( $_ENV['MOVIE_PAGES'] ) || ! isset( $_ENV['ACTORS_PER_MOVIE'] ) ) {
		return;
	}

	$movie_pages      = intval( $_ENV['MOVIE_PAGES'] );
	$actors_per_movie = intval( $_ENV['ACTORS_PER_MOVIE'] );

	for ( $i = $movie_pages; $i >= 1; $i-- ) {
		$movies = $moviesRepository->getTopRated( array( 'page' => $i ) );
		foreach ( array_reverse( $movies->toArray() ) as $movie ) {
			// 0. GET DATA
			// From TMDB API
			$movie_id = $movie->getId();
			// Movie Data: https://github.com/php-tmdb/api/blob/master/lib/Tmdb/Repository/MovieRepository.php#L58-L81
			$movie_data = $moviesRepository->load( $movie_id );
			// Filter adult movies
			$movie_adult = $movie_data->getAdult();
			if ( $movie_adult === true ) {
				continue;
			}
			$movie_keywords = $movie_data->getKeywords();
			$skip_movie     = false;
			foreach ( $movie_keywords as $keyword ) {
				$keyword_name = $keyword->getName();
				if ( str_contains( $keyword_name, 'softcore' ) || str_contains( $keyword_name, 'adult' ) || str_contains( $keyword_name, 'porn' ) || str_contains( $keyword_name, 'masturbation' ) || str_contains( $keyword_name, 'prostitution' ) || str_contains( $keyword_name, 'hardcore' ) || str_contains( $keyword_name, 'sex' ) || str_contains( $keyword_name, 'erotic' ) ) {
					$skip_movie = true;
				}
			}
			if ( $skip_movie === true ) {
				continue;
			}

			// Movie Fields
			$movie_title        = $movie_data->getTitle();
			$movie_overview     = $movie_data->getOverview(); // Post Content
			$movie_tagline      = $movie_data->getTagline(); // Post Excerpt
			$movie_vote_average = $movie_data->getVoteAverage(); // In scale x/10
			$movie_vote_count   = $movie_data->getVoteCount();
			$movie_popularity   = $movie_data->getPopularity();
			$movie_status       = $movie_data->getStatus();
			$movie_runtime      = $movie_data->getRuntime();
			$movie_homepage     = $movie_data->getHomepage();
			$movie_language     = $movie_data->getOriginalLanguage();
			if ( $movie_data->getReleaseDate() ) {
				$movie_release_date = $movie_data->getReleaseDate()->format( 'Y-m-d' );
			};
			$movie_revenue = $movie_data->getRevenue();
			$movie_budget  = $movie_data->getBudget();
			// $movie_spoken_languages = $movie_data->getSpokenLanguages(); // Array
			// Movies poster = Featured Image
			$movie_poster_path = $movie_data->getPosterPath();
			$movie_poster_url  = 'https://image.tmdb.org/t/p/w1280' . $movie_poster_path;
			// Movies backdrop Image
			$movie_backdrop_img_path = $movie_data->getBackdropPath();
			$movie_backdrop_img_url  = 'https://image.tmdb.org/t/p/w1280' . $movie_backdrop_img_path;
			// All images. Array of images
			$movie_images = $movie_data->getImages();
			// Movies videos: https://github.com/php-tmdb/api/blob/master/lib/Tmdb/Model/Common/Video.php
			$movie_videos          = $movie_data->getVideos();
			$movie_recommendations = $movie_data->getRecommendations();
			$similar_movies        = $movie_data->getSimilar();
			$movie_genres          = $movie_data->getGenres();
			// Credits & Cast: https://github.com/php-tmdb/api/blob/master/lib/Tmdb/Model/Collection/CreditsCollection.php
			// Abstract Member Model: https://github.com/php-tmdb/api/blob/master/lib/Tmdb/Model/Person/AbstractMember.php
			// Cast Member Model: https://github.com/php-tmdb/api/blob/master/lib/Tmdb/Model/Person/CastMember.php
			$movie_credits = $movie_data->getCredits();
			$movie_actors  = $movie_credits->getCast();

			// 1. INSERT MOVIE INTO DB
			// (with basic information)
			$movie_guid = get_site_url() . '?tmdb_movie_id=' . $movie_id;
			$post_id    = get_post_id_from_guid( $movie_guid );
			if ( is_null( $post_id ) ) {
				$post_id = 0;
			}

			$new_film = array(
				'ID'           => intval( $post_id ),
				'post_title'   => wp_strip_all_tags( $movie_title ),
				'post_content' => $movie_overview,
				'post_excerpt' => $movie_tagline,
				'post_status'  => 'publish',
				'post_date'    => current_time( 'mysql' ),
				'post_author'  => 1,
				'guid'         => $movie_guid,
				'post_type'    => 'movies',
				'meta_input'   => array(
					'_wpmovies_vote_count'   => $movie_vote_count,
					'_wpmovies_vote_average' => $movie_vote_average,
					'_wpmovies_popularity'   => $movie_popularity,
					'_wpmovies_status'       => $movie_status,
					'_wpmovies_homepage'     => $movie_homepage,
					'_wpmovies_release_date' => $movie_release_date,
					'_wpmovies_revenue'      => $movie_revenue,
					'_wpmovies_budget'       => $movie_budget,
					'_wpmovies_runtime'      => $movie_runtime,
					'_wpmovies_language'     => $movie_language,
				),
			);

			// If the guid doesn't exist, create. If not, update.
			if ( $post_id == 0 ) {
				$post_id = wp_insert_post( $new_film );
			} else {
				wp_update_post( $new_film );
			}

			// 2. UPLOAD FEATURED IMAGE
			// Upload, attach it to the post, and set Featured Image
			if ( ! empty( $movie_poster_path ) ) {
				$poster_data = array(
					'post_title'  => wp_strip_all_tags( $movie_title ) . ' poster',
					'post_author' => 1,
					'guid'        => get_site_url() . '?tmdb_image_id=' . $movie_poster_path,
					'meta_input'  => array(
						'_wpmovies_img_source' => $movie_poster_url,
					),
				);
				$poster_id   = attach_image_to_post( $movie_poster_url, $post_id, $poster_data );
				set_post_thumbnail( $post_id, $poster_id );
			};

			// 3. UPLOAD BACKDROP IMAGE
			// Attach it to the post and add custom field
			if ( ! empty( $movie_backdrop_img_path ) ) {
				$backdrop_img_data = array(
					'post_title'  => wp_strip_all_tags( $movie_title ) . ' backdrop image',
					'post_author' => 1,
					'guid'        => get_site_url() . '?tmdb_image_id=' . $movie_backdrop_img_path,
					'meta_input'  => array(
						'_wpmovies_img_source' => $movie_backdrop_img_url,
					),
				);
				$backdrop_img_id   = attach_image_to_post( $movie_backdrop_img_url, $post_id, $backdrop_img_data );
				update_post_meta( intval( $post_id ), '_wpmovies_backdrop_img_id', intval( $backdrop_img_id ) );
			};

			// 4. ADD MORE IMAGES
			// Limit to 5
			$images_array = array();
			$image_count  = 1;
			foreach ( $movie_images as $image ) {
				if ( $image_count <= 5 ) {
					if ( ! empty( $image->getFilePath() ) ) {
						$img_data = array(
							'post_title'  => wp_strip_all_tags( $movie_title ) . ' image',
							'post_author' => 1,
							'guid'        => get_site_url() . '?tmdb_image_id=' . $image->getFilePath(),
							'meta_input'  => array(
								'_wpmovies_img_source' => 'https://www.themoviedb.org/t/p/w1280' . $image->getFilePath(),
							),
						);
						$img_id   = attach_image_to_post( 'https://www.themoviedb.org/t/p/w1280' . $image->getFilePath(), $post_id, $img_data );
					}
					$images_array[] = intval( $img_id );
					$image_count++;
				}
			}
			update_post_meta( intval( $post_id ), '_wpmovies_images', wp_json_encode( $images_array ) );

			// 5. ADD VIDEOS
			// No limit to ensure we include trailers
			$videos_array = array();
			foreach ( $movie_videos as $video ) {
				$video_id    = $video->getId();
				$video_url   = str_replace( '%s', $video->getKey(), $video->getUrlFormat() );
				$video_type  = $video->getType();
				$video_title = str_replace( '"', '\"', $video->getName() );

				$videos_array[] = array(
					'id'   => $video_id,
					'name' => $video_title,
					'type' => $video_type,
					'url'  => $video_url,
				);
			}
			update_post_meta( intval( $post_id ), '_wpmovies_videos', wp_json_encode( $videos_array ) );

			// 6. ADD RECOMMENDED MOVIES
			// Add the ids
			$recommended_movies_array = array();
			foreach ( $movie_recommendations as $recommended_movie ) {
				$recommended_movies_array[] = $recommended_movie->getId();
			}
			update_post_meta( intval( $post_id ), '_wpmovies_recommended', wp_json_encode( $recommended_movies_array ) );

			// 7. ADD SIMILAR MOVIES
			// Add the ids
			$similar_movies_array = array();
			foreach ( $similar_movies as $similar_movie ) {
				$similar_movies_array[] = $similar_movie->getId();
			}
			update_post_meta( intval( $post_id ), '_wpmovies_similar', wp_json_encode( $similar_movies_array ) );

			// 8. ADD GENRES AS CATEGORIES
			foreach ( $movie_genres as $genre ) {
				$genre_name = $genre->getName();
				$genre_slug = sanitize_title( $genre_name );

				// Check if it exists
				$term_data = term_exists( $genre_slug, 'category' );
				if ( ! $term_data ) {
					$term_data = wp_insert_term( $genre_name, 'category', array( 'slug' => $genre_slug ) );
				};
				// Assign it to the current post
				wp_set_object_terms( $post_id, intval( $term_data['term_id'] ), 'category', true ); // IMPORTANT THE `TRUE` TO NOT OVERWRITE
			}

			// 9. CREATE TAX FOR THE MOVIE
			// To add later to the actors post type
			$movie_slug      = sanitize_title( $movie_title );
			$movie_term_data = term_exists( $movie_slug, 'movies_tax' );
			if ( ! $movie_term_data ) {
				$movie_term_data = wp_insert_term( $movie_title, 'movies_tax', array( 'slug' => $movie_slug ) );
			}

			// 10. HANDLE THE ACTORS
			// Limit number of actors
			$actors_number = 1;
			foreach ( $movie_actors as $actor ) {
				if ( $actors_number <= $actors_per_movie ) {
					$actor_id       = $actor->getId();
					$actor_data     = $peopleRepository->load( $actor_id );
					$actor_birthday = null;
					if ( $actor_data->getBirthday() ) {
						$actor_birthday = $actor_data->getBirthday()->format( 'Y-m-d' );
					};
					$actor_info = array(
						'id'               => $actor_id,
						'name'             => $actor_data->getName(),
						'biography'        => $actor_data->getBiography(),
						'homepage'         => $actor_data->getHomepage(),
						'popularity'       => $actor_data->getPopularity(),
						'birthday'         => $actor_birthday,
						'place_of_birth'   => $actor_data->getPlaceOfBirth(),
						'profile_img_path' => $actor_data->getProfilePath(),
						'character'        => $actor->getCharacter(),
					);
					add_actor( $actor_info, $post_id, $movie_term_data );

					$actors_number++;
				}
			}
		}
	}
}
