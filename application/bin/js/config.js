var fs = require('fs');

exports.configureExpress = function(app) {
    app.set('PORT', 8000);
    app.set('HOST', 'local.easyshop');
    app.set('PROTOCOL', 'https');
    app.set('KEY', fs.readFileSync('key/easyshop.key'));
    app.set('CERT', fs.readFileSync('key/easyshop.crt'));
};
