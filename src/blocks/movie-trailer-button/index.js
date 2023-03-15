import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-trailer-button', {
	edit: Edit,
	save: () => null,
});
