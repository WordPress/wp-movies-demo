import { store, getContext } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
	state: {
		get isMovieIncluded() {
			const context = getContext();
			return state.likedMovies.includes(context.post.id);
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
