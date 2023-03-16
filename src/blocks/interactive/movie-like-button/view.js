// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store } = window.__experimentalInteractivity;

store({
	selectors: {
		wpmovies: {
			isMovieIncluded: ({ state, context: { post } }) =>
				state.wpmovies.likedMovies.includes(post.id),
		},
	},
	actions: {
		wpmovies: {
			toggleMovie: ({ state, context }) => {
				const index = state.wpmovies.likedMovies.findIndex(
					(post) => post === context.post.id
				);
				if (index === -1)
					state.wpmovies.likedMovies.push(context.post.id);
				else state.wpmovies.likedMovies.splice(index, 1);
			},
		},
	},
});
