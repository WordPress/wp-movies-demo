/**
 * WordPress dependencies.
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'wpmovies', {
	state: {
		get isPlaying() {
			return state.currentVideo !== '';
		},
	},
	actions: {
		closeVideo: () => {
			state.currentVideo = '';
		},
		setVideo: () => {
			const ctx = getContext();
			state.currentVideo =
				'https://www.youtube.com/embed/' + ctx.videoId + '?autoplay=1';
		},
	},
} );
