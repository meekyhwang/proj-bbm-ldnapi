var express = require("express");
var mysql = require("mysql");
var bodyParser = require("body-parser");
var md5 = require('MD5');
var morgan = require('morgan');
var jwt = require('jsonwebtoken');
var config = require('./config/database');
var rest = require("./REST.js");
var app = express();
var port = process.env.PORT || 3000;
var allowed_sites = require('./config/sites');

function REST() {
    var self = this;
    self.connectMysql();
};

REST.prototype.connectMysql = function () {
    var self = this;
    var pool = mysql.createPool({
        connectionLimit: 100,
        host: config.db_host,
        user: config.db_user,
        password: config.db_password,
        database: config.database,
        debug: true
    });
    pool.getConnection(function (err, connection) {
        if (err) {
            self.stop(err);
        } else {
            self.configureExpress(connection);
        }
    });
}

REST.prototype.configureExpress = function (connection) {
    var self = this;
    app.set('siteSecret', config.secret);
    app.use(bodyParser.urlencoded({extended: true}));
    app.use(bodyParser.json());
    app.use(morgan('dev'));
    var router = express.Router();

    router.post('/authenticate', function (req, res) {
        //TODO Cleanup
        allowed_sites.find({
            name: req.body.name
        }, function (err, site) {
            if (err) throw err;

            if (!site) {
                res.send({success: false, msg: 'Authentication failed. Site not found.'});
            } else {
                // check if password matches
                if( site.password == req.body.password ){
                    var token = jwt.sign(site, config.secret, {
                        expiresIn: '24h' // expires in 24 hours
                    });
                    // return the information including token as JSON
                    res.json({success: true, message: 'Enjoy your token!', token: token});
                }else {
                    res.send({success: false, msg: 'Authentication failed. Wrong password.'});
                }
            }
        });
    });

    // route middleware to verify a token
    router.use(function (req, res, next) {

        // check header or url parameters or post parameters for token
        var token = req.body.token || req.query.token || req.headers['x-access-token'];

        // decode token
        if (token) {

            // verifies secret and checks exp
            jwt.verify(token, app.get('siteSecret'), function (err, decoded) {
                if (err) {
                    return res.json({success: false, message: 'Failed to authenticate token.'});
                } else {
                    // if everything is good, save to request for use in other routes
                    req.decoded = decoded;
                    next();
                }
            });

        } else {

            // if there is no token
            // return an error
            return res.status(403).send({
                success: false,
                message: 'No token provided.'
            });

        }
    });

    app.use('/api', router);
    var rest_router = new rest(router, connection, md5);
    self.startServer();
}

REST.prototype.startServer = function () {
    app.listen(port, function () {
        console.log("All right ! I am alive at Port " + port);
    });
}

REST.prototype.stop = function (err) {
    console.log("ISSUE WITH MYSQL \n" + err);
    process.exit(1);
}

new REST();
