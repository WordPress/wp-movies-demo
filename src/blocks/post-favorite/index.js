import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

registerBlockType('wpmovies/post-favorite', {
	edit: Edit,
	save: () => null,
});
