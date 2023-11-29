import { store } from '@wordpress/interactivity';

const updateURL = async (value) => {
	const url = new URL(window.location);
	url.searchParams.set('post_type', 'movies');
	url.searchParams.set('orderby', 'name');
	url.searchParams.set('order', 'asc');
	url.searchParams.set('s', value);
	await navigate(`/${url.search}${url.hash}`);
};

const { state } = store('wpmovies', {
	actions: {
		updateSearch: async ({ event }) => {
			const { value } = event.target;

			// Don't navigate if the search didn't really change.
			if (value === state.searchValue) return;

			state.searchValue = value;

			if (value === '') {
				// If the search is empty, navigate to the home page.
				await navigate('/');
			} else {
				// If not, navigate to the new URL.
				await updateURL(value);
			}
		},
	},
});
