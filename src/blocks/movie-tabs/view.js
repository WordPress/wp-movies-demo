import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	actions: {
		wpmovies: {
			showImagesTab: ({ context }) => {
				context.isImagesTab = true;
				context.isVideosTab = false;
			},
			showVideosTab: ({ context }) => {
				context.isVideosTab = true;
				context.isImagesTab = false;
			},
		},
	},
});
