const castQueryLoop = 'wpmovies/cast-query-loop';
const moviesQueryLoop = 'wpmovies/movies-query-loop';

const commonQueryProps = {
	perPage: 4,
	pages: 0,
	offset: 0,
	order: 'asc',
	orderBy: 'title',
	author: '',
	search: '',
	exclude: [],
	sticky: '',
	inherit: false,
	parents: [],
};

const commonAttributes = {
	displayLayout: {
		type: 'flex',
		columns: 4,
	},
	align: 'wide',
	layout: {
		inherit: true,
		justifyContent: 'left',
	},
};

const innerBlocks = [
	[
		'core/post-template',
		{},
		[
			[
				'core/post-featured-image',
				{
					isLink: true,
				},
				[],
			],
			[
				'core/post-title',
				{
					level: 3,
					isLink: true,
					fontSize: 'large',
				},
				[],
			],
		],
	],
	['core/query-pagination'],
	['core/query-no-results'],
];

// Register Movies Query Loop
wp.blocks.registerBlockVariation('core/query', {
	name: moviesQueryLoop,
	title: 'Movies Query Loop',
	attributes: {
		namespace: moviesQueryLoop,
		query: {
			...commonQueryProps,
			postType: 'movies',
		},
		...commonAttributes,
	},
	innerBlocks: innerBlocks,
});

// Register Cast Query Loop
wp.blocks.registerBlockVariation('core/query', {
	name: castQueryLoop,
	title: 'Cast Query Loop',
	attributes: {
		namespace: castQueryLoop,
		query: {
			...commonQueryProps,
			postType: 'actors',
		},
		...commonAttributes,
	},
	innerBlocks: innerBlocks,
});
