import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-runtime', {
	edit: Edit,
	save: () => null,
});
