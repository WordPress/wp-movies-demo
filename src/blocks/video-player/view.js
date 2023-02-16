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
			setVideo: ({ state, event }) => {
				state.wpmovies.isPlaying = true;
				state.wpmovies.currentVideo =
					'https://www.youtube.com/embed/' +
					event.target.dataset.wpmoviesVideoId +
					'?autoplay=1';
			},
		},
	},
});
