const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');

module.exports = [
	defaultConfig,
	{
		...defaultConfig,
		entry: {
			runtime: './lib/runtime',
			'query-loop-variations': './lib/query-loop-variations',
			'blocks/favorites-number/view':
				'./src/blocks/favorites-number/view',
			'blocks/post-favorite/view': './src/blocks/post-favorite/view',
			'blocks/movie-search/view': './src/blocks/movie-search/view',
			'blocks/movie-trailer-button/view':
				'./src/blocks/movie-trailer-button/view',
			'blocks/video-player/view': './src/blocks/video-player/view',
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
