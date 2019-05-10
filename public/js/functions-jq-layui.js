// 通用ajax回调方法
function comAjaxCallback(resp,obj)
{
    if(obj){
        obj.data('loading',false);
    }
    closeLayer('loading');
    if(resp.code){
        alertMsg(resp.msg,5);
        return false;
    }
    alertMsg(resp.msg,0,1000,function () {
        if(resp.url){
            location.href = resp.url;
        }else{
            if(obj.data('keep')){
                return false;
            }
            location.reload();
        }
    });
}

// 监听表单提交
layui.use(['form','layer'], function(){
    var form = layui.form;

    form.on('submit(btnFormSubmit)', function(data){
        btnFormAjaxRequest(data.elem);
        return false;
    });
});

// 按钮表单ajax请求
function btnFormAjaxRequest(btn)
{
    var btnObj = $(btn);
    var msg = btnObj.data('msg');
    var yes = function (index) {
        if(index){
            closeLayerIndex(index);
        }
        if(btnObj.data('loading')){
            return false;
        }
        openLoading();
        btnObj.data('loading',true);
        var formObj = btnObj.data('form') ? $(btnObj.data('form')) : btnObj.parents('form:first');
        var url = btnObj.data('url') ? btnObj.data('url') : formObj.attr('action');
        var type = btnObj.data('type') ? btnObj.data('type') : (formObj.attr('method') ? formObj.attr('method') : 'get');
        var data = btnObj.data('data') ? btnObj.data('data') : formObj.serialize();
        var callback = btnObj.data('callback') ? window[btnObj.data('callback')] : comAjaxCallback;
        ajaxRequest(url,type,data,callback,btnObj);
    };
    if(msg){
        alertConfirm(msg,yes);
    }else{
        yes();
    }
}

var uploadFileNum = 0;
var uploadFilesURL = [];
// 上传文件
function uploadFile(input)
{
    var files = input.files;
    if( ! files || 0 === files.length){
        alertMsg('请先选择上传文件');
        return false;
    }
    var obj = $(input);
    var url = obj.data('url') ? obj.data('url') : '/admin/home/upload';
    var savepath = obj.data('savepath');
    var overwrite = obj.data('overwrite');
    var name = obj.data('name');
    var callback;
    if(obj.data('callback')){
        callback = window[obj.data('callback')];
    }else{
        callback = function (resp,inputObj) {
            uploadFileNum ++;
            if(resp.code){
                alertMsg(inputObj.get(0).files[uploadFileNum].name + ' 上传失败',5);
            }else{
                uploadFilesURL.push(resp.data.FileUrl);
            }
            if(uploadFileNum === inputObj.get(0).files.length){
                var doneFunc = inputObj.data('done');
                var done;
                if(doneFunc){
                    done = window[doneFunc];
                }else{
                    done = function (urls) {
                        location.reload();
                    };
                }
                alertMsg('上传完成',1);
                done(uploadFilesURL,inputObj);
                closeLayer('loading');
                inputObj.data('loading',false).val('');
                uploadFileNum = 0;
                uploadFilesURL = [];
            }
        }
    }
    
    if(obj.data('loading')){
        return false;
    }
    openLoading();
    obj.data('loading',true);
    
    $.each(files,function (i,file) {
        var formdata = new FormData();
        formdata.append('SavePath',savepath);
        formdata.append('SaveName',file.name);
        formdata.append('Overwrite',overwrite);
        formdata.append('UploadName',name);
        formdata.append(name,file);

        ajaxUploadFile(url,formdata,callback,obj)
    })
}

// 删除操作成功后删除当前行
function callRemoveRowtr(resp,obj)
{
    if(obj){
        obj.data('loading',false);
    }
    closeLayer('loading');
    if(resp.code){
        alertMsg(resp.msg,5);
        return false;
    }
    alertMsg(resp.msg,0,1000,function () {
        if(resp.url){
            location.href = resp.url;
        }else{
            obj.parents('tr:first').remove();
        }
    });
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

// 复制JSON 对象
function copyJson(obj) {
    return JSON.parse(JSON.stringify(obj));
}

// 生成选项DOM
function makeOptionDom(list,val,valKey,titKey)
{
    valKey = valKey ? valKey : 'Id';
    titKey = titKey ? titKey : 'Name';
    var dom = '';
    $.each(list,function(i,row){
        var selected='';
        if(undefined !== val && val == row[valKey]){
            selected = ' selected';
        }
        dom +='<option value="'+ row[valKey] +'" '+ selected +'> '+ row[titKey] +' </option>';
    });

    return dom;
}

// 生成复选框DOM
function makeChekboxDom(list,name,val,valKey,titKey) {
    valKey = valKey ? valKey : 'Id';
    titKey = titKey ? titKey : 'Name';
    var dom = '';
    $.each(list,function(i,row){
        var checked='';
        if(undefined !== val && val == row[valKey]){
            checked = ' checked';
        }
        dom +='<input type="checkbox" name="'+name+'" value="'+row[valKey]+'" title="'+row[titKey]+'" '+checked+'>';
    });

    return dom;
}

// 获取下级分组
function getChilds(list,parentId,key) {
    key = key ? key : 'ParentId';
    var childs = [];
    var other = [];
    $.each(list,function (i,row) {
        if(row[key] === parentId){
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
function makeOptionTreeDom(list,parentId,level,val,key,titKey,parentKey) {
    if(0 === list.length){
        return '';
    }
    parentId = parentId ? parentId : 0; // 上级ID
    level = level ? level : 1; // 层级
    key = key ? key : 'Id'; // 数值字段名
    titKey = titKey ? titKey : 'Name'; // 显示值字段名
    var group = getChilds(list,parentId,parentKey); // 获取下级分组
    var num = group.childs.length;
    var dom = '';
    var icon = ['&nbsp;┃', '&nbsp;┣', '&nbsp;┗'];
    var nbsp = '&nbsp;';
    var n = 1;
    $.each(group.childs,function (i,row) {
        var space = '';
        for(var j = 1; j < level; j++){
            if(1 === j){
                space += nbsp;
            }else{
                space += icon[0] + nbsp;
            }
        }
        if(1 !== level){
            if(n === num){
                space += icon[2];
            }else{
                space += icon[1];
            }
        }
        var selected='';
        if(val == row[key]){
            selected = ' selected ';
        }
        dom += '<option value="'+row[key]+'" '+selected+'> '+space+' '+row[titKey]+' </option>';
        dom += makeOptionTreeDom(group.other,row[key],level+1,val,key,titKey,parentKey);
        n ++;
    });

    return dom;
}