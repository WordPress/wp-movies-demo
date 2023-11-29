// Disclaimer: Importing the `store` using a global is just a temporary solution.
// import { store, getContext } from '@wordpress/interactivity';
const {
	store,
	getContext,
} = require('../../../../../gutenberg/node_modules/@wordpress/interactivity');

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
