const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = [
	defaultConfig,
	{
		...defaultConfig,
		entry: {
			'query-loop-variations': './lib/query-loop-variations',
		},
	},
];
