<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/source">资源列表</a>
                    </li>
                    <li class="layui-this">
                        添加资源
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/source/add" method="post" onsubmit="return false;">

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item upload-content">
                                <label class="layui-form-label">
                                    图片资源：
                                    <a class="layui-btn layui-btn-warm layui-btn-sm btn-upload">
                                        点击上传
                                        <input type="file" accept="image/*" name="UploadFile" data-field="Url" data-savepath="source" data-overwrite="true" onchange="uploadImg(this)">
                                    </a>
                                </label>
                                <div class="layui-input-block uploaded-box">
                                    <ul class="img-box">

                                    </ul>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">云地址：</label>
                                <div class="layui-input-block">
                                    <input type="text" id="Cloud" name="Cloud" placeholder=""  class="layui-input">
                                    <img id="CloudImg" src="" alt="" style="max-height: 200px;display: none;">
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

<link rel="stylesheet" href="/viewer/jquery-0.6.0/viewer.min.css">
<script src="/viewer/jquery-0.6.0/viewer.min.js"></script>
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