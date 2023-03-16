import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/page-background', {
	edit: Edit,
	save: () => null,
});
