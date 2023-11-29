import { store } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
	state: {
		get likesCount() {
			return state.likedMovies.length;
		},
		get isLikedMoviesNotEmpty() {
			return state.likedMovies.length !== 0;
		},
	},
});
