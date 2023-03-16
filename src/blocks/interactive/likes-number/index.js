import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/likes-number', {
	edit: Edit,
	save: () => null,
});
