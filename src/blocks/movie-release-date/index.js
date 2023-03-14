import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-release-date', {
	edit: Edit,
	save: () => null,
});
