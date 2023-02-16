import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-trailer-button', {
	edit: Edit,
	save: () => null,
});
