
            var fs = require('fs');
            exports.configureExpress = function(app) {
                app.set('NODE_PORT', 8000);
                app.set('NODE_HOST', 'easyshop.ph.trunk');
                app.set('PROTOCOL', 'https');
                app.set('KEY', fs.readFileSync('/var/www/easyshop/easyshop-git/application/scripts/../bin/nodejs/ChatServer/key/easyshop.key'));
                app.set('CERT', fs.readFileSync('/var/www/easyshop/easyshop-git/application/scripts/../bin/nodejs/ChatServer/key/easyshop.crt'));
                app.set('JWT_SECRET', 'SECRET345y5h0p');
                app.set('REDIS_PORT', 6379);
                app.set('REDIS_HOST', '127.0.0.1');
                app.set('REDIS_CHANNEL_NAME', 'chat-channel');
            };
        