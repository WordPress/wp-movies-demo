import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-search', {
	edit: Edit,
	save: () => null,
});
