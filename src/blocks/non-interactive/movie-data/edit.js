
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<ul className="wpmovies-data-list">
				<li className="wpmovies-data-list-item">
					<div className="wpmovies-data-list-key">Language</div>
					<div className="wpmovies-data-list-value">En</div>
				</li>
				<li className="wpmovies-data-list-item">
					<div className="wpmovies-data-list-key">Budget</div>
					<div className="wpmovies-data-list-value">$250,000,000</div>
				</li>
				<li className="wpmovies-data-list-item">
					<div className="wpmovies-data-list-key">Revenue</div>
					<div className="wpmovies-data-list-value">$800,000,000</div>
				</li>
			</ul>
		</div>
	);
};

export default Edit;
