import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

registerBlockType('wpmovies/movie-search', {
	edit: Edit,
	save: () => null,
});
