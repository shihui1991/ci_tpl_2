<blockquote class="layui-elem-quote">
    <?php if(!empty($data['FilterWheres']) || !empty($data['FilterOrders'])): ?>
        <a class="layui-btn layui-btn-primary" onclick="openFilter()">
            <i class="layui-icon layui-icon-ok-circle" style="color: orangered"></i> 筛选
        </a>
    <?php else: ?>
        <a class="layui-btn" onclick="openFilter()">筛选</a>
    <?php endif; ?>

    <a class="layui-btn layui-btn-normal" href="<?php echo $data['FilterUrl']; ?>">重置</a>

    <?php if(!empty($data['OtherBtns'])): ?>
        <?php foreach($data['OtherBtns'] as $btn): ?>
            <?php echo $btn; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</blockquote>

<?php $index = 0; ?>

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
                <td id="filter-where">
                    <?php if(!empty($data['FilterWheres'])): ?>
                        <?php foreach($data['FilterWheres'] as $where): ?>
                            <div class="layui-form-item filter-item">
                                <div class="layui-inline">
                                    <div class="layui-input-inline">
                                        <select name="FilterWheres[<?php echo $index; ?>][Field]" lay-search="">
                                            <?php foreach($data['FilterFields'] as $field => $name): ?>
                                                <option value="<?php echo $field; ?>" <?php if($where['Field'] == $field){echo ' selected';}; ?>><?php echo $name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <select name="FilterWheres[<?php echo $index; ?>][Method]">
                                            <?php foreach($data['FilterMethods'] as $key => $desc): ?>
                                                <option value="<?php echo $key; ?>" <?php if($where['Method'] == $key){echo ' selected';}; ?>><?php echo $desc; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <input type="text" name="FilterWheres[<?php echo $index; ?>][Value]" value="<?php echo $where['Value']; ?>" class="layui-input">
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
                                            <?php foreach($data['FilterFields'] as $field => $name): ?>
                                                <option value="<?php echo $field; ?>" <?php if($orders['Field'] == $field){echo ' selected';}; ?>><?php echo $name; ?></option>
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
        var area = ['850px', '450px'];
        var title = ['筛选条件','text-align: center;'];
        var content = $('#filter-box');
        var options = {
            btn:['<i class="layui-icon layui-icon-search"></i> 搜索','<i class="layui-icon layui-icon-delete"></i> 清空']
            ,btnAlign: 'c'
            ,yes:function (index,layero) {
                $('#filter-form').submit();
            }
            ,btn2:function (index,layero) {
                $('#filter-where').html('');
                $('#filter-orders').html('');
                filterIndex = 0;
                return false;
            }
        };
        openDom(area,title,content,options);
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

    var filterIndex = <?php echo $index; ?>;
    var fields = <?php echo json_encode($data['FilterFields'],JSON_UNESCAPED_UNICODE); ?>;
    var methods = <?php echo json_encode($data['FilterMethods'],JSON_UNESCAPED_UNICODE); ?>;

    // 添加筛选条件
    function addParams() {
        var dom = '';
        dom += '<div class="layui-form-item filter-item">' +
            '    <div class="layui-inline">' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterWheres['+filterIndex+'][Field]" lay-search="">';

        $.each(fields,function (f,n) {
            dom += '<option value="'+f+'">'+n+'</option>';
        });

        dom += '            </select>' +
            '        </div>' +
            '        <div class="layui-input-inline">' +
            '            <select name="FilterWheres['+filterIndex+'][Method]">';

        $.each(methods,function (m,n) {
            dom += '<option value="'+m+'">'+n+'</option>';
        });

        dom +='            </select>' +
            '        </div>' +
            '        <div class="layui-input-inline">' +
            '            <input type="text" name="FilterWheres['+filterIndex+'][Value]" value="" class="layui-input">' +
            '        </div>' +
            '        <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="delItems(this)"  title="点击删除此项">' +
            '            <i class="layui-icon layui-icon-delete"></i>' +
            '        </a>' +
            '    </div>' +
            '</div>';

        $('#filter-where').append(dom);
        filterIndex ++;

        renderForm();
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

        renderForm();
    }
    // 删除项
    function delItems(obj) {
        $(obj).parents('.filter-item:first').remove();
    }
</script>