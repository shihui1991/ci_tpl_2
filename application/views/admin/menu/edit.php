<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/menu">菜单列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/menu/add">添加菜单</a>
                    </li>
                    <li class="layui-this">
                        修改菜单
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/menu/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id'];?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">上级菜单：</label>
                                <div class="layui-input-block" id="ParentId">

                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">路由地址：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Url" value="<?php echo $data['List']['Url'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">路由别名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="UrlAlias" value="<?php echo $data['List']['UrlAlias'];?>" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">菜单图标：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Icon" value="<?php echo htmlspecialchars($data['List']['Icon']);?>" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否限制：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Ctrl" value="0" title="不限" <?php if(0 == $data['List']['Ctrl']){echo ' checked';};?>>
                                    <input type="radio" name="Ctrl" value="1" title="限制" <?php if(1 == $data['List']['Ctrl']){echo ' checked';};?>>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否显示：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Display" value="0" title="隐藏" <?php if(0 == $data['List']['Display']){echo ' checked';};?>>
                                    <input type="radio" name="Display" value="1" title="显示" <?php if(1 == $data['List']['Display']){echo ' checked';};?>>
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
                                <label class="layui-form-label">排序：</label>
                                <div class="layui-input-block">
                                    <input type="number" min="0" name="Sort" value="<?php echo $data['List']['Sort'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
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

<script src="/js/func-form-submit.js"></script>
<script>
    // 获取上级菜单
    var ParentId=<?php echo $data['List']['ParentId'];?>;
</script>
<script src="/js/func-menu-modify.js"></script>