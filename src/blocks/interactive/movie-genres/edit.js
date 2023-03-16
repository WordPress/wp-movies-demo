import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<a>Action</a>
			<a>Adventure</a>
			<a>Comedy</a>
		</div>
	);
};

export default Edit;
