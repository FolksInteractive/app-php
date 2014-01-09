angular.module('tc.filters.timeago', [])

    .filter("timeago", function(){
    return function(date){
        return moment.utc(date).fromNow();
    }
})