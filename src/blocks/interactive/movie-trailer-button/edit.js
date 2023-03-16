import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<div class="wpmovies-page-button-parent">
				<div class="wpmovies-page-button-child">
					<span>Play trailer</span>
				</div>
			</div>
		</div>
	);
};

export default Edit;
