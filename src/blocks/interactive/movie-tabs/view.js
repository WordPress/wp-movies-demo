/**
 * WordPress dependencies.
 */
import { getContext, store } from '@wordpress/interactivity';

store( 'wpmovies', {
	state: {
		get isImagesTab() {
			const ctx = getContext();
			return ctx.tab === 'images';
		},
		get isVideosTab() {
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
} );
