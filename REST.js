var mysql   = require("mysql");

function REST_ROUTER(router,connection,md5) {
    var self = this;
    self.handleRoutes(router,connection,md5);
}

REST_ROUTER.prototype.handleRoutes = function(router,connection,md5) {
    var self = this;
    router.get("/",function(req,res){
        res.json({"Message" : "Yes API works!"});
    });

    router.get("/templates",function(req,res){
        var query = "SELECT * FROM ?? WHERE ?? = ?";
        var table = ["wp_posts", "post_type", "template"];
        query = mysql.format(query,table);
        connection.query(query,function(err,rows){
            if(err) {
                res.json({"Error" : true, "Message" : "Error executing MySQL query"});
            } else {
                res.json({"Error" : false, "Message" : "Success", "Templates" : rows});
            }
        });
    });

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

    // router.post("/users",function(req,res){
    //     var query = "INSERT INTO ??(??,??) VALUES (?,?)";
    //     var table = ["user_login","user_email","user_password",req.body.email,md5(req.body.password)];
    //     query = mysql.format(query,table);
    //     connection.query(query,function(err,rows){
    //         if(err) {
    //             res.json({"Error" : true, "Message" : "Error executing MySQL query"});
    //         } else {
    //             res.json({"Error" : false, "Message" : "User Added !"});
    //         }
    //     });
    // });
    //
    // router.put("/users",function(req,res){
    //     var query = "UPDATE ?? SET ?? = ? WHERE ?? = ?";
    //     var table = ["user_login","user_password",md5(req.body.password),"user_email",req.body.email];
    //     query = mysql.format(query,table);
    //     connection.query(query,function(err,rows){
    //         if(err) {
    //             res.json({"Error" : true, "Message" : "Error executing MySQL query"});
    //         } else {
    //             res.json({"Error" : false, "Message" : "Updated the password for email "+req.body.email});
    //         }
    //     });
    // });
    //
    // router.delete("/users/:email",function(req,res){
    //     var query = "DELETE from ?? WHERE ??=?";
    //     var table = ["user_login","user_email",req.params.email];
    //     query = mysql.format(query,table);
    //     connection.query(query,function(err,rows){
    //         if(err) {
    //             res.json({"Error" : true, "Message" : "Error executing MySQL query"});
    //         } else {
    //             res.json({"Error" : false, "Message" : "Deleted the user with email "+req.params.email});
    //         }
    //     });
    // });
}

module.exports = REST_ROUTER;
