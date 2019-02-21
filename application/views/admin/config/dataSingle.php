<body>
<style>
    .content-text{
        min-height:20px;
    }
</style>
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
                    <?php if(empty($data['List'])):?>
                        <li class="">
                            <a href="/admin/config/insert?ConfigId=<?php echo $data['ConfigId'];?>">添加数据</a>
                        </li>
                    <?php endif;?>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <fieldset class="layui-elem-field">
                            <legend><?php echo $data['Config']['Table']; ?> <span class="layui-badge-rim"><?php echo $data['Config']['Name']; ?></span></legend>

                            <div class="layui-field-box">
                                <?php if(!empty($data['List'])):?>
                                    <form class="layui-form " action="/admin/config/modify?ConfigId=<?php echo $data['ConfigId'];?>" method="post" onsubmit="return false;">
                                        <table class="layui-table">
                                            <thead>
                                            <tr>
                                                <th>字段</th>
                                                <th>属性</th>
                                                <th>值</th>
                                                <th>说明</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php foreach($data['Config']['Columns'] as $field=>$column):?>
                                                <tr>
                                                    <td><?php echo $field;?></td>
                                                    <td><?php echo $column['attr'];?></td>
                                                    <td>
                                                        <p contenteditable="true" class="content-text"><?php echo isset($data['List'][0][$field]) ? htmlspecialchars($data['List'][0][$field]) : '';?></p>
                                                        <input type="hidden" class="content-input" name="<?php echo $field; ?>" value="<?php echo isset($data['List'][0][$field]) ? htmlspecialchars($data['List'][0][$field]) : '';?>">
                                                    </td>
                                                    <td><?php echo $column['name'];?></td>
                                                </tr>
                                            <?php endforeach;?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="4">
                                                    <?php $dbModel = \models\logic\TplLogic::instance($data['Config']['Table'])->databaseModel; ?>
                                                    <?php $key = $dbModel->getKey($data['List'][0]); ?>
                                                    <div class="layui-btn-group">
                                                        <button class="layui-btn" lay-submit lay-filter="formSubmit">更新</button>
                                                        <a class="layui-btn layui-btn-danger" data-confirm="确定要删除吗？" onclick="btnAct(this);" data-action="/admin/config/delete?ConfigId=<?php echo $data['ConfigId'];?>&Keys=<?php echo $key;?>">删除</a>
                                                    </div>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                <?php endif;?>
                            </div>
                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/func-form-submit.js"></script>
<script>

    // 监听修改
    $('.content-text').on('keyup blur change',function () {
        $(this).siblings('.content-input').val($(this).html());
    });

</script>