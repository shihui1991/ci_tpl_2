<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/config">配置列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/file">配置文件</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/config/add" method="post" onsubmit="return false;">

                            <div class="layui-form-item">
                                <label class="layui-form-label">表名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Table" value="" required  lay-verify="required" placeholder="" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="" required  lay-verify="required" placeholder="" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">字段详情：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th width="120">字段</th>
                                            <th>字段名</th>
                                            <th>字段映射</th>
                                            <th width="100">属性</th>
                                            <th>属性描述</th>
                                            <th>验证规则</th>
                                            <th><a class="layui-btn layui-btn-xs layui-btn-normal" onclick="addField(this);">添加字段</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="formSubmit">保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;
        //监听提交
        form.on('submit(formSubmit)', function(data){
            btnAct(data.elem);
            return false;
        });
    });
    var index=0;
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
        var trIndex=tr.index();console.log(tr);
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

</script>