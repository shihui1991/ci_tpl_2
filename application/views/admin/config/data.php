<body>

<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/config">配置列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/file">配置文件</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/config/data?Id=<?php echo $data['Id'];?>">配置数据</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <?php foreach($data['Config']['Columns'] as $field=>$column):?>
                                    <th>
                                        <?php echo $field;?><br>
                                        <?php echo $column['name'];?><br>
                                        <?php echo $column['attr'];?>
                                    </th>
                                <?php endforeach;?>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if(!empty($data['List'])):?>
                                <?php foreach($data['List'] as $row):?>
                                    <tr>
                                        <?php foreach($data['Config']['Columns'] as $field=>$column):?>
                                            <td><?php echo $row[$field];?></td>
                                        <?php endforeach;?>
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
