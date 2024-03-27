/**
 * WordPress dependencies.
 */
import { getElement, store } from '@wordpress/interactivity';

const updateURL = async (value) => {
	const url = new URL(window.location);
	url.searchParams.set('post_type', 'movies');
	url.searchParams.set('orderby', 'name');
	url.searchParams.set('order', 'asc');
	url.searchParams.set('s', value);
	await store('core/router').actions.navigate(`/${url.search}${url.hash}`);
};

const { state } = store('wpmovies', {
	actions: {
		*updateSearch() {
			const { ref } = getElement();
			const { value } = ref;
			// Don't navigate if the search didn't really change.
			if (value === state.searchValue) return;

			state.searchValue = value;

			if (value === '') {
				// If the search is empty, navigate to the home page.
				yield store('core/router').actions.navigate('/');
			} else {
				// If not, navigate to the new URL.
				yield updateURL(value);
			}
		},
	},
});
