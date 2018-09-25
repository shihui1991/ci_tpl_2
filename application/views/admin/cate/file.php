<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/cate">分类列表</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/cate/file">配置文件</a>
                    </li>
                    <li class="">
                        <a href="/admin/cate/add">添加分类</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <fieldset class="layui-elem-field">
                            <legend>
                                <a class="layui-btn layui-btn-danger" data-confirm="确定要更新【常量配置文件】吗？" data-action="/admin/cate/update" onclick="btnAct(this);">
                                    <i class="layui-icon layui-icon-refresh"></i>
                                    更新配置文件
                                </a>

                                <span class="layui-badge-rim"><?php echo date('Y-m-d H:i:s',$data['Updated']); ?></span>
                            </legend>

                            <div class="layui-field-box">
                                <pre class="layui-code">
<?php foreach($data['List'] as $row): ?>
<?php echo htmlspecialchars($row); ?>
<?php endforeach; ?>
                                </pre>
                            </div>
                        </fieldset>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    layui.use('code', function(){ //加载code模块
        layui.code(); //引用code方法
    });

</script>