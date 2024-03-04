// Disclaimer: Importing the `store` using a global is just a temporary solution.
import { store } from '@wordpress/interactivity';

const { state } = store({
	state: {
		likesCount: () => state.wpmovies.likedMovies.length,
		isLikedMoviesNotEmpty: () => state.wpmovies.likedMovies.length !== 0,
	},
});
