<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">分类列表</li>
                    <li class="">
                        <a href="/admin/cate/add">添加分类</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-btn-group">
                            <button class="layui-btn layui-btn-danger" type="button" onclick="updateConf()">
                                <i class="layui-icon layui-icon-refresh"></i>
                                更新【常量配置文件】
                            </button>

                        </div>
                        <br>

                        <?php if(!empty($data['List'])): ?>

                            <div class="layui-collapse">

                                <?php foreach($data['List']['GroupList'] as $group): ?>

                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="color: blue;font-weight: bold;">
                                            <?php echo $group['Group']; ?>
                                            <a class="layui-btn layui-btn-xs" href="/admin/cate/add?Group=<?php echo $group['Group']; ?>">添加分类</a>
                                        </h2>

                                        <div class="layui-colla-content">

                                            <table class="layui-table">
                                                <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>名称</th>
                                                    <th>分类值</th>
                                                    <th>常量名</th>
                                                    <th>排序</th>
                                                    <th>状态</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($data['List']['CateList'][$group['Group']] as $row): ?>
                                                    <tr>
                                                        <td><?php echo $row['Id']; ?></td>
                                                        <td><?php echo $row['Name']; ?></td>
                                                        <td><?php echo $row['Value']; ?></td>
                                                        <td><?php echo $row['Constant']; ?></td>
                                                        <td><?php echo $row['Sort']; ?></td>
                                                        <td><?php echo STATE_ON == $row['Display'] ? '显示' : '隐藏'; ?></td>
                                                        <td>
                                                            <div class="layui-btn-group">
                                                                <a class="layui-btn layui-btn-xs" href="/admin/cate/edit?Id=<?php echo $row['Id']; ?>">编辑</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateConf() {
        var yes = confirm('确定要更新【常量配置文件】么？');
        if(yes){
            // 下载TS文件
            ajaxSubmit('/admin/cate/update',{},'get');
            if(!ajaxResp || "undefined" === typeof ajaxResp){
                alert('网络开小差了');
            }else{
                alert(ajaxResp.msg);
            }
        }
    }
</script>