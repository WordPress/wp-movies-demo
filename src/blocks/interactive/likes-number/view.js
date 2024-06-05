/**
 * WordPress dependencies.
 */
import { store } from '@wordpress/interactivity';

const { state } = store( 'wpmovies', {
	state: {
		get isLikedMoviesNotEmpty() {
			return state.likedMovies.length > 0;
		},
	},
} );
