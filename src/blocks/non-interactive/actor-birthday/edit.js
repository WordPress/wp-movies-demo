import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return <span {...useBlockProps()}>9th November 2022</span>;
};

export default Edit;
