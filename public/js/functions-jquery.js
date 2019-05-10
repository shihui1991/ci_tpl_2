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