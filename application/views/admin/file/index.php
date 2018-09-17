<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">文件列表</li>

                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

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
                                            <div class="layui-btn-group">
                                                <?php if(!$row['IsDir']):?>
                                                    <a class="layui-btn layui-btn-xs" href="/<?php echo $row['Path'];?>" target="_blank">下载</a>
                                                <?php endif;?>
                                                <a class="layui-btn layui-btn-xs" data-confirm="确定要删除【<?php echo $row['File'];?>】吗？" onclick="btnAct(this);" data-action="/admin/file/del?File=<?php echo $row['Path'];?>">删除</a>
                                            </div>
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

</script>