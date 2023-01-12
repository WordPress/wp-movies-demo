import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <div {...useBlockProps()}>Movie Data</div>;
};

export default Edit;
