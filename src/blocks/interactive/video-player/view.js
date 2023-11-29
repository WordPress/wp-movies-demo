// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store, getContext } = window.__experimentalInteractivity;

store('wpmovies', {
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
