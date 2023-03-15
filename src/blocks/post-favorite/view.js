// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store } = window.__experimentalInteractivity;

store({
	selectors: {
		wpmovies: {
			isMovieIncluded: ({ state, context: { post } }) =>
				`https://s.w.org/images/core/emoji/14.0.0/svg/${
					state.wpmovies.favoriteMovies.includes(post.id)
						? '2764'
						: '1f90d'
				}.svg`,
		},
	},
	actions: {
		wpmovies: {
			toggleMovie: ({ state, context }) => {
				const index = state.wpmovies.favoriteMovies.findIndex(
					(post) => post === context.post.id
				);
				if (index === -1)
					state.wpmovies.favoriteMovies.push(context.post.id);
				else state.wpmovies.favoriteMovies.splice(index, 1);
			},
		},
	},
});
