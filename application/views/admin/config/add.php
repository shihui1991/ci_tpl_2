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
                        <a href="/admin/config/add">添加配置</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/config/add" method="post" onsubmit="return false;">

                            <div class="layui-form-item">
                                <label class="layui-form-label">表名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Table" value="" required  lay-verify="required" placeholder="" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" value="" required  lay-verify="required" placeholder="" class="layui-input">
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

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">主数据库：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="MainDB" value="" required  lay-verify="required" placeholder="" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">备数据库：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="BackDB" value="" required  lay-verify="required" placeholder="" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">单列配置：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Single" value="0" title="否" checked>
                                    <input type="radio" name="Single" value="1" title="是">
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
                                            <th><a class="layui-btn layui-btn-xs layui-btn-normal" onclick="addField(this);">添加字段</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="弃用" >
                                    <input type="radio" name="State" value="1" title="开启" checked>
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
    var index=0;

</script>
<script src="/js/func-config-modify.js"></script>