import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Edit = () => {
	return (
		<>
			<div {...useBlockProps()}>
				<div className="filled-stars" style={{ width: '100%' }}></div>
			</div>
			<div>200 {__('Reviews', 'wp-movies')}</div>
		</>
	);
};

export default Edit;
