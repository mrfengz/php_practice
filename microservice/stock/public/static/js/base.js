const URL_PREFIX = location.origin+'/index.php';

$.ajaxSetup({
    dataType:"JSON",
    beforeSend: function(){
        console.log('before')
    },
    complete: function(){
        console.log('end')
    }
});


function deleteOne(url, id) {
    $.post(URL_PREFIX + url, {id: id}, function(res){
        if (res.code) {
            layerAlert(res.message, res.code)
        } else {
            // layerAlert(res.message, res.code)
            location.reload()
        }
    });
}
