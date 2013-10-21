/*
 * This a util service for dates
 */
module.service("utils.DateService", function(){
    /*
     * Return the datetime of the current moment
     */
    var now = function(){
        var date;
        date = new Date();
        date = date.getUTCFullYear() + '-' +
            ('00' + (date.getUTCMonth() + 1)).slice(-2) + '-' +
            ('00' + date.getUTCDate()).slice(-2) + ' ' +
            ('00' + date.getUTCHours()).slice(-2) + ':' +
            ('00' + date.getUTCMinutes()).slice(-2) + ':' +
            ('00' + date.getUTCSeconds()).slice(-2);

        return date;
    }

    /*
     * Public API
     */
    return {
        now : now
    };
})