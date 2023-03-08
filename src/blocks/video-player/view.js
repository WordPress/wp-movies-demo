const { store } = window.__experimentalInteractivity;

store({
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
			setVideo: ({ state, context }) => {
				state.wpmovies.currentVideo =
					'https://www.youtube.com/embed/' +
					context.videoId +
					'?autoplay=1';
			},
		},
	},
});
