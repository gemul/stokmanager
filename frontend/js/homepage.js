// homepage info
$.ajax({
    url:"api/api.php?mod=home.info",
    dataType:'JSON',
    type:'get',
    success:function(res){
        $('title').html(res.site_name);
        $('#shop_name').html(res.shop_name);
        $('#user_name').html(res.user_name);
    }
});