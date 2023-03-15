import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <span {...useBlockProps()}>Georgetown, Guyana</span>;
};

export default Edit;
