import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
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
