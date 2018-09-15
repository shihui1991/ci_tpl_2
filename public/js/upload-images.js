// 图片预览初始化
if($('.img-box').find('li').length){
    $('.img-box').viewer();
}

// 图片预览
function viewPic(obj) {
    var that=$(obj);
    that.parents('li:first').find('img:first').click();
}

// 删除图片
function removePic(obj) {
    var that=$(obj);
    var imbBox=that.parents('.img-box:first');
    var li=that.parents('li:first');
    var index=li.index();

    li.remove();
    imbBox.viewer('update');
}

// 上传图片后显示模板
var tpl='<div class="layui-form-item upload-content">' +
    '    <label class="layui-form-label">' +
    '        图标：' +
    '        <button class="layui-btn layui-btn-sm" type="button" onclick="uploadBtn(this)">' +
    '            点击上传' +
    '        </button>' +
    '        <input type="file" accept="image/*" name="UploadFile" data-field="" data-savepath="" data-savename="" data-overwrite="true" onchange="upload(this)" style="display: none;">' +
    '    </label>' +
    '    <div class="layui-input-block uploaded-box">' +
    '        <ul class="img-box">' +
    '            <li style="display: inline-block;margin:10px;">' +
    '                <div style="width:300px;height:200px;">' +
    '                    <img style="max-width:250px;max-height:200px;" src="" alt="">' +
    '                    <div class="text">' +
    '                        <div class="inner">' +
    '                            <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="viewPic(this)">查看</a>' +
    '                            <a class="layui-btn layui-btn-xs layui-btn-warm" onclick="removePic(this)">删除</a>' +
    '                        </div>' +
    '                    </div>' +
    '                    <input type="text" name="Icon" value="" placeholder="" readonly  class="layui-input">' +
    '                </div>' +
    '            </li>' +
    '        </ul>' +
    '    </div>' +
    '</div>';

 // 上传
function uploadImg(obj) {
    var btn=$(obj);
    var uploadContent=btn.parents('.upload-content:first');
    var uploadedBox=uploadContent.find('.uploaded-box');
    var imbBox=uploadContent.find('.img-box');
    var field=btn.data('field');
    var multi=btn.prop('multiple');
    var img='';

    var fileUrls=uploadFile(obj);
    $.each(fileUrls,function (i,url) {
        img+='<li style="display: inline-block;margin:10px;">' +
            '    <div style="width:300px;height:200px;">' +
            '        <img style="max-width:250px;max-height:200px;" src="'+url+'" alt="">' +
            '        <div class="text">' +
            '            <div class="inner">' +
            '                <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="viewPic(this)">查看</a>' +
            '                <a class="layui-btn layui-btn-xs layui-btn-warm" onclick="removePic(this)">删除</a>' +
            '            </div>' +
            '        </div>' +
            '        <input type="text" name="'+field+'" value="'+url+'" placeholder="" readonly  class="layui-input">'+
            '    </div>' +
            '</li>';
    });

    if(multi){
        imbBox.append(img);
    }else{
        imbBox.html(img);
    }
    imbBox.viewer('update');


    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;

        form.render();
    });
}