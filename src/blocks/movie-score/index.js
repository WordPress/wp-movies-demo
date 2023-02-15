import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-score', {
	edit: Edit,
	save: () => null,
});
