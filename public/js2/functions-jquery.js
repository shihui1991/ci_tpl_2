// ajax 请求
function ajaxRequest(url,type,data,callback,jqObj,args)
{
    if( !type){
        type = 'get';
    }
    var options = {
        url:url ,
        type:type ,
        async:true ,
        cache: false ,
        data:data ,
        dataType:"json" ,
        success:function(resp){
            callback(resp,jqObj);
        } ,
        error:function () {
            var resp = {
                "data": '',
                "code": 999,
                "msg": "未知错误",
                "url": ""
            };
            callback(resp,jqObj);
        } ,
    };
    if(args){
        $.extend(options,args);
    }
    $.ajax(options);
}

// ajax 文件上传
function ajaxUploadFile(url,data,callback,jqObj,args)
{
    var options = {
        contentType: false ,
        processData: false ,
    };
    if(args){
        $.extend(options,args);
    }
    ajaxRequest(url,'post',data,callback,jqObj,options);
}

// 通用ajax回调方法
function comAjaxCallback(resp,obj)
{
    if(obj){
        obj.data('loading',false).val('');
    }
    if(resp.code){
        alert(resp.msg);
        return false;
    }
    alert(resp.msg);
    if(resp.url){
        location.href = resp.url;
    }else{
        if(obj.data('keep')){
            return false;
        }
        location.reload();
    }
}

// 按钮表单ajax请求
function btnFormAjaxRequest(btn)
{
    var btnObj = $(btn);
    var msg = btnObj.data('msg');
    if(msg && !confirm(msg)){
        return false;
    }
    if(btnObj.data('loading')){
        return false;
    }
    btnObj.data('loading',true);
    var formObj = btnObj.data('form') ? $(btnObj.data('form')) : btnObj.parents('form:first');
    var url = btnObj.data('url') ? btnObj.data('url') : formObj.attr('action');
    var type = btnObj.data('type') ? btnObj.data('type') : (formObj.attr('method') ? formObj.attr('method') : 'get');
    var data = btnObj.data('data') ? btnObj.data('data') : formObj.serialize();
    var callback = btnObj.data('callback') ? window[btnObj.data('callback')] : comAjaxCallback;
    ajaxRequest(url,type,data,callback,btnObj);
}


//表头 ==>> 全选、全不选
function allCheckOrCancel(obj)
{
    var checked = $(obj).prop('checked');
    var allTbodyCheckboxObj = $(obj).parents('table:first').find('input[type=checkbox]');
    if(checked){
        allTbodyCheckboxObj.prop('checked',true);
    }else{
        allTbodyCheckboxObj.prop('checked',false);
    }
}

// 树形列表复选框 选中冒泡与取消捕获
function upDown(obj) {
    var rowObj = $(obj), // 当前行
        rowId = rowObj.data('id'), // 当前行ID
        rowParentId = rowObj.data('parent-id'), // 当前行上级ID
        parentObj = $('#id-'+rowParentId), // 当前行上级
        childObj = $('input[data-parent-id=' + rowId + ']'); // 当前行下级
    // 当前行选中 冒泡
    if(rowObj.prop('checked') && rowParentId){
        parentObj.prop("checked", true);
        upDown(parentObj);
    }
    // 当前行取消 捕获
    else if(!rowObj.prop('checked') && childObj.length){
        $.each(childObj,function (i,child) {
            $(child).prop('checked',false);
            upDown($(child));
        })
    }
}