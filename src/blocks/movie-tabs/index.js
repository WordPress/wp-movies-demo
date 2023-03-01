import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-tabs', {
	edit: Edit,
	save: () => null,
});
