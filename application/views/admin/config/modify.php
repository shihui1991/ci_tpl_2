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
                    <li class="">
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/edit?Id=<?php echo $data['ConfigId'];?>">修改配置</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/data?ConfigId=<?php echo $data['ConfigId'];?>">配置数据</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/insert?ConfigId=<?php echo $data['ConfigId'];?>">添加数据</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/config/modify?ConfigId=<?php echo $data['ConfigId'];?>">修改数据</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <fieldset class="layui-elem-field">
                            <legend><?php echo $data['Config']['Table']; ?> <span class="layui-badge-rim"><?php echo $data['Config']['Name']; ?></span></legend>

                            <div class="layui-field-box">
                                <form class="layui-form " action="/admin/config/modify?ConfigId=<?php echo $data['ConfigId'];?>" method="post" onsubmit="return false;">

                                    <input type="hidden" name="Id" value="<?php echo $data['Id']; ?>">

                                    <?php if(!empty($data['Config'])): ?>
                                        <?php foreach($data['Config']['Columns'] as $column): ?>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label"><?php echo $column['name']; ?>：</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="<?php echo $column['field']; ?>" value="<?php echo isset($data['List'][$column['field']]) ? htmlspecialchars($data['List'][$column['field']]) : '';?>" placeholder="" class="layui-input">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>


                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="formSubmit">保存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </fieldset>

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