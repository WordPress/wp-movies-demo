// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store, getContext } = window.__experimentalInteractivity;

store('wpmovies', {
	state: {
		get isMovieIncluded() {
			const context = getContext();
			return state.wpmovies.likedMovies.includes(context.post.id);
		},
	},
	actions: {
		toggleMovie: ({ state, context }) => {
			const index = state.likedMovies.findIndex(
				(post) => post === context.post.id
			);
			if (index === -1) state.likedMovies.push(context.post.id);
			else state.likedMovies.splice(index, 1);
		},
	},
});
