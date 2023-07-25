import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';
import { ReactComponent as EmptyHeart } from '../../../../wp-movies-theme/assets/empty-heart.svg';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<EmptyHeart />
		</div>
	);
};

export default Edit;
