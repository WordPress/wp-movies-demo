// Disclaimer: Importing the `store` using a global is just a temporary solution.
import { store } from '@wordpress/interactivity';

store('wpmovies', {
	state: {
		get likesCount() {
			return state.likedMovies.length;
		},
		get isLikedMoviesNotEmpty() {
			return state.likedMovies.length !== 0;
		},
	},
});
