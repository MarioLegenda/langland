const path = require('path');
const webpack = require('webpack');
const Uglify = require("uglifyjs-webpack-plugin");

module.exports = {
    entry: './../../js/app/entry.jsx',
    output: {
        path: path.resolve('../../dist/js/app'),
        filename: 'bundle.js'
    },
    watchOptions: {
        poll: true
    },
    module: {
        loaders: [
            { test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/ },
            {
                test: /\.jsx$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets:['es2015', 'react']
                }
            }
        ],
    },
    plugins: [
/*        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify('production')
        }),
        new webpack.optimize.UglifyJsPlugin()*/
    ],
};