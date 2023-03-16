import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<img
				draggable="false"
				role="img"
				className="emoji"
				alt=":heart:"
				src="https://s.w.org/images/core/emoji/14.0.0/svg/2764.svg"
			/>
			<span>5</span>
		</div>
	);
};

export default Edit;
