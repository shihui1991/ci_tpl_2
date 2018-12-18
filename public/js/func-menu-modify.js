
layui.use(['form','layer'], function(){
    var form = layui.form;
    var layer = layui.layer;

    if(ParentId){
        ajaxSubmit('/admin/menu/info',{Id:ParentId},'get');
        if(!ajaxResp || "undefined" === typeof ajaxResp){
            layer.msg('网络开小差了',{icon:5});
        }else{
            if(ajaxResp.code){
                layer.msg(ajaxResp.msg,{icon:2});
            }
            else{
                var dom='<input type="text" value="'+ajaxResp.data.List.Name+'" class="layui-input" readonly>';
                $('#ParentId').append(dom);
                form.render();
            }
        }
    }

});