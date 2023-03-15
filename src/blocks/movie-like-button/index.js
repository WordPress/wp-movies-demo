import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-like-button', {
	edit: Edit,
	save: () => null,
});
