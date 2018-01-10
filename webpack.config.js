module.exports = {
	entry: './src/wpiw-block.js',
	output: {
		path: __dirname,
		filename: 'assets/js/wpiw-block.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
				query: {
					presets: ['es2015','react']
				}
			},
		],
	}
};