<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/cate">分类列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/cate/add">添加分类</a>
                    </li>
                    <li class="layui-this">
                        修改
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/cate/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id']?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">分组：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Group" value="<?php echo $data['List']['Group']?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name']?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">分类值：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Value" value="<?php echo $data['List']['Value']?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">常量名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Constant" value="<?php echo $data['List']['Constant']?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">排序：</label>
                                <div class="layui-input-block">
                                    <input type="number" name="Sort" value="<?php echo $data['List']['Sort']?>" min="0" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否显示：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Display" value="0" title="隐藏" <?php if(0 == $data['List']['Display']){echo ' checked';}?>>
                                    <input type="radio" name="Display" value="1" title="显示" <?php if(1 == $data['List']['Display']){echo ' checked';}?>>
                                </div>
                            </div>


                            <div class="layui-form-item">
                                <label class="layui-form-label">说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"><?php echo $data['List']['Infos']?></textarea>
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