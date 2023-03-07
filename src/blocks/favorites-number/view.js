import { store } from '../../../../block-hydration-experiments/src/runtime/store.js';

store({
	state: {
		favorites: {
			// state.favorites.posts is defined in `favorites-number/view.js`.
			// The state is shared between all blocks!
			count: ({ state }) => state.favorites.posts.length,
		},
	},
	selectors: {
		favorites: {
			isFavoritePostsEmpty: ({ state }) =>
				`https://s.w.org/images/core/emoji/14.0.0/svg/${
					state.favorites.posts.length !== 0 ? '2764' : '1f90d'
				}.svg`,
		},
	},
});
