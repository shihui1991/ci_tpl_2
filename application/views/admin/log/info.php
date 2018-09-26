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

                        <pre class="layui-code" lay-title="<?php echo $data['File']; ?>" lay-encode="true" lay-about="false">
<?php echo htmlspecialchars($data['Content']); ?>
                        </pre>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    layui.use('code', function(){ //加载code模块
        layui.code({
            about:false
            , encode:true
        });
    });
</script>