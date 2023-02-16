import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <div {...useBlockProps()}>Video Player</div>;
};

export default Edit;
