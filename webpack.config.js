/**
 * WordPress dependencies
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

// Add any a new entry point by extending the webpack config.
module.exports = [
	...defaultConfig,
	{
		...defaultConfig[0],
		entry: {
			'query-loop-variations': './lib/query-loop-variations.js',
		},
	},
];
