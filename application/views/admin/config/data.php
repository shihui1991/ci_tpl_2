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
                    <li class="">
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/edit?Id=<?php echo $data['ConfigId'];?>">修改配置</a>
                    </li>
                    <li class="layui-this">
                        <a href="/admin/config/data?ConfigId=<?php echo $data['ConfigId'];?>">配置数据</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/insert?ConfigId=<?php echo $data['ConfigId'];?>">添加数据</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <fieldset class="layui-elem-field">
                            <legend><?php echo $data['Config']['Table']; ?> <span class="layui-badge-rim"><?php echo $data['Config']['Name']; ?></span></legend>

                            <div class="layui-field-box">

                                <?php require_once VIEWPATH.'layout/search.php'; ?>

                                <table class="layui-table">
                                    <thead>
                                    <tr>
                                        <?php foreach($data['Config']['Columns'] as $field=>$column):?>
                                            <th>
                                                <?php echo $field;?><br>
                                                <?php echo $column['name'];?><br>
                                                <?php echo $column['alias'];?><br>
                                                <?php echo $column['attr'];?>
                                            </th>
                                        <?php endforeach;?>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php if(!empty($data['List'])):?>
                                        <?php foreach($data['List'] as $row):?>
                                            <tr>
                                                <?php foreach($data['Config']['Columns'] as $field=>$column):?>
                                                    <td><?php echo isset($row[$field])?$row[$field]:'';?></td>
                                                <?php endforeach;?>
                                                <td>
                                                    <div class="layui-btn-group">
                                                        <a class="layui-btn layui-btn-xs" href="/admin/config/modify?ConfigId=<?php echo $data['ConfigId'];?>&Id=<?php echo $row['Id'];?>">编辑</a>
                                                        <a class="layui-btn layui-btn-xs layui-btn-danger" data-confirm="确定要删除吗？" onclick="btnAct(this);" data-action="/admin/config/delete?ConfigId=<?php echo $data['ConfigId'];?>&Ids=<?php echo $row['Id'];?>">删除</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
