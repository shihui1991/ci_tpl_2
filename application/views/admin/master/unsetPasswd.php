<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">
            <br>

            <form class="layui-form " action="/admin/master/unsetPasswd" method="post" onsubmit="return false;">
                <input type="hidden" name="Id" value="<?php echo $data['Id'];?>">

                <div class="layui-form-item">
                    <label class="layui-form-label">姓名：</label>
                    <div class="layui-input-block">
                        <input type="text" readonly class="layui-input" value="<?php echo $data['List']['Realname'];?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">账号：</label>
                    <div class="layui-input-block">
                        <input type="text" readonly class="layui-input" value="<?php echo $data['List']['Account'];?>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">重置密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="Password" required  lay-verify="required" placeholder=""  class="layui-input">
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

<script src="/js/func-form-submit.js"></script>