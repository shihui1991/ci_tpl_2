// 输入云地址实时预览
$('#Cloud').on('change',function () {
    var src=$(this).val();
    var img=$('#CloudImg');
    img.attr('src',src);
    if(src){
        img.css('display','');
    }else{
        img.css('display','none');
    }
});