<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/menu">菜单列表</a>
                    </li>
                    <li class="layui-this">
                        添加菜单
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form " action="/admin/menu/add" method="post" onsubmit="return false;">
                            <div class="layui-form-item">
                                <label class="layui-form-label">上级菜单：</label>
                                <div class="layui-input-block" id="ParentId">
                                    <input type="hidden" name="ParentId" value="<?php echo $data['ParentId'];?>">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">路由地址：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Url" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">路由别名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="UrlAlias" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Name" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">菜单图标：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="Icon" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否限制：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Ctrl" value="0" title="不限">
                                    <input type="radio" name="Ctrl" value="1" title="限制" checked>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否显示：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="Display" value="0" title="隐藏" checked>
                                    <input type="radio" name="Display" value="1" title="显示">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">是否开启：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="State" value="0" title="禁用">
                                    <input type="radio" name="State" value="1" title="开启" checked>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">排序：</label>
                                <div class="layui-input-block">
                                    <input type="number" min="0" name="Sort" value="0" required  lay-verify="required" placeholder=""  class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">功能说明：</label>
                                <div class="layui-input-block">
                                    <textarea name="Infos" class="layui-textarea"></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="formSubmit">保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;

        // 获取上级菜单
        var ParentId=<?php echo $data['ParentId'];?>;
        if(ParentId){
            ajaxSubmit('/admin/menu/info',{Id:ParentId},'get');
            if(!ajaxResp || "undefined" === typeof ajaxResp){
                layer.msg('网络开小差了',{icon:5});
            }else{
                if(ajaxResp.code){
                    layer.msg(ajaxResp.msg,{icon:2});
                }
                else{
                    var dom='<input type="text" value="'+ajaxResp.data.List.Name+'" class="layui-input" readonly>';
                    $('#ParentId').append(dom);
                    form.render();
                }
            }
        }

        //监听提交
        form.on('submit(formSubmit)', function(data){
            btnAct(data.elem);
            return false;
        });
    });
</script>