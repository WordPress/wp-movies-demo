// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store, navigate } = window.__experimentalInteractivity;

const siteRoot = document.querySelector('link[rel*="api.w.org"]').href.replace('/wp-json/', '');

const updateURL = async (value) => {
	const url = new URL(window.location);
	url.searchParams.set('post_type', 'movies');
	url.searchParams.set('orderby', 'name');
	url.searchParams.set('order', 'asc');
	url.searchParams.set('s', value);
	await navigate(`${siteRoot}/${url.search}${url.hash}`);
};

store({
	actions: {
		wpmovies: {
			updateSearch: async ({ state, event }) => {
				const { value } = event.target;

				// Don't navigate if the search didn't really change.
				if (value === state.wpmovies.searchValue) return;

				state.wpmovies.searchValue = value;

				if (value === '') {
					// If the search is empty, navigate to the home page.
					await navigate(siteRoot);
				} else {
					// If not, navigate to the new URL.
					await updateURL(value);
				}
			},
		},
	},
});
