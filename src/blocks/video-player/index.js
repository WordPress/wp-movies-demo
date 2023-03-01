import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/video-player', {
	edit: Edit,
	save: () => null,
});
