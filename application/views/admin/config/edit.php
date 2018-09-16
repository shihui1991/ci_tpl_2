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
                        修改
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/config/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id']?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">表名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Table" value="<?php echo $data['List']['Table'];?>" readonly class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">字段详情：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th>字段</th>
                                            <th>字段名</th>
                                            <th>字段映射</th>
                                            <th>属性</th>
                                            <th>属性描述</th>
                                            <th>验证规则</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($data['List']['Columns'])):?>
                                            <?php foreach($data['List']['Columns'] as $column):?>
                                                <tr>
                                                    <td><?php echo $column['field'];?></td>
                                                    <td><?php echo $column['name'];?></td>
                                                    <td><?php echo $column['alias'];?></td>
                                                    <td><?php echo $column['attr'];?></td>
                                                    <td><?php echo $column['desc'];?></td>
                                                    <td><?php echo $column['rules'];?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
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

</script>