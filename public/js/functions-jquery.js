$('head').append(
    '<link rel="stylesheet" href="/toastr/toastr.min.css">\n' +
    '<script src="/toastr/toastr.min.js"></script>\n'
);
// 提示消息配置
toastr.options = {
    closeButton: true,
    debug: false,
    progressBar: false,
    positionClass: "toast-top-right",
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "2000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

// 响应的通用提示消息
function noticeForResp(resp,obj) {
    if(!resp){
        toastr.error('未知错误');
    }
    else if(resp.code){
        toastr.warning(resp.msg);
    }
    else{
        if(resp.url){
            toastr.options.onHidden = function() {
                location.href = resp.url;
            }
        }
        toastr.success(resp.msg);
    }
    closeLoading();
    // 释放提交按钮
    $(obj).data('loading',false).val('');
}

// 删除操作成功后删除当前行
function callRemoveRowtr(resp,obj) {
    if(!resp){
        toastr.error('未知错误');
    }
    else if(resp.code){
        toastr.warning(resp.msg);
    }
    else{
        if(resp.url){
            toastr.options.onHidden = function() {
                location.href = resp.url;
            }
        }else{
            toastr.options.onHidden = function() {
                obj.parents('tr:first').remove();
            }
        }
        toastr.success(resp.msg);
    }
    closeLoading();
    // 释放提交按钮
    $(obj).data('loading',false).val('');
}

// 响应格式 {code,msg,data,url}
// ajax 数据保存
function ajaxData(url,data,type) {
    var callback = getCallback(arguments[3]);
    var obj = arguments[4];

    $.ajax({
        url:url
        , type:type
        , async:true
        , data:data
        , dataType:"json"
        , success:function(resp){
            callback(resp,obj);
        }
        , error:function () {
            callback(getNetErrorResp(),obj);
        }
    });
}

// ajax 上传文件
function ajaxFile(url,data) {
    var callback = getCallback(arguments[2]);
    var obj = arguments[3];
    $.ajax({
        url:url
        , data:data
        , type:'post'
        , dataType:'json'
        , async:true
        , cache: false
        , contentType: false
        , processData: false
        , success:function(resp){
            callback(resp,obj);
        }
        , error:function () {
            callback(getNetErrorResp(),obj);
        }
    });
}

// 获取ajax回调方法
function getCallback(arg) {
    var callback;
    if(arg){
        if('string' === typeof arg){
            callback = window[arg];
        }else{
            callback = arg;
        }
    }else{
        callback = noticeForResp;
    }

    return callback;
}
 // 获取网络异常错误数据
function getNetErrorResp() {
    return {
        "code":1
        ,"msg":"未知错误"
        ,"data":""
        ,"url":""
    };
}

// 按钮触发表单 ajax 提交
function btnFormAjaxSubmit(obj) {
    var type = 'get'; // 默认请求方式
    var btn = $(obj); // 按钮
    actBeforeBtnClick(obj); // 请求前的操作
    var callback = getCallbackForAfterBtnAct(obj);  // 响应回调
    var btnForm = btn.data('form'); // 按钮指定的表单
    var btnAction = btn.data('action'); // 按钮指定的提交地址
    var btnData = btn.data('data'); // 按钮绑定的特定数值
    var btnType = btn.data('type'); // 按钮指定的请求方式
    var btnConfirm = btn.data('confirm'); // 请求前提示
    var formObj = btnForm ? $(btnForm) : btn.parents('form:first'); // 表单
    var formType; // 表单指定的请求方式
    var data = ''; // 提交数据
    var url = btnAction; // 请求地址

    if(formObj.length){
        formType = formObj.attr('method');
        url = btnAction ? btnAction : formObj.attr('action');
        data = formObj.serialize();
    }
    // btn 数据
    if(btnData){
        if(data){
            data += '&' + btnData;
        }
        else{
            data = btnData;
        }
    }
    // 请求方式
    if(btnType){
        type = btnType;
    }
    else if(formType){
        type = formType;
    }
    // 禁止重复提交
    if(btn.data('loading')){
        return false;
    }
    // 防止重复提交
    btn.data('loading',true);
    // 提交提示
    var canDo=true;
    if(btnConfirm){
        if(false === confirm(btnConfirm)){
            canDo = false;
        }
    }
    if(canDo){
        ajaxData(url,data,type,callback,btn)
    }else{
        // 释放提交按钮
        btn.data('loading',false);
        closeLoading();
    }
}
// 按钮请求前的操作
function actBeforeBtnClick(obj) {
    var beforeAct = $(obj).data('before-act');
    if(beforeAct && window[beforeAct]){
        window[beforeAct](obj);
    }else{
        showLoading();
    }
}

// 获取按钮操作之后的回调
function getCallbackForAfterBtnAct(obj) {
    var afterAct = $(obj).data('after-act'); // 请求成功后的操作
    var callback;
    if(afterAct && window[afterAct]){
        callback = window[afterAct];
    }else{
        callback = function (resp) {
            toastr.info(resp.msg);
            closeLoading();
            // 释放提交按钮
            $(obj).data('loading',false).val('');
        }
    }

    return callback;
}

// 按钮触发上传文件
function btnUploadFile(obj) {
    var btn = $(obj); // 按钮
    actBeforeBtnClick(obj); // 请求前的操作
    var callback = getCallbackForAfterBtnAct(obj);  // 响应回调
    var url = btn.data('url'); // 上传地址
    var savepath = btn.data('savepath'); // 保存目录
    var overwrite = btn.data('overwrite'); // 是否同名覆盖
    var uploadname = btn.attr('name'); // 键名
    var files = obj.files; // 上传文件
    url = url ? url : '/sys/home/upload'; // 上传地址
    // 禁止重复提交
    if(btn.data('loading')){
        return false;
    }
    // 防止重复提交
    btn.data('loading',true);
    // 逐个上传
    if(files && files.length){
        $.each(files,function (i,file) {
            var formdata = new FormData();
            formdata.append('SavePath',savepath);
            formdata.append('SaveName',file.name);
            formdata.append('Overwrite',overwrite);
            formdata.append('UploadName',uploadname);
            formdata.append(uploadname,file);

            ajaxFile(url,formdata,callback,btn);
        })
    }
    // 释放提交按钮
    btn.data('loading',false).val('');
}

//表头 ==>> 全选、全不选
function allCheckOrCancel(obj){
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

// 生成选项DOM
function makeOptionDom(list) {
    var value = arguments[1] ? arguments[1] : undefined; // 选中的值
    var key = arguments[2] ? arguments[2] : 'ID'; // 数值字段名
    var title = arguments[3] ? arguments[3] : 'Name'; // 显示值字段名
    var dom='';
    $.each(list,function(i,row){
        var selected='';
        if(undefined !== value && value == row[key]){
            selected = ' selected';
        }
        dom +='<option value="'+ row[key] +'" '+ selected +'> '+ row[title] +' </option>';
    });

    return dom;
}

// 生成复选框DOM
function makeChekboxDom(list,name) {
    var value = arguments[2] ? arguments[2] : undefined; // 选中的值
    var key = arguments[3] ? arguments[3] : 'ID'; // 数值字段名
    var title = arguments[4] ? arguments[4] : 'Name'; // 显示值字段名
    var dom='';
    $.each(list,function(i,row){
        var checked='';
        if(undefined !== value && value == row[key]){
            checked = ' checked';
        }
        dom +='<input type="checkbox" name="'+name+'" value="'+row[key]+'" title="'+row[title]+'" '+checked+'>';
    });

    return dom;
}

// 获取下级分组
function getChilds(list,parentID) {
    var title = arguments[2] ? arguments[2] : 'ParentID';
    var childs = [];
    var other = [];
    $.each(list,function (i,row) {
        if(row[title] == parentID){
            childs.push(row);
        }else{
            other.push(row);
        }
    });

    return {
        childs:childs
        ,other:other
    };
}

// 生成树形选项DOM
function makeOptionTreeDom(list) {
    if(0 === list.length){
        return '';
    }
    var parentID = arguments[1] ? arguments[1] : 0; // 上级ID
    var level = arguments[2] ? arguments[2] : 1; // 层级
    var value = arguments[3] ? arguments[3] : undefined; // 选中的值
    var key = arguments[4] ? arguments[4] : 'ID'; // 数值字段名
    var title = arguments[5] ? arguments[5] : 'Name'; // 显示值字段名
    var group = getChilds(list,parentID); // 获取下级分组
    var num = group.childs.length;
    var dom = '';
    var icon = ['&nbsp;┃', '&nbsp;┣', '&nbsp;┗'];
    var nbsp = '&nbsp;';
    var n = 1;
    $.each(group.childs,function (i,row) {
        var space = '';
        for(var j = 1; j < level; j++){
            if(1 == j){
                space += nbsp;
            }else{
                space += icon[0]+nbsp;
            }
        }
        if(1 != level){
            if(n == num){
                space += icon[2];
            }else{
                space += icon[1];
            }
        }
        var selected='';
        if(value == row[key]){
            selected = ' selected ';
        }
        dom += '<option value="'+row[key]+'" '+selected+'> '+space+' '+row[title]+' </option>';
        dom += makeOptionTree(group.other,row[key],level+1,value,key,title);
        n ++;
    });

    return dom;
}

// 复制JSON 对象
function copyJson(obj) {
    return JSON.parse(JSON.stringify(obj));
}