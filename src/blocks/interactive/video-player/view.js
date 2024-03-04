import { store } from '@wordpress/interactivity';

const { state } = store('wpmovies', {
	state: {
		isPlaying: () => state.currentVideo !== '',
	},
	actions: {
		closeVideo: ({ state }) => {
			state.currentVideo = '';
		},
		setVideo: ({ state, context }) => {
			state.currentVideo =
				'https://www.youtube.com/embed/' +
				context.videoId +
				'?autoplay=1';
		},
	},
});
