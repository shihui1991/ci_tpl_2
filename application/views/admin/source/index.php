<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">资源列表</li>
                    <li class="">
                        <a href="/admin/source/add">添加资源</a>
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
                                <th>图片</th>
                                <th>地址</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $row):?>
                                    <tr>
                                        <td><?php echo $row['Id'];?></td>
                                        <td><?php echo $row['Name'];?></td>
                                        <td>
                                            本机：<img src="<?php echo $row['Url'];?>" alt="" style="max-width: 100px;max-height: 100px;">
                                            云：<img src="<?php echo $row['Cloud'];?>" alt="" style="max-width: 100px;max-height: 100px;">
                                        </td>
                                        <td>
                                            本机：<?php echo $row['Url'];?><br>
                                            云：<?php echo $row['Cloud'];?><br>
                                            说明：<?php echo substr($row['Infos'],0,100);?>
                                        </td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/source/edit?Id=<?php echo $row['Id'];?>">编辑</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
