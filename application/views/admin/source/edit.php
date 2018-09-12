<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/source">资源列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/source/add">添加资源</a>
                    </li>
                    <li class="layui-this">
                        修改
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/source/edit" method="post">
                            <input type="hidden" name="Id" value="<?php echo $data['Id']?>">
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item upload-content">
                                <label class="layui-form-label">
                                    图片资源：
                                    <button class="layui-btn layui-btn-sm" type="button" onclick="uploadBtn(this)">
                                        点击上传
                                    </button>
                                    <input type="file" accept="image/*" name="UploadFile" data-field="Url" data-savepath="source" data-savename="" data-overwrite="true" onchange="upload(this)" style="display: none;">
                                </label>
                                <div class="layui-input-block uploaded-box">
                                    <ul class="img-box">
                                        <?php if(!empty($data['List']['Url'])):?>
                                            <li style="display: inline-block;margin:10px;">
                                                <div style="width:300px;height:200px;">
                                                    <img style="max-width:250px;max-height:200px;" src="<?php echo $data['List']['Url'];?>" alt="">
                                                    <div class="text">
                                                        <div class="inner">
                                                            <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="viewPic(this)">查看</a>
                                                            <a class="layui-btn layui-btn-xs layui-btn-warm" onclick="removePic(this)">删除</a>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="Url" value="<?php echo $data['List']['Url'];?>" placeholder="" readonly  class="layui-input">
                                                </div>
                                            </li>
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">云地址：</label>
                                <div class="layui-input-block">
                                    <input type="text" id="Cloud" name="Cloud" value="<?php echo $data['List']['Cloud'];?>" placeholder=""  class="layui-input">
                                    <img id="CloudImg" src="<?php echo $data['List']['Cloud'];?>" alt="" style="max-height: 200px;">
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

<script src="/js/upload-images.js"></script>

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

    // 输入云地址实时预览
    $('#Cloud').on('change',function () {
        var src=$(this).val();
        var img=$('#CloudImg');
        img.attr('src',src);
        if(src){
            img.css('display','');
        }else{
            img.css('display','none');
        }
    });

</script>