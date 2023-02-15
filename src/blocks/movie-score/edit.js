import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';
import './style.css';

const Edit = () => {
	return <div {...useBlockProps()}>Movie Score</div>;
};

export default Edit;
