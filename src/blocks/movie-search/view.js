import { store } from '../../../lib/runtime/store.js';
import { navigate } from '../../../lib/runtime/router.js';

const updateURL = async (value) => {
	const url = new URL(window.location);
	url.searchParams.set('post_type', 'movies');
	url.searchParams.set('orderby', 'name');
	url.searchParams.set('order', 'asc');
	url.searchParams.set('s', value);
	await navigate(`/${url.search}${url.hash}`);
};

store({
	state: {
		search: {
			value: '',
		},
	},
	actions: {
		search: {
			update: async ({ state, event }) => {
				// Update the state.
				const { value } = event.target;
				if (value === state.search.value) {
					return;
				}
				state.search.value = value;

				// If the search is empty, navigate to the home page.
				if (value === '') {
					await navigate('/');
					return;
				}

				// Update the URL.
				await updateURL(value);

				// Focus the input.
				document
					.querySelector('.wp-block-wpmovies-movie-search > input')
					.focus();
			},
		},
	},
});
