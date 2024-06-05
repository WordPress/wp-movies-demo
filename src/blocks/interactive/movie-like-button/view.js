/**
 * WordPress dependencies.
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
	state: {
		get isMovieIncluded() {
			const ctx = getContext();
			return state.likedMovies.includes(ctx.post.id);
		},
	},
	actions: {
		toggleMovie: () => {
			const ctx = getContext();
			const index = state.likedMovies.findIndex(
				(post) => post === ctx.post.id
			);
			if (index === -1) state.likedMovies.push(ctx.post.id);
			else state.likedMovies.splice(index, 1);
		},
	},
});
