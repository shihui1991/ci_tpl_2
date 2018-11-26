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

                                <?php if(!empty($data['FilterOrders'])): ?>
                                    <?php foreach($data['FilterOrders'] as $orders): ?>
                                        <th><?php echo $data['FilterFields'][$orders['Field']];?></th>
                                    <?php endforeach; ?>
                                <?php endif; ?>

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

                                        <?php if(!empty($data['FilterOrders'])): ?>
                                            <?php foreach($data['FilterOrders'] as $orders): ?>
                                                <td><?php echo $row[$orders['Field']];?></td>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

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

                        <div class="layui-table-page">
                            <?php echo $data['Links']; ?>
                            <span class="layui-laypage-count">
                                当前 <?php echo count($data['List']); ?> 条，
                                每页 <?php echo $data['PerPage']; ?> 条，
                                共 <?php echo number_format($data['Total']); ?> 条
                            </span>
                        </div>

                        <div class="layui-collapse">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title"><span class="layui-badge">备注：</span>响应参数基本格式</h2>
                                <div class="layui-colla-content">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th>参数</th>
                                            <th>类型</th>
                                            <th>名称</th>
                                            <th>说明</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>code</td>
                                            <td>int</td>
                                            <td>返回码</td>
                                            <td>返回0则操作成功，大于0则操作失败</td>
                                        </tr>
                                        <tr>
                                            <td>msg</td>
                                            <td>string</td>
                                            <td>提示信息</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>data</td>
                                            <td>array</td>
                                            <td>返回数据</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>url</td>
                                            <td>string</td>
                                            <td>重定向地址</td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>