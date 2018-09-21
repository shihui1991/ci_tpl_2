<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/api">接口列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/api/add">添加接口</a>
                    </li>
                    <li class="layui-this">
                        修改
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/api/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id']?>">
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">接口URL：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Url" value="<?php echo $data['List']['Url'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">事件ID：</label>
                                <div class="layui-input-block">
                                    <input type="number" name="EventId" value="<?php echo $data['List']['EventId'];?>" min="0" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="关闭" <?php if(0 == $data['List']['State']){echo ' checked';}?>>
                                    <input type="radio" name="State" value="1" title="开启" <?php if(1 == $data['List']['State']){echo ' checked';}?>>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">请求参数：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th width="120"></th>
                                            <th width="150">参数</th>
                                            <th width="100">类型</th>
                                            <th width="80">必填/可选</th>
                                            <th width="200">名称</th>
                                            <th>说明</th>
                                            <th width="60"><button class="layui-btn layui-btn-sm" data-field="Request" data-id="0" type="button" onclick="addField(this)">添加</button></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $id=0; ?>
                                        <?php $requestList=$data['List']['Request']; ?>
                                        <?php if(!empty($requestList)): ?>
                                            <?php foreach($requestList as $k=>$request):?>
                                                <?php $id=$request['Id']>$id?$request['Id']:$id; ?>
                                                <tr data-tt-id="<?php echo $request['Id'];?>" data-tt-parent-id="<?php echo $request['ParentId'];?>">
                                                    <td><?php echo $request['ParentId'];?>-<?php echo $request['Id'];?></td>
                                                    <td>
                                                        <input type="text" name="Request[<?php echo $request['Id'];?>][VarName]" value="<?php echo $request['VarName'];?>" class="layui-input">
                                                        <input type="hidden" name="Request[<?php echo $request['Id'];?>][Id]" value="<?php echo $request['Id'];?>">
                                                        <input type="hidden" name="Request[<?php echo $request['Id'];?>][ParentId]" value="<?php echo $request['ParentId'];?>">
                                                    </td>
                                                    <td><input type="text" name="Request[<?php echo $request['Id'];?>][Type]" value="<?php echo $request['Type'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Request[<?php echo $request['Id'];?>][Required]" value="<?php echo $request['Required'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Request[<?php echo $request['Id'];?>][Name]" value="<?php echo $request['Name'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Request[<?php echo $request['Id'];?>][Infos]" value="<?php echo $request['Infos'];?>" class="layui-input"></td>
                                                    <td>
                                                        <div class="layui-btn-group">
                                                            <button class="layui-btn layui-btn-xs layui-btn-normal" type="button" data-field="Response" data-id="<?php echo $request['Id'];?>" onclick="addField(this)" title="添加子级"><i class="layui-icon layui-icon-add-circle"></i></button>
                                                            <button class="layui-btn layui-btn-xs layui-btn-danger" type="button" data-id="<?php echo $request['Id'];?>" onclick="removeField(this)" title="删除"><i class="layui-icon layui-icon-delete"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
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
                                            <th width="120"></th>
                                            <th width="150">参数</th>
                                            <th width="80">类型</th>
                                            <th width="80">选项</th>
                                            <th width="200">名称</th>
                                            <th>说明</th>
                                            <th width="60"><button class="layui-btn layui-btn-sm" data-field="Response" data-id="0" type="button" onclick="addField(this)">添加</button></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $responseList=$data['List']['Response']; ?>
                                        <?php if(!empty($responseList)): ?>
                                            <?php foreach($responseList as $i=>$response):?>
                                                <?php $id=$response['Id']>$id?$response['Id']:$id; ?>
                                                <tr data-tt-id="<?php echo $response['Id'];?>" data-tt-parent-id="<?php echo $response['ParentId'];?>">
                                                    <td><?php echo $response['ParentId'];?>-<?php echo $response['Id'];?></td>
                                                    <td>
                                                        <input type="text" name="Response[<?php echo $response['Id'];?>][VarName]" value="<?php echo $response['VarName'];?>" class="layui-input">
                                                        <input type="hidden" name="Response[<?php echo $response['Id'];?>][Id]" value="<?php echo $response['Id'];?>">
                                                        <input type="hidden" name="Response[<?php echo $response['Id'];?>][ParentId]" value="<?php echo $response['ParentId'];?>">
                                                    </td>
                                                    <td><input type="text" name="Response[<?php echo $response['Id'];?>][Type]" value="<?php echo $response['Type'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Response[<?php echo $response['Id'];?>][Required]" value="<?php echo $response['Required'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Response[<?php echo $response['Id'];?>][Name]" value="<?php echo $response['Name'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Response[<?php echo $response['Id'];?>][Infos]" value="<?php echo $response['Infos'];?>" class="layui-input"></td>
                                                    <td>
                                                        <div class="layui-btn-group">
                                                            <button class="layui-btn layui-btn-xs layui-btn-normal" type="button" data-field="Response" data-id="<?php echo $response['Id'];?>" onclick="addField(this)" title="添加子级"><i class="layui-icon layui-icon-add-circle"></i></button>
                                                            <button class="layui-btn layui-btn-xs layui-btn-danger" type="button" data-id="<?php echo $response['Id'];?>" onclick="removeField(this)" title="删除"><i class="layui-icon layui-icon-delete"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item upload-content">
                                <label class="layui-form-label">
                                    响应示例：
                                    <a class="layui-btn layui-btn-warm layui-btn-sm btn-upload">
                                        点击上传
                                        <input type="file" accept="image/*" name="UploadFile" multiple data-field="Example[]" data-savepath="api" data-overwrite="true" onchange="uploadImg(this)">
                                    </a>
                                </label>
                                <div class="layui-input-block uploaded-box">
                                    <ul class="img-box">
                                        <?php if(!empty($data['List']['Example'])):?>
                                            <?php foreach($data['List']['Example'] as $img):?>
                                                <li style="display: inline-block;margin:10px;">
                                                    <div style="width:300px;height:200px;">
                                                        <img style="max-width:250px;max-height:200px;" src="<?php echo $img;?>" alt="">
                                                        <div class="text">
                                                            <div class="inner">
                                                                <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="viewPic(this)">查看</a>
                                                                <a class="layui-btn layui-btn-xs layui-btn-warm" onclick="removePic(this)">删除</a>
                                                            </div>
                                                        </div>
                                                        <input type="text" name="Example[]" value="<?php echo $img;?>" placeholder="" readonly  class="layui-input">
                                                    </div>
                                                </li>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"><?php echo $data['List']['Infos'];?></textarea>
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

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>

<link rel="stylesheet" href="/viewer/jquery-0.6.0/viewer.min.css">
<script src="/viewer/jquery-0.6.0/viewer.min.js"></script>
<script src="/js/upload-images.js"></script>

<script>
    // 添加字段
    var trId=<?php echo $id;?>;
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
        expandable: true
        , initialState: "expanded"
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