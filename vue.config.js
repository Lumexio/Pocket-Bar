module.exports = {
 configureWebpack: {
  // other webpack options to merge in ...
 },
 // devServer Options don't belong into `configureWebpack`
 devServer: {
  host: '0.0.0.0',
  webSocketURL: 'ws://0.0.0.0:6001',

  hot: true,
  disableHostCheck: true,
 },
};