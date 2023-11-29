// Disclaimer: Importing the `store` using a global is just a temporary solution.
// import { store, getContext } from '@wordpress/interactivity';
const {
	store,
	getContext,
} = require('../../../../../gutenberg/node_modules/@wordpress/interactivity');
const { state } = store('wpmovies', {
	state: {
		get isMovieIncluded() {
			const context = getContext();
			return state.likedMovies.includes(context.post.id);
		},
	},
	actions: {
		toggleMovie: () => {
			const context = getContext();
			const index = state.likedMovies.findIndex(
				(post) => post === context.post.id
			);
			if (index === -1) state.likedMovies.push(context.post.id);
			else state.likedMovies.splice(index, 1);
		},
	},
});
