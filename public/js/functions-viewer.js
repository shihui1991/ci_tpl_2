$('head').append(
    '<link rel="stylesheet" href="/viewer/jquery-0.6.0/viewer.min.css">\n' +
    '<script src="/viewer/jquery-0.6.0/viewer.min.js"></script>'
);

// 图片预览初始化
if($('.img-box').find('li').length){
    $('.img-box').viewer();
}

// 图片预览
function viewPic(obj) {
    $(obj).parents('li:first').find('img:first').click();
}

// 删除图片
function removePic(obj) {
    var btn = $(obj);
    btn.parents('li:first').remove();
    btn.parents('.img-box:first').viewer('update');
}

// 显示图片
function makeImageViewDom(resp,btn) {
    var contentObj = btn.parents('.upload-content:first');
    var imbBoxObj = contentObj.find('.img-box');
    var field = btn.data('field');
    var multi = btn.prop('multiple');
    var url = resp.data.FileUrl;
    var dom ='<li class="layui-col-xs3">' +
        '         <div style="max-width:300px;max-height:200px;">' +
        '             <img style="max-width:300px;max-height:150px;" src="'+url+'" alt="">' +
        '             <i class="layui-icon layui-icon-close-fill btn-remove-image" onclick="removePic(this)" title="删除"></i>' +
        '             <input type="text" name="'+field+'" value="'+url+'" placeholder="" readonly  class="layui-input">'+
        '         </div>' +
        '     </li>';

    if(multi){
        imbBoxObj.append(dom);
    }else{
        imbBoxObj.html(dom);
    }
    imbBoxObj.viewer('update');
    closeLoading();
}