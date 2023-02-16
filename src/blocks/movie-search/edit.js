import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<input
				type="search"
				name="s"
				inputmode="search"
				placeholder={__('Search for a movie', 'wp-movies')}
				required=""
			/>
		</div>
	);
};

export default Edit;
