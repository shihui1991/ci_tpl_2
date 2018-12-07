<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">
                        <a href="/admin/config">配置列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/file">配置文件</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <?php require_once VIEWPATH.'layout/search.php'; ?>

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>表名</th>
                                <th>名称</th>
                                <th>主键</th>
                                <th>单列</th>
                                <th>说明</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $row):?>
                                    <tr>
                                        <td><?php echo $row['Id'];?></td>
                                        <td><?php echo $row['Table'];?></td>
                                        <td><?php echo $row['Name'];?></td>
                                        <td><?php echo $row['PrimaryKey'];?></td>
                                        <td><?php echo YES == $row['Single']?'是':'否';?></td>
                                        <td><?php echo $row['Infos'];?></td>
                                        <td><?php echo YES == $row['State']?'开启':'弃用';?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs layui-btn-primary" href="/admin/config/edit?Id=<?php echo $row['Id'];?>">编辑</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-normal" href="/admin/config/data?ConfigId=<?php echo $row['Id'];?>">查看</a>
                                                <a class="layui-btn layui-btn-xs" href="/admin/config/download?Id=<?php echo $row['Id'];?>" target="_blank">下载</a>
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
