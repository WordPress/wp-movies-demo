import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-like-icon', {
	edit: Edit,
	save: () => null,
});
