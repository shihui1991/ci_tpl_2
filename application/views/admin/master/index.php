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
                                        <td><?php echo $row['RoleName']; ?></td>
                                        <td><?php echo $row['Account']; ?></td>
                                        <td><?php echo STATE_ON==$row['State'] ? '开启' : '禁用'; ?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/master/edit?Id=<?php echo $row['Id']; ?>">编辑</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-danger" data-url="/admin/master/unsetPasswd?Id=<?php echo $row['Id']; ?>" onclick="unsetPasswd(this)">重置密码</a>
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
<script>
    // 重置密码
    function unsetPasswd(obj) {
        layui.use(['layer'], function(){
            var layer=layui.layer;

            var w=$(window).width();
            var h=$(window).height();
            var width=500;
            var height=300;
            var isFull=false;
            // 小屏最大化
            if(w < width || h < height){
                isFull = true;
                width = w;
                height = h;
            }
            var i=layer.open({
                type: 2
                ,skin:'layui-layer-molv'
                ,area: [width+'px', height+'px']
                ,shade: 0.3
                ,maxmin: true
                ,title: '重置密码'
                ,content: $(obj).data('url')
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
            // 小屏最大化
            if(isFull){
                layer.full(i);
            }
        });
    }
</script>