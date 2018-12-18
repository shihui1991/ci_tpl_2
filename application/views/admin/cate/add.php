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
                        <a href="/admin/cate/file">配置文件</a>
                    </li>
                    <li class="layui-this">
                        添加分类
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/cate/add" method="post" onsubmit="return false;">

                            <div class="layui-form-item">
                                <label class="layui-form-label">分组：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Group" value="<?php echo $data['Group']; ?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">分类值：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Value" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">常量名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Constant" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">排序：</label>
                                <div class="layui-input-block">
                                    <input type="number" name="Sort" value="0" min="0" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否显示：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Display" value="0" title="隐藏">
                                    <input type="radio" name="Display" value="1" title="显示" checked>
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

<script src="/js/func-form-submit.js"></script>