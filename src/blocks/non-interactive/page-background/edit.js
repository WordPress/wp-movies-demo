import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <div {...useBlockProps()}>Page background</div>;
};

export default Edit;
