// Disclaimer: Importing the `store` using a global is just a temporary solution.
import { store } from '@wordpress/interactivity';

const { state } = store({
	state: {
		likesCount() {
			return state.likedMovies.length;
		},
		isLikedMoviesNotEmpty() {
			return state.likedMovies.length !== 0;
		},
	},
});
