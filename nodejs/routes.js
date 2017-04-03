var mysql   = require("mysql");
var templates = require("./controllers/templates");
var playlists = require("./controllers/playlists");

function rest(router,connection) {
    var self = this;
    templates.init(connection);
    playlists.init(connection);
    self.handleRoutes(router,connection);
}

rest.prototype.handleRoutes = function(router,connection) {
    var self = this;
    router.get("/",function(req,res){
        res.json({"Message" : "Yes API works!"});
    });

    router.get("/templates",function(req,res,next){
        templates.getAllTemplates(function (err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                if(rows.length > 0){
                    res.json({"Error" : false, "Message" : "Success", "RowCount": rows.length,  "Templates" : rows});
                }else {
                    res.json({"Error" : true, "Message" : "No results"});
                }
            }
        });
    });

    router.get("/templates/:template_id",function(req,res,next){
        templates.getTemplateById(req.params.template_id,function (err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                if(rows.length > 0){
                    res.json({"Error" : false, "Message" : "Success", "RowCount": rows.length,  "Templates" : rows});
                }else {
                    res.json({"Error" : true, "Message" : "No results"});
                }
            }
        });
    });

    router.get("/playlists",function(req,res){
        playlists.getAllPlaylists(function (err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                if(rows.length > 0){
                    res.json({"Error" : false, "Message" : "Success", "RowCount": rows.length,  "Playlists" : rows});
                }else {
                    res.json({"Error" : true, "Message" : "No results"});
                }
            }
        });
    });

    router.get("/playlists/:playlist_id",function(req,res){
        playlists.getPlaylistById(req.params.playlist_id,function (err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                if(rows.length > 0){
                    res.json({"Error" : false, "Message" : "Success", "RowCount": rows.length,  "Playlists" : rows});
                }else {
                    res.json({"Error" : true, "Message" : "No results"});
                }
            }
        });
    });
};

module.exports = rest;
