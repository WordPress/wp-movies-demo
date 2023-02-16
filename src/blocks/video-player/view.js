import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	state: {
		wpmovies: {
			currentVideo: '',
			isPlaying: false,
		},
	},
	actions: {
		wpmovies: {
			closeVideo: ({ state }) => {
				state.wpmovies.isPlaying = false;
				state.wpmovies.currentVideo = '';
			},
		},
	},
});
