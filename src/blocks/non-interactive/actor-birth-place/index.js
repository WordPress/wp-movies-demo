import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType('wpmovies/actor-birth-place', {
	edit: Edit,
	save: () => null,
});
