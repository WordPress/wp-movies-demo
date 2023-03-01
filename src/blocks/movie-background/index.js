import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import Edit from './edit';
import './style.css';

registerBlockType('wpmovies/movie-background', {
	edit: Edit,
	save: () => {
		return (
			<div>
				<InnerBlocks.Content />
			</div>
		);
	},
});
