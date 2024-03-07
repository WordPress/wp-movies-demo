/**
 * WordPress dependencies.
 */
import { getContext, store } from '@wordpress/interactivity';

store('wpmovies', {
	state: {
		isImagesTab: () => {
			const ctx = getContext();
			return ctx.tab === 'images';
		},
		isVideosTab: () => {
			const ctx = getContext();
			return ctx.tab === 'videos';
		},
	},
	actions: {
		showImagesTab: () => {
			const ctx = getContext();
			ctx.tab = 'images';
		},
		showVideosTab: () => {
			const ctx = getContext();
			ctx.tab = 'videos';
		},
	},
});
