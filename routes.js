var mysql   = require("mysql");
var templates = require("./controllers/templates");
var playlists = require("./controllers/playlists");

function rest(router,connection) {
    var self = this;
    templates.init(connection);
    self.handleRoutes(router,connection);
}

rest.prototype.handleRoutes = function(router,connection) {
    var self = this;
    router.get("/",function(req,res){
        res.json({"Message" : "Yes API works!"});
    });

    router.get("/templates",templates.getAllTemplates);

    router.get("/templates/:template_id",function(req,res){
        var query = "SELECT * FROM ?? WHERE ??=? AND ?? = ?";
        var table = ["wp_posts", "post_type", "template", "ID",req.params.template_id];
        query = mysql.format(query,table);
        connection.query(query,function(err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                res.json({"Error" : false, "Message" : "Success", "Templates" : rows});
            }
        });
    });

    router.get("/playlists",function(req,res){
        var query = "SELECT * FROM ?? WHERE ?? = ?";
        var table = ["wp_posts", "post_type", "playlist"];
        query = mysql.format(query,table);
        connection.query(query,function(err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                res.json({"Error" : false, "Message" : "Success", "Playlists" : rows});
            }
        });
    });

    router.get("/playlists/:playlist_id",function(req,res){
        var query = "SELECT * FROM ?? WHERE ??=? AND ?? = ?";
        var table = ["wp_posts", "post_type", "playlists", "ID",req.params.playlist_id];
        query = mysql.format(query,table);
        connection.query(query,function(err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                res.json({"Error" : false, "Message" : "Success", "Playlists" : rows});
            }
        });
    });
}

module.exports = rest;
