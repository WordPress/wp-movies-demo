import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/actor-birthday', {
	edit: Edit,
	save: () => null,
});
