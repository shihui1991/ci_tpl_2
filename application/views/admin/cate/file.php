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

                        <div class="layui-btn-group">
                            <a class="layui-btn layui-btn-danger" data-confirm="确定要更新【常量配置文件】吗？" data-action="/admin/cate/update" onclick="btnAct(this);">
                                <i class="layui-icon layui-icon-refresh"></i>
                                更新配置文件
                            </a>
                        </div>

                        <div class="layui-code layui-box layui-code-view">
                            <h3 class="layui-code-h3">conf.php - <?php echo date('Y-m-d H:i:s',$data['Updated']); ?></h3>
                            <ol class="layui-code-ol">
                                <?php foreach($data['Content'] as $cont):?>
                                    <li><?php echo $cont; ?></li>
                                <?php endforeach;?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
