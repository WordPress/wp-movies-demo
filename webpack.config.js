const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');

module.exports = [
	defaultConfig,
	{
		...defaultConfig,
		entry: {
			'blocks/interactive/likes-number/view': './src/blocks/interactive/likes-number/view',
			'blocks/interactive/movie-like-icon/view': './src/blocks/interactive/movie-like-icon/view',
			'blocks/interactive/movie-search/view': './src/blocks/interactive/movie-search/view',
			'blocks/interactive/video-player/view': './src/blocks/interactive/video-player/view',
			'blocks/interactive/movie-tabs/view': './src/blocks/interactive/movie-tabs/view',
			'blocks/interactive/movie-like-button/view':
				'./src/blocks/interactive/movie-like-button/view',
		},
	},
	{
		...defaultConfig,
		entry: {
			'query-loop-variations': './lib/query-loop-variations',
		},
		output: {
			filename: '[name].js',
			path: resolve(process.cwd(), 'build'),
		},
		optimization: {
			runtimeChunk: {
				name: 'vendors',
			},
			splitChunks: {
				cacheGroups: {
					vendors: {
						test: /[\\/]node_modules[\\/]/,
						name: 'vendors',
						minSize: 0,
						chunks: 'all',
					},
				},
			},
		},
		module: {
			rules: [
				{
					test: /\.(j|t)sx?$/,
					exclude: /node_modules/,
					use: [
						{
							loader: require.resolve('babel-loader'),
							options: {
								cacheDirectory:
									process.env.BABEL_CACHE_DIRECTORY || true,
								babelrc: false,
								configFile: false,
								presets: [
									[
										'@babel/preset-react',
										{
											runtime: 'automatic',
											importSource: 'preact',
										},
									],
								],
							},
						},
					],
				},
			],
		},
	},
];
