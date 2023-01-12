import wpx from '../../../lib/interactivity-api/runtime/wpx.js';

console.log('wpx', wpx.state.favorites);
wpx({
	state: {
		favorites: {
			posts: [],
			count: ({ state }) => state.favorites.posts.length,
		},
	},
	selectors: {
		favorites: {
			isPostIncluded: ({ state, context: { post } }) =>
				`https://s.w.org/images/core/emoji/14.0.0/svg/${
					state.favorites.posts.includes(post.id) ? '2764' : '1f90d'
				}.svg`,
			isFavoritePostsEmpty: ({ state }) =>
				`https://s.w.org/images/core/emoji/14.0.0/svg/${
					state.favorites.posts.length !== 0 ? '2764' : '1f90d'
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
