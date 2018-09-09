<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">管理员列表</li>
                    <li class="">
                        <a href="/admin/master/add">添加管理员</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>姓名</th>
                                <th>角色</th>
                                <th>账号</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])): ?>
                                <?php foreach($data['List'] as $row): ?>
                                    <tr>
                                        <td><?php echo $row['Id']; ?></td>
                                        <td><?php echo $row['Realname']; ?></td>
                                        <td><?php echo $row['RoleId']; ?></td>
                                        <td><?php echo $row['Account']; ?></td>
                                        <td><?php echo STATE_ON==$row['State'] ? '开启' : '禁用'; ?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/master/edit?Id=<?php echo $row['Id']; ?>">编辑</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            </tbody>
                        </table>

                        <div class="layui-table-page">
                            <?php echo $data['Links']; ?>
                            <span class="layui-laypage-count">
                                当前 <?php echo count($data['List']); ?> 条，
                                每页 <?php echo $data['PerPage']; ?> 条，
                                共 <?php echo number_format($data['Total']); ?> 条
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
