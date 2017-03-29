var mysql = require("mysql");
var dbconn;

var Templates = {
    init : function(connection){
        dbconn = connection;
    },
    getAllTemplates: function (req, res, next) {
        console.log(dbconn);
        var query = "SELECT * FROM ?? WHERE ?? = ?";
        var table = ["wp_posts", "post_type", "template"];
        query = mysql.format(query, table);
        dbconn.query(query, function (err, rows) {
            if (err) {
                res.json({"Error": true, "Message": "Error executing MySQL query"});
            } else {
                res.json({"Error": false, "Message": "Success", "Templates": rows});
            }
        });
    }
    // getTaskById:function(id,callback){
    //
    //     return db.query("select * from task where Id=?",[id],callback);
    // },
    // addTask:function(Task,callback){
    //     return db.query("Insert into task values(?,?,?)",[Task.Id,Task.Title,Task.Status],callback);
    // },
    // deleteTask:function(id,callback){
    //     return db.query("delete from task where Id=?",[id],callback);
    // },
    // updateTask:function(id,Task,callback){
    //     return db.query("update task set Title=?,Status=? where Id=?",[Task.Title,Task.Status,id],callback);
    // }

};

module.exports = Templates;