import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<button {...useBlockProps}>
			<span>Play trailer</span>
		</button>
	);
};

export default Edit;
