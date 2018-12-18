<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/master">管理员列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/master/add">添加管理员</a>
                    </li>
                    <li class="layui-this">
                        修改管理员
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/master/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id'];?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">真实姓名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Realname" value="<?php echo $data['List']['Realname'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">角色：</label>
                                <div class="layui-input-block">
                                    <select name="RoleId" id="RoleId" class="layui-input" lay-verify="required" lay-search>
                                        <option value=""> 选择角色 </option>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">登录账号：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Account" value="<?php echo $data['List']['Account'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否开启：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="禁用" <?php if(0 == $data['List']['State']){echo ' checked';};?>>
                                    <input type="radio" name="State" value="1" title="开启" <?php if(1 == $data['List']['State']){echo ' checked';};?>>
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

<script src="/js/func-form-submit.js"></script>
<script>
    // 获取角色
    var curRoleId=<?php echo $data['List']['RoleId'];?>;

</script>
<script src="/js/func-master-modify.js"></script>