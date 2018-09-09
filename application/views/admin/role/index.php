<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">角色列表</li>
                    <li class="">
                        <a href="/admin/role/add">添加角色</a>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table treetable">
                            <thead>
                            <tr>
                                <th>Id - 名称</th>
                                <th>是否超管</th>
                                <th>说明</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['List'])): ?>
                                <?php foreach($data['List'] as $row): ?>
                                    <tr data-tt-id="<?php echo $row['Id']; ?>" data-tt-parent-id="<?php echo $row['ParentId']; ?>" data-tt-branch="true">
                                        <td><?php echo $row['Id']; ?> - <?php echo $row['Name']; ?></td>
                                        <td><?php echo STATE_ON==$row['Admin'] ? '是' : '否'; ?></td>
                                        <td><?php echo $row['Infos']; ?></td>
                                        <td>
                                            <div class="layui-btn-group">
                                                <a class="layui-btn layui-btn-xs" href="/admin/role/add?ParentId=<?php echo $row['Id']; ?>">添加下级</a>
                                                <a class="layui-btn layui-btn-xs" href="/admin/role/edit?Id=<?php echo $row['Id']; ?>">编辑</a>
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
        ,onNodeExpand: function() {
            var node = this;
            var treeObj=$('tr[data-tt-id='+node.id+']').parents('table.treetable:first');
            var childSize = treeObj.find("[data-tt-parent-id='" + node.id + "']").length;
            if (childSize > 0) {
                return;
            }
            ajaxSubmit('/admin/role',{"ParentId":node.id},'post');
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
                            '    <td>'+data.Id+' - '+data.Name+'</td>'+
                            '    <td>'+(1==data.Admin?'是':'否')+'</td>'+
                            '    <td>'+data.Infos+'</td>'+
                            '    <td>'+
                            '        <div class="layui-btn-group">'+
                            '            <a class="layui-btn layui-btn-xs" href="/admin/role/add?ParentId='+data.Id+'">添加下级</a>'+
                            '            <a class="layui-btn layui-btn-xs" href="/admin/role/edit?Id='+data.Id+'">编辑</a>'+
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