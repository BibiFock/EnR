const path = require('path');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
// opti build
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

const PATHS = {
    app: path.resolve(__dirname,'src/app'),
    build: path.resolve(__dirname,'../public'),
    assets: path.resolve(__dirname,'src/public/assets')
};

const HtmlWebpackPlugin = require('html-webpack-plugin');

const ENV = process.env.npm_lifecycle_event;
const isProd = ENV === 'build';

const webpackConfig = {
    mode: (isProd ? 'production' : 'development'),
    entry: {
        app: PATHS.app + "/index.js"
    },
    output: {
        path: PATHS.build,
        publicPath: isProd ? '/' : 'http://localhost:6868/',
        filename: isProd ? 'js/[name].[hash].js' : 'js/[name].js',
        chunkFilename: isProd ? 'js/[id].[hash].chunk.js' : 'js/[id].chunk.js'
    },
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            'vue': 'vue/dist/vue.min.js'
        }
    },
    devServer: {
        publicPath: '/',
        host: '127.0.0.1',
        port: 6868,
        historyApiFallback: true,
    },
    devtool: (isProd ? false : 'inline-source-map'),
    module: {
        rules: [{
            test: /\.vue$/,
            loader: 'vue-loader'
        }, {
            test: /\.scss$/,
            loaders: ['style-loader','css-loader', 'sass-loader']
        }, {
            test: /\.css$/,
            loaders: [
                'vue-style-loader',
                'style-loader',
                'css-loader'
            ]
        }, {
            test: /\.(jpg|png)$/,
            loader: 'file-loader?name=images/[name].[ext]'
        }, {
            test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/,
            loader: 'url-loader?limit=10000&mimetype=application/font-woff'
        }, {
            test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
            loader: 'url-loader?limit=10000&mimetype=application/octet-stream'
        }, {
            test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
            loader: 'file-loader'
        }, {
            test: /\.html$/,
            loader: 'raw-loader', exclude: path.resolve(__dirname,'src/public')
        }, {
            test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
            loader: 'url-loader?limit=10000&mimetype=image/svg+xml'
        }],
    },
    plugins: [
        new VueLoaderPlugin(),
        new HtmlWebpackPlugin({
            template: 'underscore-template-loader!./src/public/index.html',
            chunksSortMode: 'dependency'
        }),
        new CopyWebpackPlugin([{
            from: PATHS.assets, to: 'assets'
        }]),
        new webpack.DefinePlugin({
            // Environment helpers
            'process.env': {
                'ENV': (isProd ? '"production"' : '"dev"'),
                'API_URL': (isProd ? '"https://photovolt.poulpy.org/api/"' : '"http://localhost:6869/api/"'),
                'COORD': {
                    'LATITUDE': 48.8566,
                    'LONGITUDE': 2.3522,
                },
                'MAP': {
                    'TYPE': '"hybrid"',
                    'ZOOM': 13,
                    'ZOOM_EDIT': 17
                }
            }
        }),
    ]
};

if (isProd) {
    webpackConfig.optimization = {
        splitChunks: {
            cacheGroups: {
                default: false,
                commons: {
                    test: /[\\/]node_modules[\\/]/,
                    name: "vendor",
                    chunks: "all"
                }
            }
        }
    },
    webpackConfig.plugins.push(
        new UglifyJsPlugin({
            uglifyOptions: {
                compress: {
                    warnings: false
                }
            },
            sourceMap: false,
            parallel: true
        })
    );
}

module.exports = webpackConfig;
