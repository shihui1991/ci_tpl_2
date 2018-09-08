// ajax提交
function ajaxSubmit(url,data,type) {
    $.ajax({
        url:url
        ,type:type
        ,async:false
        ,data:data
        ,dataType:"json"
        ,success:function(resp){
            ajaxResp=resp;
        }
        ,error:function () {
            ajaxResp=null;
        }
    });
}

// ajax 上传文件
function ajaxUpload(url,data) {
    $.ajax({
        url:url,
        data:data,
        type:'post',
        dataType:'json',
        async:false,
        cache: false,
        contentType: false,
        processData: false,
        success:function (resp) {
            ajaxResp=resp;
        },
        error:function (resp) {
            ajaxResp=null;
        }
    });
}


// btn 表单提交
function btnFormSubmit(obj) {
    var type='get';
    var btn=$(obj);
    var btnForm=btn.data('form');
    var btnAction=btn.data('action');
    var btnData=btn.data('data');
    var btnType=btn.data('type');
    var btnConfirm=btn.data('confirm');
    var formObj=btnForm?$(btnForm):btn.parents('form:first');
    var formType=formObj.attr('method');
    var url=btnAction?btnAction:formObj.attr('action');
    var data=formObj.serialize();
    // btn 数据
    if(btnData){
        if(data){
            data += '&'+btnData;
        }
        else{
            data = btnData;
        }
    }
    // 提交类型
    if(btnType){
        type=btnType;
    }
    else if(formType){
        type=formType;
    }
    // 禁止重复提交
    if(btn.data('loading') || btn.prop('disabled') || btn.hasClass('disabled')){
        return false;
    }
    // 防止重复提交
    btn.data('loading',true).prop('disabled',true).addClass('disabled');
    // 提交提示
    if(btnConfirm){
        if(false === confirm(btnConfirm)){
            return false;
        }
    }
    ajaxSubmit(url,data,type);
    // 释放提交按钮
    btn.data('loading',false).prop('disabled',false).removeClass('disabled');
    // 阻止表单默认行为
    return false;
}

// btn 操作
function btnAct(obj) {
    layui.use(['layer'], function(){
        var layer = layui.layer;
        var loading=layer.load();

        btnFormSubmit(obj);

        layer.close(loading);

        if(!ajaxResp || "undefined" === typeof ajaxResp){
            return false;
        }

        if(ajaxResp.code){
            layer.msg(ajaxResp.msg,{icon:2});
        }
        else{
            layer.msg(ajaxResp.msg,{icon:1,time:1000},function () {
                if(ajaxResp.url) {
                    location.href=ajaxResp.url;
                }
                else{
                    location.reload();
                }
            });

        }
    });
}

// 增加 option
function appendOpt(url,post,type,value,id) {
    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;
        var key='Id'; // 关键字段
        var val='Name'; // 显示字段

        if(arguments[5]){
            key=arguments[5];
        }
        if(arguments[6]){
            val=arguments[6];
        }

        ajaxSubmit(url,post,type);
        if(!ajaxResp || "undefined" === typeof ajaxResp){
            layer.msg('网络开小差了',{icon:5});
        }else{
            if(ajaxResp.code){
                layer.msg(ajaxResp.msg,{icon:2});
            }
            else{
                if(ajaxResp.data.List.length>0){
                    var dom='';
                    $.each(ajaxResp.data.List,function(i,data){
                        var selected='';
                        if(value == data[key]){
                            selected='selected';
                        }
                        dom +='<option value="'+data[key]+'" '+selected+'> '+data[val]+' </option>';
                    });
                    $('#'+id).append(dom);
                    form.render();
                }

            }
        }
    });
}

// 增加 checkbox
function appendCheckbox(url,post,type,values,id,name) {
    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;
        var key='Id'; // 关键字段
        var val='Name'; // 显示字段

        if(arguments[6]){
            key=arguments[6];
        }
        if(arguments[7]){
            val=arguments[7];
        }

        ajaxSubmit(url,post,type);
        if(!ajaxResp || "undefined" === typeof ajaxResp){
            layer.msg('网络开小差了',{icon:5});
        }else{
            if(ajaxResp.code){
                layer.msg(ajaxResp.msg,{icon:2});
            }
            else{
                if(ajaxResp.data.List.length>0){
                    var dom='';
                    $.each(ajaxResp.data.List,function(i,data){
                        var checked='';
                        if($.inArray(data[key],values) > -1){
                            checked='checked';
                        }
                        dom +='<input type="checkbox" name="'+name+'" value="'+data[key]+'" title="'+data[val]+'" '+checked+'>';
                    });

                    $('#'+id).append(dom);
                    form.render();
                }

            }
        }
    });
}

// 初始化 时间插件
function renderDate() {
    layui.use(['form','laydate'], function() {
        var form = layui.form;
        var laydate = layui.laydate;

        var laydates = $('body').find('.laydate');
        $.each(laydates, function (i, obj) {
            var type = $(obj).data('type');
            var index = $.inArray(type, ['time', 'date', 'datetime', 'month', 'year']);
            if (index > -1) {
                laydate.render({
                    elem: obj
                    , type: type
                });
            }
        });

        form.render();
    });
}