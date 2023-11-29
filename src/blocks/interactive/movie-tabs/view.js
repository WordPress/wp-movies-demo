// Disclaimer: Importing the `store` using a global is just a temporary solution.
import { store, getContext } from '@wordpress/interactivity';

store({
	state: {
		get isImagesTab() {
			const context = getContext();
			return context.tab === 'images';
		},
		get isVideosTab() {
			const context = getContext();
			return context.tab === 'videos';
		},
	},
	actions: {
		showImagesTab: () => {
			const context = getContext();
			context.tab = 'images';
		},
		showVideosTab: () => {
			const context = getContext();
			context.tab = 'videos';
		},
	},
});
