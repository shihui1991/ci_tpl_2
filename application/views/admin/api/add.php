<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/api">接口列表</a>
                    </li>
                    <li class="layui-this">
                        添加接口
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/api/add" method="post" onsubmit="return false;">
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">接口URL：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Url" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">事件ID：</label>
                                <div class="layui-input-block">
                                    <input type="number" name="EventId" value="0" min="0" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="关闭">
                                    <input type="radio" name="State" value="1" title="开启" checked>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">请求参数：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th style="max-width: 120px"></th>
                                            <th width="120">参数</th>
                                            <th width="100">类型</th>
                                            <th width="80">必填/可选</th>
                                            <th width="200">名称</th>
                                            <th>说明</th>
                                            <th width="60"><button class="layui-btn layui-btn-sm" data-field="Request" data-id="0" type="button" onclick="addField(this)">添加</button></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">响应参数：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th style="max-width: 120px"></th>
                                            <th width="120">参数</th>
                                            <th width="80">类型</th>
                                            <th width="80">选项</th>
                                            <th width="200">名称</th>
                                            <th>说明</th>
                                            <th width="60"><button class="layui-btn layui-btn-sm" data-field="Response" data-id="0" type="button" onclick="addField(this)">添加</button></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item upload-content">
                                <label class="layui-form-label">
                                    响应示例：
                                    <a class="layui-btn layui-btn-warm layui-btn-sm btn-upload">
                                        点击上传
                                        <input type="file" accept="image/*" name="UploadFile" multiple data-field="Example[]" data-savepath="api" data-savename="" data-overwrite="true" onchange="uploadImg(this)">
                                    </a>
                                </label>
                                <div class="layui-input-block uploaded-box">
                                    <ul class="img-box">

                                    </ul>
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

<script type="text/html">
    <tr data-tt-id="" data-tt-parent-id="">
        <td></td>
        <td>
            <input type="text" name="Field[][VarName]" class="layui-input">
            <input type="hidden" name="Field[][Id]" value="">
            <input type="hidden" name="Field[][ParentId]" value="">
        </td>
        <td><input type="text" name="Field[][Type]" class="layui-input"></td>
        <td><input type="text" name="Field[][Required]" class="layui-input"></td>
        <td><input type="text" name="Field[][Name]" class="layui-input"></td>
        <td><input type="text" name="Field[][Infos]" class="layui-input"></td>
        <td>
            <div class="layui-btn-group">
                <button class="layui-btn layui-btn-xs layui-btn-normal" type="button" data-field="Response" data-id="" onclick="addField(this)" title="添加子级"><i class="layui-icon layui-icon-add-circle"></i></button>
                <button class="layui-btn layui-btn-xs layui-btn-danger" type="button" data-id="" onclick="removeField(this)" title="删除"><i class="layui-icon layui-icon-delete"></i></button>
            </div>
        </td>
    </tr>
</script>

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>

<link rel="stylesheet" href="/viewer/jquery-0.6.0/viewer.min.css">
<script src="/viewer/jquery-0.6.0/viewer.min.js"></script>
<script src="/js/upload-images.js"></script>

<script>
    // 添加字段
    var trId=0;
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

    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;
        //监听提交
        form.on('submit(formSubmit)', function(data){
            btnAct(data.elem);
            return false;
        });
    });

</script>