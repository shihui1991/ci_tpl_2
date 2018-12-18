<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">接口列表</li>
                    <li class="">
                        <a href="/admin/api/add">添加接口</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <?php require_once VIEWPATH.'layout/search.php'; ?>

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>名称</th>
                                <th>URL</th>
                                <th>事件ID</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $row):?>
                                    <tr>
                                        <td><?php echo $row['Id'];?></td>
                                        <td><?php echo $row['Name'];?></td>
                                        <td><?php echo $row['Url'];?></td>
                                        <td><?php echo $row['EventId'];?></td>
                                        <td><?php echo STATE_ON == $row['State'] ? '开启':'关闭';?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs layui-btn-normal" href="/admin/api/info?Id=<?php echo $row['Id'];?>">查看</a>
                                                <a class="layui-btn layui-btn-xs" href="/admin/api/edit?Id=<?php echo $row['Id'];?>">编辑</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>

                        <?php require_once VIEWPATH.'layout/pageBar.php'; ?>
                        <?php require_once VIEWPATH.'admin/api/apiStruct.php'; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>