const { store } = window.__experimentalInteractivity;

store({
	selectors: {
		wpmovies: {
			isImagesTab: ({ context }) => context.tab === 'images',
			isVideosTab: ({ context }) => context.tab === 'videos',
		},
	},
	actions: {
		wpmovies: {
			showImagesTab: ({ context }) => {
				context.tab = 'images';
			},
			showVideosTab: ({ context }) => {
				context.tab = 'videos';
			},
		},
	},
});
