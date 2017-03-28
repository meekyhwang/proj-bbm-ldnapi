var sites = {};
    sites.allowed_sites = [{
        id: 1,
        name: "LDNScreen",
        password: "L0nd0n123"
    }, {
        id: 2,
        name: "LDNNYC",
        password: "N3wy0rk123"
    }];

    sites.find = function (obj, fn) {
        for(var i = 0; i < this.allowed_sites.length; i++){
            if( this.allowed_sites[i].name == obj.name){
                return fn(false, this.allowed_sites[i]);
            }else {
                var err = new Error('Site does not exist');
                return fn(err);
            }
        }
    };

module.exports = sites;