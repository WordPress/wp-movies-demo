import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-genres', {
	edit: Edit,
	save: () => null,
});
