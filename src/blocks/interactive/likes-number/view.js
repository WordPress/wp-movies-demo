// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store } = window.__experimentalInteractivity;

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
