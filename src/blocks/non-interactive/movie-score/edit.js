import { useBlockProps } from '@wordpress/block-editor';
import './style.css';

const Edit = () => {
	return (
		<div
			{ ...useBlockProps() }
			className="wpmovies-score-wrap"
			style={ {
				backgroundColor: '#21d07a55',
			} }
		>
			<div className="wpmovies-score-circle">
				<div
					className="wpmovies-score-mask wpmovies-score-full"
					style={ { transform: 'rotate(135deg)' } }
				>
					<div
						className="wpmovies-score-fill"
						style={ {
							backgroundColor: '#21d07a',
							transform: 'rotate(135deg)',
						} }
					></div>
				</div>
				<div className="wpmovies-score-mask wpmovies-score-half">
					<div
						className="wpmovies-score-fill"
						style={ {
							backgroundColor: '#21d07a',
							transform: 'rotate(135deg)',
						} }
					></div>
				</div>
				<div className="wpmovies-score-inside-circle">
					<p>75%</p>
				</div>
			</div>
		</div>
	);
};

export default Edit;
