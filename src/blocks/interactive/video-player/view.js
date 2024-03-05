import { store, getContext } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
	state: {
		isPlaying: () => state.currentVideo !== '',
	},
	actions: {
		closeVideo: () => {
			state.currentVideo = '';
		},
		setVideo: () => {
			const ctx = getContext();
			console.log('ctx', ctx);
			state.currentVideo =
				'https://www.youtube.com/embed/' + ctx.videoId + '?autoplay=1';
		},
	},
});
