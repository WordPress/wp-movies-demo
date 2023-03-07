import { store } from '../../../../block-hydration-experiments/src/runtime/store.js';

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
