<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">菜单列表</li>
                    <li class="">
                        <a href="/admin/menu/add">添加菜单</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table treetable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>名称</th>
                                <th>路由地址</th>
                                <th>限制</th>
                                <th>显示</th>
                                <th>状态</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])): ?>
                                <?php foreach($data['List'] as $row): ?>
                                    <tr data-tt-id="<?php echo $row['Id']; ?>" data-tt-parent-id="<?php echo $row['ParentId']; ?>" data-tt-branch="true">
                                        <td><?php echo $row['Id']; ?></td>
                                        <td><?php echo $row['Icon']; ?> <?php echo $row['Name']; ?></td>
                                        <td><?php echo $row['Url']; ?></td>
                                        <td><?php echo YES==$row['Ctrl'] ? '限制' : '不限'; ?></td>
                                        <td><?php echo YES==$row['Display'] ? '显示' : '隐藏'; ?></td>
                                        <td><?php echo STATE_ON==$row['State'] ? '开启' : '禁用'; ?></td>
                                        <td><?php echo $row['Sort']; ?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/menu/add?ParentId=<?php echo $row['Id']; ?>">添加子菜单</a>
                                                <a class="layui-btn layui-btn-xs layui-btn-normal" href="/admin/menu/edit?Id=<?php echo $row['Id']; ?>">编辑</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>

<script>
    $(".treetable").treetable({
        expandable: true
        ,initialState :"collapsed"
        ,stringCollapse:'关闭'
        ,stringExpand:'展开'
        ,clickableNodeNames: true
        ,column: 1
        ,onNodeExpand: function() {
            var node = this;
            var treeObj=$('tr[data-tt-id='+node.id+']').parents('table.treetable:first');
            var childSize = treeObj.find("[data-tt-parent-id='" + node.id + "']").length;
            if (childSize > 0) {
                return;
            }
            ajaxSubmit('/admin/menu',{"ParentId":node.id},'post');
            if(!ajaxResp || "undefined" === typeof ajaxResp){
                return false;
            }
            if(ajaxResp.code){
                return false;
            }
            else{
                if(ajaxResp.data.List.length){
                    var childs='';
                    $.each(ajaxResp.data.List,function(i,data){
                        childs +='<tr data-tt-id="'+data.Id+'" data-tt-parent-id="'+data.ParentId+'" data-tt-branch="true">'+
                            '    <td>'+data.Id+'</td>'+
                            '    <td>'+data.Icon+' '+data.Name+'</td>'+
                            '    <td>'+data.Url+'</td>'+
                            '    <td>'+(0==data.Ctrl?'不限':'限制')+'</td>'+
                            '    <td>'+(1==data.Display?'显示':'隐藏')+'</td>'+
                            '    <td>'+(1==data.State?'开启':'禁用')+'</td>'+
                            '    <td>'+data.Sort+'</td>'+
                            '    <td>'+
                            '        <div class="layui-btn-group">'+
                            '            <a class="layui-btn layui-btn-xs" href="/admin/menu/add?ParentId='+data.Id+'">添加子菜单</a>'+
                            '            <a class="layui-btn layui-btn-xs layui-btn-normal" href="/admin/menu/edit?Id='+data.Id+'">编辑</a>'+
                            '        </div>'+
                            '    </td>'+
                            '</tr>';
                    });
                    treeObj.treetable("loadBranch", node, childs);// 插入子节点
                    treeObj.treetable("expandNode", node.id);// 展开子节点
                }else{
                    var tr = treeObj.find("[data-tt-id='" + node.id + "']");
                    tr.data("tt-branch","false");
                    tr.find("span.indenter").html("");
                }
            }

        }
    });
</script>