<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">
            <br>

            <form class="layui-form " action="/admin/master/editPasswd" method="post" onsubmit="return false;">

                <div class="layui-form-item">
                    <label class="layui-form-label">旧密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="OldPassword" required  lay-verify="required" placeholder=""  class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="Password" required  lay-verify="required" placeholder=""  class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">重复密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="PasswordConfim" required  lay-verify="required" placeholder=""  class="layui-input">
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