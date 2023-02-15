import '@wordpress/block-editor';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<InnerBlocks />
		</div>
	);
};

export default Edit;
