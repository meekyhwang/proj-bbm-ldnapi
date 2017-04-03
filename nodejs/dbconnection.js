var mysql = require("mysql");
var config = require('./config/database');

var pool = mysql.createPool({
    connectionLimit: 100,
    host: config.db_host,
    user: config.db_user,
    password: config.db_password,
    database: config.database,
    debug: false
});
module.exports = pool;


