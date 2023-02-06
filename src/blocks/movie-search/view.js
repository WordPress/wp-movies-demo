import { wpx } from '../../../lib/runtime/wpx.js';
import { navigate } from '../../../lib/runtime/router.js';

const debounce = (func, delay) => {
	let timerId;
	return (...args) => {
		clearTimeout(timerId);
		timerId = setTimeout(() => func(...args), delay);
	};
};

const updateURL = async (value) => {
	const url = new URL(window.location);
	url.searchParams.set('post_type', 'movies');
	url.searchParams.set('orderby', 'name');
	url.searchParams.set('order', 'asc');
	url.searchParams.set('s', value);
	await navigate(`/${url.search}${url.hash}`);
};

wpx({
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
				if (value === state.search.value) return;
				state.search.value = value;

				// Update the URL.
				await updateURL(value);

				// Focus the input.
				document
					.querySelector('.wp-block-wpmovies-movie-search > input')
					.focus();
			},
			focusout: ({ state, event }) => {
				if (
					event.target.value === '' &&
					window.location.search !== ''
				) {
					navigate('/');
				}
			},
		},
	},
});
