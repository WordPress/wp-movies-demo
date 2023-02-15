import '@wordpress/block-editor';
import { useBlockProps } from '@wordpress/block-editor';
import './style.css';

const Edit = () => {
	return (
		<div
			{...useBlockProps()}
			class="wpmovies-score-wrap"
			style={{
				backgroundColor: '#21d07a55',
			}}
		>
			<div class="wpmovies-score-circle">
				<div
					class="wpmovies-score-mask wpmovies-score-full"
					style={{ transform: 'rotate(135deg)' }}
				>
					<div
						class="wpmovies-score-fill"
						style={{
							backgroundColor: '#21d07a',
							transform: 'rotate(135deg)',
						}}
					></div>
				</div>
				<div class="wpmovies-score-mask wpmovies-score-half">
					<div
						class="wpmovies-score-fill"
						style={{
							backgroundColor: '#21d07a',
							transform: 'rotate(135deg)',
						}}
					></div>
				</div>
				<div class="wpmovies-score-inside-circle">
					<p>75%</p>
				</div>
			</div>
		</div>
	);
};

export default Edit;
