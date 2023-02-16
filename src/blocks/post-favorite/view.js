import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	state: {
		favorites: {
			posts: [],
		},
	},
	selectors: {
		favorites: {
			isPostIncluded: ({ state, context: { post } }) =>
				`https://s.w.org/images/core/emoji/14.0.0/svg/${
					state.favorites.posts.includes(post.id) ? '2764' : '1f90d'
				}.svg`,
		},
	},
	actions: {
		favorites: {
			togglePost: ({ state, context }) => {
				const index = state.favorites.posts.findIndex(
					(post) => post === context.post.id
				);
				if (index === -1) state.favorites.posts.push(context.post.id);
				else state.favorites.posts.splice(index, 1);
			},
		},
	},
});
