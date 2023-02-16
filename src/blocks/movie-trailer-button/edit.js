import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<a
				target="_blank"
				href="https://www.youtube.com/watch?v=jm2sNLIPPvA"
			>
				Play Trailer
			</a>
		</div>
	);
};

export default Edit;
