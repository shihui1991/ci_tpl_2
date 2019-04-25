<body>
<link rel="stylesheet" href="/layui/2.4.3/css/modules/code.css">
<style>
    ol li{
        word-wrap: break-word;
        list-style-type: decimal-leading-zero;
        background-color: #fff;
        margin-left: 50px;
    }
</style>
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

                        <div class="layui-code layui-box layui-code-view">
                            <h3 class="layui-code-h3"><?php echo $data['File']; ?></h3>
                            <ol class="">
                                <?php foreach($data['Content'] as $i => $cont):?>
                                    <li style=""><?php echo $cont; ?></li>
                                <?php endforeach;?>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
