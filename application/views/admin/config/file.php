<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/config">配置列表</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/config/file">配置文件</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-btn-group">
                            <a class="layui-btn layui-btn-warm btn-upload">
                                上传配置文件
                                <input type="file" accept="application/vnd.ms-excel" name="UploadFile" data-field="File" data-savepath="<?php echo CONFIG_UPLOAD_DIR;?>" data-overwrite="true" onchange="uploadConfig(this)">
                            </a>
                        </div>
                        <table class="layui-table treetable">
                            <thead>
                            <tr>
                                <th>文件</th>
                                <th>大小</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $i=>$row):?>
                                    <tr data-tt-id="<?php echo $row['Path']; ?>" data-tt-parent-id="<?php echo $row['Dir']; ?>">
                                        <td><?php echo $row['File'];?></td>
                                        <td><?php echo $row['Size'];?></td>
                                        <td><?php echo $row['Updated'];?></td>
                                        <td>
                                            <?php if(!$row['IsDir']):?>
                                                <div class="layui-btn-group">
                                                    <a class="layui-btn layui-btn-xs" data-confirm="确定要更新配置【<?php echo $row['File'];?>】吗？" onclick="btnAct(this);" data-action="/admin/config/update?File=<?php echo $row['Path'];?>">更新</a>
                                                    <a class="layui-btn layui-btn-xs layui-btn-normal" href="/<?php echo $row['Path'];?>" target="_blank">下载</a>
                                                    <a class="layui-btn layui-btn-xs layui-btn-danger" data-confirm="确定要删除配置【<?php echo $row['File'];?>】吗？" onclick="btnAct(this);" data-action="/admin/config/remove?File=<?php echo $row['Path'];?>">删除</a>
                                                </div>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>

<script>
    $(".treetable").treetable({
        expandable: true
        ,initialState :"collapsed"
        ,stringCollapse:'关闭'
        ,stringExpand:'展开'
    });

    function uploadConfig(obj) {
        var fileUrls=uploadFile(obj);
        if(fileUrls.length){
            location.reload();
        }
    }
</script>