<blockquote class="layui-elem-quote">
    <a class="layui-btn" onclick="openFilter()">筛选</a>
    <a class="layui-btn layui-btn-normal" href="<?php echo $data['FilterUrl']; ?>">重置</a>

    <?php if(!empty($data['OtherBtns'])): ?>
        <?php foreach($data['OtherBtns'] as $btn): ?>
            <?php echo $btn; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</blockquote>

<?php $index=0; ?>

<div id="filter-box" style="display: none;">
    <form action="<?php echo $data['FilterUrl']; ?>" class="layui-form" method="post" id="filter-form">
        <table class="layui-table">
            <tr>
                <th>
                    筛选条件
                    <a class="layui-btn layui-btn-warm layui-btn-sm" onclick="addParams()" title="点击添加筛选条件">
                        <i class="layui-icon layui-icon-add-1"></i>
                    </a>
                </th>
                <td id="filter-params">
                    <?php if(!empty($data['FilterParams'])): ?>
                        <?php foreach($data['FilterParams'] as $params): ?>
                            <div class="layui-form-item filter-item">
                                <div class="layui-inline">
                                    <div class="layui-input-inline">
                                        <select name="FilterParams[<?php echo $index; ?>][Field]" lay-search="">
                                            <?php foreach($data['FilterFields'] as $key=>$value): ?>
                                                <option value="<?php echo $key; ?>" <?php if($params['Field'] == $key){echo ' selected';}; ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <select name="FilterParams[<?php echo $index; ?>][Method]">
                                            <?php foreach($data['FilterMethods'] as $key=>$value): ?>
                                                <option value="<?php echo $key; ?>" <?php if($params['Method'] == $key){echo ' selected';}; ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <input type="text" name="FilterParams[<?php echo $index; ?>][Value]" value="<?php echo $params['Value']; ?>" class="layui-input">
                                    </div>
                                    <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="delItems(this)"  title="点击删除此项">
                                        <i class="layui-icon layui-icon-delete"></i>
                                    </a>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </td>
            </tr>
            <tr>
                <th>
                    排序方式
                    <a class="layui-btn layui-btn-warm layui-btn-sm" onclick="addOrders()" title="点击添加排序方式">
                        <i class="layui-icon layui-icon-add-1"></i>
                    </a>
                </th>
                <td id="filter-orders">
                    <?php if(!empty($data['FilterOrders'])): ?>
                        <?php foreach($data['FilterOrders'] as $orders): ?>

                            <div class="layui-form-item filter-item">
                                <div class="layui-inline">
                                    <div class="layui-input-inline">
                                        <select name="FilterOrders[<?php echo $index; ?>][Field]" lay-search="">
                                            <?php foreach($data['FilterFields'] as $key=>$value): ?>
                                                <option value="<?php echo $key; ?>" <?php if($orders['Field'] == $key){echo ' selected';}; ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <select name="FilterOrders[<?php echo $index; ?>][By]">
                                            <option value="ASC" <?php echo 'ASC' == $orders['By']?' selected':'';?>> 升序 </option>
                                            <option value="DESC" <?php echo 'DESC' == $orders['By']?' selected':'';?>> 降序 </option>
                                        </select>
                                    </div>
                                    <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="delItems(this)"  title="点击删除此项">
                                        <i class="layui-icon layui-icon-delete"></i>
                                    </a>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    // 打开筛选弹框
    function openFilter() {
        layui.use(['form','layer'], function(){
            var form = layui.form;
            var layer = layui.layer;

            layer.ready(function () {
                var w=$(window).width();
                var h=$(window).height();
                var width=850;
                var height=450;
                var isFull=false;
                // 小屏最大化
                if(w < width || h < height){
                    isFull = true;
                    width = w;
                    height = h;
                }
                var i=layer.open({
                    type:1
                    ,skin:'layui-layer-lan'
                    ,area: [width+'px', height+'px']
                    ,offset:'auto'
                    ,closeBtn:1
                    ,shade:0.3
                    ,shadeClose:true
                    ,maxmin:true
                    ,moveOut: false
                    ,title:['筛选条件','text-align: center;']
                    ,content:$('#filter-box')
                    ,zIndex: layer.zIndex //重点1
                    ,success: function(layero,index){
                        layer.setTop(layero); //重点2
                    }
                    ,btn:['<i class="layui-icon layui-icon-search"></i> 搜索','<i class="layui-icon layui-icon-delete"></i> 清空']
                    ,btnAlign: 'c'
                    ,yes:function (index,layero) {
                        $('#filter-form').submit();
                    }
                    ,btn2:function (index,layero) {
                        $('#filter-params').html('');
                        $('#filter-orders').html('');
                        filterIndex=0;
                        return false;
                    }
                });
                // 小屏最大化
                if(isFull){
                    layer.full(i);
                }
            });

        });
    }

    // 调整分页条行为
    $(function () {
        var pageBar = $('.layui-table-page');
        if(pageBar.length){
            pageBar.on('click','a[data-ci-pagination-page]',function () {
                $('#filter-form').attr('action',this.href).submit();
                return false;
            });
        }
    });

    var filterIndex=<?php echo $index; ?>;
    var fields=<?php echo json_encode($data['FilterFields'],JSON_UNESCAPED_UNICODE); ?>;
    var methods=<?php echo json_encode($data['FilterMethods'],JSON_UNESCAPED_UNICODE); ?>;

    // 添加筛选条件
    function addParams() {
        var dom='';
        dom += '<div class="layui-form-item filter-item">' +
            '    <div class="layui-inline">' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterParams['+filterIndex+'][Field]" lay-search="">';

        $.each(fields,function (f,n) {
            dom += '<option value="'+f+'">'+n+'</option>';
        });

        dom += '            </select>' +
            '        </div>' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterParams['+filterIndex+'][Method]">';

        $.each(methods,function (m,n) {
            dom += '<option value="'+m+'">'+n+'</option>';
        });

        dom +='            </select>' +
            '        </div>' +
            '        <div class="layui-input-inline">' +
            '            <input type="text" name="FilterParams['+filterIndex+'][Value]" value="" class="layui-input">' +
            '        </div>' +
            '        <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="delItems(this)"  title="点击删除此项">' +
            '            <i class="layui-icon layui-icon-delete"></i>' +
            '        </a>' +
            '    </div>' +
            '</div>';

        $('#filter-params').append(dom);
        filterIndex ++;

        layui.use(['form'], function(){
            var form = layui.form;
            form.render();
        });
    }

    // 添加排序方式
    function addOrders() {
        var dom='';
        dom += '<div class="layui-form-item filter-item">' +
            '    <div class="layui-inline">' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterOrders['+filterIndex+'][Field]" lay-search="">';

        $.each(fields,function (f,n) {
            dom += '<option value="'+f+'">'+n+'</option>';
        });
        dom += '            </select>' +
            '        </div>' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterOrders['+filterIndex+'][By]">' +
            '               <option value="ASC">升序</option>' +
            '               <option value="DESC">降序</option>' +
            '            </select>' +
            '        </div>' +
            '        <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="delItems(this)"  title="点击删除此项">' +
            '            <i class="layui-icon layui-icon-delete"></i>' +
            '        </a>' +
            '    </div>' +
            '</div>';


        $('#filter-orders').append(dom);
        filterIndex ++;

        layui.use(['form'], function(){
            var form = layui.form;
            form.render();
        });
    }
    // 删除项
    function delItems(obj) {
        $(obj).parents('.filter-item:first').remove();
    }
</script>