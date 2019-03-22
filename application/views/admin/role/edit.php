<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/role">角色列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/role/add">添加角色</a>
                    </li>
                    <li class="layui-this">
                        修改角色
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/role/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id'];?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">上级角色：</label>
                                <div class="layui-input-block" id="ParentId">

                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否超管：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Admin" value="0" title="否" <?php if(0 == $data['List']['Admin']){echo ' checked';};?>>
                                    <input type="radio" name="Admin" value="1" title="是" <?php if(1 == $data['List']['Admin']){echo ' checked';};?>>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">授权菜单：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>名称</th>
                                            <th>路由地址</th>
                                            <th><input type="checkbox" id="check-all" onclick="allCheckOrCancel(this);" lay-ignore><label for="check-all"> 全选/取消 </label></th>
                                        </tr>
                                        </thead>
                                        <tbody id="menu-ids">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">功能说明：</label>
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
<script src="/js/func-form-submit.js"></script>

<script>
    // 获取上级角色
    var ParentId=<?php echo $data['List']['ParentId'];?>;
    var curMenuIds=<?php echo json_encode($data['List']['MenuIds'],JSON_UNESCAPED_UNICODE)?>;
    var isAdmin=<?php echo $data['List']['Admin']?>;

</script>

<script src="/js/func-role-modify.js"></script>