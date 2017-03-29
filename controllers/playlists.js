var mysql   = require("mysql");
var dbconn;

var Playlists = {
    init : function(connection){
        dbconn = connection;
    },
    getAllPlaylists: function (callback) {
        var query = "SELECT * FROM ?? WHERE ?? = ?";
        var table = ["wp_posts", "post_type", "playlist"];
        query = mysql.format(query,table);
        return dbconn.query(query,callback);
    },
    getPlaylistById:function(id,callback){
        var query = "SELECT * FROM ?? WHERE ??=? AND ?? = ?";
        var table = ["wp_posts", "post_type", "playlist", "ID",[id]];
        query = mysql.format(query,table);
        return dbconn.query(query,callback);
    },
    getNextPlaylist:function(){
        //TO Come...
    }
};

module.exports = Playlists;