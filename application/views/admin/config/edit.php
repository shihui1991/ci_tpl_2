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
                    <li class="layui-this">
                        <a href="/admin/config/edit?Id=<?php echo $data['Id']; ?>">修改配置</a>
                    </li>
                    <li class="">
                        <a href="/admin/config/data?ConfigId=<?php echo $data['Id'];?>">配置数据</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/config/edit" method="post" onsubmit="return false;">

                            <input type="hidden" name="Id" value="<?php echo $data['Id']?>">

                            <div class="layui-form-item">
                                <label class="layui-form-label">表名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Table" value="<?php echo $data['List']['Table'];?>" readonly class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="<?php echo $data['List']['Name'];?>" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">数据库配置：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th>类型</th>
                                            <th>配置名</th>
                                            <th>数据库</th>
                                            <th>表名</th>
                                            <th>主键</th>
                                            <th><a class="layui-btn layui-btn-xs layui-btn-normal" onclick="addDBConf(this);">添加类型</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $index=0; ?>
                                        <?php if(!empty($data['List']['DBConf'])):?>
                                            <?php foreach($data['List']['DBConf'] as $dbConf):?>
                                                <tr>
                                                    <td><input type="text" name="DBConf[<?php echo $index;?>][type]" value="<?php echo $dbConf['type'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="DBConf[<?php echo $index;?>][dbConfigName]" value="<?php echo $dbConf['dbConfigName'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="DBConf[<?php echo $index;?>][db]" value="<?php echo $dbConf['db'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="DBConf[<?php echo $index;?>][table]" value="<?php echo $dbConf['table'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="DBConf[<?php echo $index;?>][primaryKey]" value="<?php echo $dbConf['primaryKey'];?>" class="layui-input" placeholder="英文逗号（,）隔开"></td>
                                                    <td>
                                                        <div class="layui-btn-group">
                                                            <a class="layui-btn layui-btn-xs layui-btn-danger" onclick="removeField(this);">删除</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $index++; ?>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">主数据库：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="MainDB" value="<?php echo $data['List']['MainDB'];?>" required  lay-verify="required"  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">备数据库：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="BackDB" value="<?php echo $data['List']['BackDB'];?>" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">单列配置：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Single" value="0" title="否" <?php if(0 == $data['List']['Single']){echo ' checked';};?>>
                                    <input type="radio" name="Single" value="1" title="是" <?php if(1 == $data['List']['Single']){echo ' checked';};?>>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">字段详情：</label>
                                <div class="layui-input-block">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th width="120">字段</th>
                                            <th>字段名</th>
                                            <th>字段映射</th>
                                            <th width="100">属性</th>
                                            <th>属性描述</th>
                                            <th>验证规则</th>
                                            <th width="80">显示</th>
                                            <th><a class="layui-btn layui-btn-xs layui-btn-normal" onclick="addField(this);">添加字段</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if(!empty($data['List']['Columns'])):?>
                                            <?php foreach($data['List']['Columns'] as $column):?>
                                                <tr>
                                                    <td><input type="text" name="Columns[<?php echo $index;?>][field]" value="<?php echo $column['field'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Columns[<?php echo $index;?>][name]" value="<?php echo $column['name'];?>" class="layui-input"></td>
                                                    <td><input type="text" name="Columns[<?php echo $index;?>][alias]" value="<?php echo $column['alias'];?>" class="layui-input"></td>
                                                    <td>
                                                        <select name="Columns[<?php echo $index;?>][attr]">
                                                            <option value="int" <?php if('int' == $column['attr']){echo ' selected';}?>> int </option>
                                                            <option value="float" <?php if('float' == $column['attr']){echo ' selected';}?>> float </option>
                                                            <option value="double" <?php if('double' == $column['attr']){echo ' selected';}?>> double </option>
                                                            <option value="string" <?php if('string' == $column['attr']){echo ' selected';}?>> string </option>
                                                            <option value="array" <?php if('array' == $column['attr']){echo ' selected';}?>> array </option>
                                                            <option value="json" <?php if('json' == $column['attr']){echo ' selected';}?>> json </option>
                                                            <option value="date" <?php if('date' == $column['attr']){echo ' selected';}?>> date </option>
                                                            <option value="datetime" <?php if('datetime' == $column['attr']){echo ' selected';}?>> datetime </option>
                                                        </select>
                                                    </td>
                                                    <td><textarea name="Columns[<?php echo $index;?>][desc]" class="layui-textarea"><?php echo $column['desc'];?></textarea></td>
                                                    <td><textarea name="Columns[<?php echo $index;?>][rules]" class="layui-textarea"><?php echo $column['rules'];?></textarea></td>
                                                    <td>
                                                        <select name="Columns[<?php echo $index;?>][show]">
                                                            <option value="0" <?php if(0 == $column['show']){echo ' selected';}?>> 隐藏 </option>
                                                            <option value="1" <?php if(1 == $column['show']){echo ' selected';}?>> 显示 </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="layui-btn-group">
                                                            <a class="layui-btn layui-btn-xs layui-btn-danger" onclick="removeField(this);">删除</a>
                                                            <a class="layui-btn layui-btn-xs layui-btn-primary" onclick="moveUp(this);" title="上移">上移</a>
                                                            <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="moveDown(this);" title="下移">下移</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $index++; ?>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"><?php echo $data['List']['Infos'];?></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="弃用" <?php if(0 == $data['List']['State']){echo ' checked';};?>>
                                    <input type="radio" name="State" value="1" title="开启" <?php if(1 == $data['List']['State']){echo ' checked';};?>>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="formSubmit">保存</button>
                                </div>
                            </div>
                        </form>

                        <?php require_once VIEWPATH.'admin/config/valiRules.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/func-form-submit.js"></script>
<script>
    var index=<?php echo $index; ?>;

</script>

<script src="/js/func-config-modify.js"></script>
