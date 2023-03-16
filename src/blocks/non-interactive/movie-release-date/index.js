import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/movie-release-date', {
	edit: Edit,
	save: () => null,
});
