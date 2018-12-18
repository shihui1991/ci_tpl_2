layui.use(['form','layer'], function(){
    var form = layui.form;
    var layer = layui.layer;

    ajaxSubmit('/admin/role/all',{},'get');
    if(!ajaxResp || "undefined" === typeof ajaxResp){
        layer.msg('网络开小差了',{icon:5});
    }else{
        if(ajaxResp.code){
            layer.msg(ajaxResp.msg,{icon:2});
        }
        else{
            var dom = makeOptionTree(ajaxResp.data.List,0,1,'Id','Name',curRoleId);
            $('#RoleId').append(dom);
            form.render();
        }
    }
});