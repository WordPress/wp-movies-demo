import { store, getContext } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
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
			const context = getContext();
			state.currentVideo =
				'https://www.youtube.com/embed/' +
				context.videoId +
				'?autoplay=1';
		},
	},
});
