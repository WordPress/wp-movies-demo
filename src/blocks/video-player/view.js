import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	state: {
		wpmovies: {
			currentVideo: '',
		},
	},
	selectors: {
		wpmovies: {
			isPlaying: ({ state }) => state.wpmovies.currentVideo !== '',
		},
	},
	actions: {
		wpmovies: {
			closeVideo: ({ state }) => {
				state.wpmovies.currentVideo = '';
			},
			setVideo: ({ state, event }) => {
				state.wpmovies.currentVideo =
					'https://www.youtube.com/embed/' +
					event.currentTarget.dataset.wpmoviesVideoId +
					'?autoplay=1';
			},
		},
	},
});
