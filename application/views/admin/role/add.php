<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/role">角色列表</a>
                    </li>
                    <li class="layui-this">
                        添加角色
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/role/add" method="post" onsubmit="return false;">

                            <div class="layui-form-item">
                                <label class="layui-form-label">上级角色：</label>
                                <div class="layui-input-block" id="ParentId">
                                    <input type="hidden" name="ParentId" value="<?php echo $data['ParentId'];?>">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否超管：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Admin" value="0" title="否" checked>
                                    <input type="radio" name="Admin" value="1" title="是">
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

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>
<script src="/js/func-form-submit.js"></script>

<script>
    // 获取上级角色
    var ParentId=<?php echo $data['List']['ParentId'];?>;
    var curMenuIds=[];
    var isAdmin=0;

</script>

<script src="/js/func-role-modify.js"></script>