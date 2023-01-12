import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-data', {
	edit: Edit,
	save: () => null,
});
