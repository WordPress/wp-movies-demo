import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<ul class="wpmovies-data-list">
				<li class="wpmovies-data-list-item">
					<div class="wpmovies-data-list-key">Language</div>
					<div class="wpmovies-data-list-value">En</div>
				</li>
				<li class="wpmovies-data-list-item">
					<div class="wpmovies-data-list-key">Budget</div>
					<div class="wpmovies-data-list-value">$250,000,000</div>
				</li>
				<li class="wpmovies-data-list-item">
					<div class="wpmovies-data-list-key">Revenue</div>
					<div class="wpmovies-data-list-value">$800,000,000</div>
				</li>
			</ul>
		</div>
	);
};

export default Edit;
