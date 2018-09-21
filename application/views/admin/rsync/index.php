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

                        <div class="layui-btn-group">
                            <button class="layui-btn layui-btn-normal" data-action="/admin/rsync/act?Act=backup" data-confirm="确定要把数据全部同步到备份数据库中吗？" onclick="btnAct(this);">
                                <i class="layui-icon layui-icon-senior"></i>
                                备份全部
                            </button>
                            <button class="layui-btn layui-btn-danger" data-action="/admin/rsync/act?Act=restore" data-confirm="确定要将备份全部还原到数据库中吗？" onclick="btnAct(this);">
                                <i class="layui-icon layui-icon-refresh-3"></i>
                                还原全部
                            </button>
                        </div>

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>名称</th>
                                <th>实例</th>
                                <th>方法</th>
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
                                        <td><?php echo $row['Method'];?></td>
                                        <td><?php echo $row['Infos'];?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/rsync/edit?Id=<?php echo $row['Id'];?>">编辑</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-normal" data-action="/admin/rsync/act?Act=backup&Id=<?php echo $row['Id'];?>" data-confirm="确定要备份【<?php echo $row['Name'];?>】全部数据吗？" onclick="btnAct(this);">备份</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-danger" data-action="/admin/rsync/act?Act=restore&Id=<?php echo $row['Id'];?>" data-confirm="确定要还原【<?php echo $row['Name'];?>】全部备份数据吗？" onclick="btnAct(this);">还原</a>
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
