<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/log">文件列表</a>
                    </li>
                    <li class="layui-this">文件内容</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <fieldset class="layui-elem-field">
                            <legend><?php echo $data['File']; ?></legend>
                            <h4>最后更新时间：<?php echo date('Y-m-d H:i:s',$data['Updated']); ?></h4>
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