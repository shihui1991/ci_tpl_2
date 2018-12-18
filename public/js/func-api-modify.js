function addField(obj) {
    var btn=$(obj);
    var id=btn.data('id');
    var field=btn.data('field');
    var tr=btn.parents('tr:first');
    var table=btn.parents('table:first');
    var dom='';
    var curId=trId+1;
    var parId=id;
    var parentTr=table.treetable("node", parId);

    dom +='<tr data-tt-id="'+curId+'" data-tt-parent-id="'+parId+'">' +
        '    <td>'+parId+'-'+curId+'</td>' +
        '    <td>' +
        '        <input type="text" name="'+field+'['+curId+'][VarName]" class="layui-input">' +
        '        <input type="hidden" name="'+field+'['+curId+'][Id]" value="'+curId+'">' +
        '        <input type="hidden" name="'+field+'['+curId+'][ParentId]" value="'+parId+'">' +
        '    </td>' +
        '    <td><input type="text" name="'+field+'['+curId+'][Type]" class="layui-input"></td>' +
        '    <td><input type="text" name="'+field+'['+curId+'][Required]" class="layui-input"></td>' +
        '    <td><input type="text" name="'+field+'['+curId+'][Name]" class="layui-input"></td>' +
        '    <td><input type="text" name="'+field+'['+curId+'][Infos]" class="layui-input"></td>' +
        '    <td>' +
        '        <div class="layui-btn-group">' +
        '            <button class="layui-btn layui-btn-xs layui-btn-normal" type="button" data-field="'+field+'" data-id="'+curId+'" onclick="addField(this)" title="添加子级"><i class="layui-icon layui-icon-add-circle"></i></button>' +
        '            <button class="layui-btn layui-btn-xs layui-btn-danger" type="button" data-id="'+curId+'" onclick="removeField(this)" title="删除"><i class="layui-icon layui-icon-delete"></i></button>' +
        '        </div>' +
        '    </td>' +
        '</tr>';

    table.treetable("loadBranch", parentTr, dom);// 插入子节点
    if(parentTr){
        table.treetable("expandNode", parentTr.id);// 展开子节点
    }

    layui.use(['form'], function(){
        var form = layui.form;

        form.render();
    });
    trId++;

}

$(".treetable").treetable({
    expandable: true // 展示
    , initialState: "collapsed"
    , stringCollapse: '关闭'
    , stringExpand: '展开'
});

// 删除字段
function removeField(obj) {
    var btn=$(obj);
    var id=btn.data('id');
    var table=btn.parents('table:first');

    table.treetable("removeNode", id);
}