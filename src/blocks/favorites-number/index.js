import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

registerBlockType('wpmovies/favorites-number', {
	edit: Edit,
	save: () => null,
});
