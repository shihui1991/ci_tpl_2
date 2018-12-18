layui.use(['form','layer'], function(){
    var form = layui.form;
    var layer = layui.layer;

    if(ParentId){
        ajaxSubmit('/admin/role/info',{Id:ParentId},'get');
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

    // 获取菜单
    ajaxSubmit('/admin/menu/all',{},'get');
    if(!ajaxResp || "undefined" === typeof ajaxResp){
        layer.msg('网络开小差了',{icon:5});
    }else{
        if(ajaxResp.code){
            layer.msg(ajaxResp.msg,{icon:2});
        }
        else{
            var dom=makeTableTree(ajaxResp.data.List,0);
            $('#menu-ids').append(dom);
            $(".treetable").treetable({
                expandable: true // 展示
                ,initialState :"expanded"//默认打开所有节点
                ,stringCollapse:'关闭'
                ,stringExpand:'展开'
                ,clickableNodeNames: true
                ,column: 1
            });
            form.render();
        }
    }

});

function makeTableTree(dataList,parentId) {
    if(0 == dataList.length){
        return '';
    }
    var group=getChilds(dataList,parentId);
    var dom='';

    $.each(group.childs,function (i,data) {
        if(1 == data.Ctrl){
            var checked='';
            var index=$.inArray(data.Id,curMenuIds);
            if(curMenuIds.length > 0 && index > -1){
                checked='checked';
            }
            if(1 == isAdmin){
                checked='';
            }
            dom +='<tr data-tt-id="'+data.Id+'" data-tt-parent-id="'+data.ParentId+'">' +
                '    <td>'+data.Id+'</td>' +
                '    <td>'+data.Name+'</td>' +
                '    <td>'+data.Url+'</td>' +
                '    <td><input type="checkbox" name="MenuIds[]" value="'+data.Id+'" id="id-'+data.Id+'" data-id="'+data.Id+'" data-parent-id="'+data.ParentId+'" onclick="upDown(this)" lay-ignore '+checked+'></td>' +
                '</tr>';
            dom += makeTableTree(group.other,data.Id)
        }
    });
    return dom;
}