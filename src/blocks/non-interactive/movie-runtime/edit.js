import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <span {...useBlockProps()}>2h 42m</span>;
};

export default Edit;
