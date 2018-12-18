// 添加字段
function addField(obj) {
    var btn=$(obj);
    var table=btn.parents('table:first');
    var tbody=table.find('tbody');
    var dom='';

    dom += '<tr>' +
        '    <td><input type="text" name="Columns['+index+'][field]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="Columns['+index+'][name]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="Columns['+index+'][alias]" value="" class="layui-input"></td>' +
        '    <td>' +
        '        <select name="Columns['+index+'][attr]" class="layui-input">' +
        '            <option value="int"> int </option>' +
        '            <option value="float"> float </option>' +
        '            <option value="double"> double </option>' +
        '            <option value="string"> string </option>' +
        '            <option value="array"> array </option>' +
        '            <option value="json"> json </option>' +
        '            <option value="date"> date </option>' +
        '            <option value="datetime"> datetime </option>' +
        '        </select>' +
        '    </td>' +
        '    <td><textarea name="Columns['+index+'][desc]" class="layui-textarea"></textarea></td>' +
        '    <td><textarea name="Columns['+index+'][rules]" class="layui-textarea"></textarea></td>' +
        '    <td>' +
        '        <div class="layui-btn-group">' +
        '             <a class="layui-btn layui-btn-xs layui-btn-danger" onclick="removeField(this);">删除</a>' +
        '             <a class="layui-btn layui-btn-xs layui-btn-primary" onclick="moveUp(this);" title="上移">上移</a>' +
        '             <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="moveDown(this);" title="下移">下移</a>' +
        '        </div>' +
        '    </td>' +
        '</tr>';
    index++;
    tbody.append(dom);

    layui.use(['form'], function(){
        var form = layui.form;

        form.render();
    });
}
// 删除字段
function removeField(obj) {
    $(obj).parents('tr:first').remove();
}
// 上移
function moveUp(obj) {
    var tr=$(obj).parents('tr:first');
    var tbody=tr.parents('tbody:first');
    var trIndex=tr.index();
    if(trIndex){
        tr.prev().before(tr.get());
    }
}
// 下移
function moveDown(obj) {
    var tr=$(obj).parents('tr:first');
    var tbody=tr.parents('tbody:first');
    var trIndex=tr.index();
    var trs=tbody.children('tr');
    if(trIndex !== (trs.length-1)){
        tr.next().after(tr.get());
    }
}

// 添加数据库配置
function addDBConf(obj) {
    var btn=$(obj);
    var table=btn.parents('table:first');
    var tbody=table.find('tbody');
    var dom='';

    dom += '<tr>' +
        '    <td><input type="text" name="DBConf['+index+'][type]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="DBConf['+index+'][dbConfigName]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="DBConf['+index+'][db]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="DBConf['+index+'][table]" value="" class="layui-input"></td>' +
        '    <td><input type="text" name="DBConf['+index+'][primaryKey]" value="" class="layui-input" placeholder="英文逗号（,）隔开"></td>' +
        '    <td>' +
        '        <div class="layui-btn-group">' +
        '             <a class="layui-btn layui-btn-xs layui-btn-danger" onclick="removeField(this);">删除</a>' +
        '        </div>' +
        '    </td>' +
        '</tr>';
    index++;
    tbody.append(dom);

    layui.use(['form'], function(){
        var form = layui.form;

        form.render();
    });
}