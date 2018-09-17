<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">同步列表</li>
                    <li class="">
                        <a href="/admin/rsync/add">添加同步</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <button class="layui-btn layui-btn-danger" data-action="/admin/rsync/module" data-confirm="确定要全部同步到备份数据库中吗？" onclick="btnAct(this);">
                            <i class="layui-icon layui-icon-senior"></i>
                            同步全部
                        </button>

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>名称</th>
                                <th>实例</th>
                                <th>说明</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $row):?>
                                    <tr>
                                        <td><?php echo $row['Id'];?></td>
                                        <td><?php echo $row['Name'];?></td>
                                        <td><?php echo $row['Instance'];?></td>
                                        <td><?php echo $row['Infos'];?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/rsync/edit?Id=<?php echo $row['Id'];?>">编辑</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-danger" data-action="/admin/rsync/module?Id=<?php echo $row['Id'];?>" data-confirm="确定要同步【<?php echo $row['Name'];?>】吗？" onclick="btnAct(this);">同步</a>
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
