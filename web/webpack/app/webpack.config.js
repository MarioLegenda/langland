const path = require('path');
const webpack = require('webpack');
const Uglify = require("uglifyjs-webpack-plugin");

module.exports = {
    entry: ['whatwg-fetch', './../../js/app/entry.jsx'],
    output: {
        path: path.resolve('../../dist/js/app'),
        filename: 'bundle.js'
    },
    watchOptions: {
        poll: true
    },
    module: {
        rules: [
            { test: /\.js|\.jsx$/, loader: 'babel-loader', exclude: /node_modules/ },
            {
                test: /\.jsx|\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets:['es2015', 'react']
                }
            }
        ]
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
        },
        extensions: ['*', '.js', '.vue', '.json']
    }
};

if (process.env.NODE_ENV === "production") {
    module.exports.devtool = '#source-map';
    // http://vue-loader.vuejs.org/en/workflow/production.html
    module.exports.plugins = (module.exports.plugins || []).concat([
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            compress: {
                warnings: false
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true
        })
    ])
}