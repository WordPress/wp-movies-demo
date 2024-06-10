import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div { ...useBlockProps() }>
			<div className="wpmovies-page-button-parent">
				<div className="wpmovies-page-button-child">
					<span>Play trailer</span>
				</div>
			</div>
		</div>
	);
};

export default Edit;
