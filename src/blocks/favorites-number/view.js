import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	state: {
		favorites: {
			posts: [],
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
