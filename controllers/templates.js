var mysql = require("mysql");
var $ = require("jquery");
var dbconn;

var Templates = {
    init : function(connection){
        dbconn = connection;
    },
    getAllTemplates: function (callback) {
        var query = "SELECT * FROM ?? WHERE ?? = ?";
        var table = ["wp_posts", "post_type", "template"];
        query = mysql.format(query, table);
        return dbconn.query(query,callback);
    },
    getTemplateById:function(id,callback){
        var self = this;
        var query = "SELECT * FROM ?? WHERE ??=? AND ?? = ?";
        var table = ["wp_posts", "post_type", "template", "ID",[id]];
        query = mysql.format(query,table);
        dbconn.query(query,function(err,rows){
            if(err) {
                callback(err,rows);
            } else {
                if(rows.length > 0){
                    //Call Meta
                    var meta = self.getMetaFields(id,callback);
                    console.log(meta); console.log(rows);
                }else {
                    callback(err,rows);
                }
            }
        });
    },
    getMetaFields: function(post_id,callback){
        var query = "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ? AND ( meta_key NOT LIKE ? OR meta_value NOT LIKE ? )";
        var table = [[post_id], '_%', 'field_%'];
        query = mysql.format(query,table);
        dbconn.query(query,function(err,rows){
            if(err) {
                callback(err,rows);
            } else {
                if(rows.length > 0){
                    var meta = [];
                    for(var i=0; i > rows.length; i++) {
                        meta.push({ 'key': row[i].RowDataPacket.meta_key, 'value' : row[i].RowDataPacket.meta_value });
                    }
                    console.log(meta);
                }else {
                    callback(err,rows);
                }
            }
        });
    },
    getNextTemplate:function(){
        //TO Come...
    }
};

module.exports = Templates;