import { wpx } from '../../../lib/runtime/wpx.js';

wpx({
	actions: {
		wpmovies: {
			setVideo: ({ state, event }) => {
				state.wpmovies.isPlaying = true;
				state.wpmovies.currentVideo =
					'https://www.youtube.com/embed/' +
					event.target.getAttribute('data-wpmovies-video-id') +
					'?autoplay=1';
			},
		},
	},
});
