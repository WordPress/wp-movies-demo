import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-runtime', {
	edit: Edit,
	save: () => null,
});
