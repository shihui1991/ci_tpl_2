$('head').append(
    '<link rel="stylesheet" href="/treetable/treetable.min.css" />\n' +
    '<script src="/treetable/jquery.treetable.min.js"></script>'
);

function makeTreeTable(treeTableObj) {
    var options = {
        expandable: true
        ,initialState :"collapsed"
        ,stringCollapse:'关闭'
        ,stringExpand:'展开'
        ,clickableNodeNames: true
    };
    var other = undefined !== arguments[1] ? arguments[1] : {} ;
    $.extend(options,other);

    treeTableObj.treetable(options);
}

// 折叠所有节点
function collapseAllTreeTableNodes(treeTableObj) {
    treeTableObj.treetable('collapseAll');
}

// 折叠节点
function collapseTreeTableNode(treeTableObj,id) {
    treeTableObj.treetable('collapseNode',id);
}

// 展开所有节点
function expandAllTreeTableNodes(treeTableObj) {
    treeTableObj.treetable('expandAll');
}

// 展开节点
function expandTreeTableNode(treeTableObj,id) {
    if(getTreeTableNodeByID(treeTableObj,id)){
        treeTableObj.treetable('expandNode',id);
    }
}

// 通过ID获取某行 # Select a node from the tree. Returns a TreeTable.Node object
function getTreeTableNodeByID(treeTableObj,id) {
    return treeTableObj.treetable('node',id);
}

// 删除行及所有子行 # 从树中移除某个节点及其所有子节点
function removeTreeTableNode(treeTableObj,id) {
    treeTableObj.treetable('removeNode',id);
}

// 添加子行 # 向树中插入新行(<tr>s), 传入参数 node 为父节点，rows为待插入的行. 如果父节点node为null ，新行被作为父节点插入
function addTreeTableNewNodes(treeTableObj,dom) {
    var node = undefined === arguments[2] ? null : getTreeTableNodeByID(treeTableObj,arguments[2]);
    treeTableObj.treetable('loadBranch',node,dom);
}

// 删除子行 # Remove nodes/rows (HTML <tr>s) from the tree, with parent node. Note that the parent (node) will not be removed
function delTreeTableChildNodes(treeTableObj,id) {
    var node = getTreeTableNodeByID(treeTableObj,id);
    treeTableObj.treetable('unloadBranch',node);
}

// 移动行及所有子行 # Move node nodeId to new parent with destinationId.
function moveTreeTableNodes(treeTableObj,id,toId) {
    treeTableObj.treetable('move',id,toId);
}

// 展示树中的某个节点
function revealTreeTableNode(treeTableObj,id) {
    treeTableObj.treetable('reveal',id);
}

// 子行排序
function sortTreeTableNodes(treeTableObj,id) {
    var node = getTreeTableNodeByID(treeTableObj,id);
    var columnOrFunc = undefined === arguments[2] ? null : arguments[2];
    treeTableObj.treetable('sortBranch',node,columnOrFunc);
}

// 删除成功后删除行及所有子行
function callRemoveNodes(resp,obj) {
    if(!resp){
        toastr.error('未知错误');
    }
    else if(resp.code){
        toastr.warning(resp.msg);
    }
    else{
        if(resp.url){
            toastr.options.onHidden = function() {
                location.href = resp.url;
            }
        }else{
            toastr.options.onHidden = function() {
                var id = obj.parents('tr:first').data('tt-id');
                removeTreeTableNode(obj.parents("table.treetable:first"),id);
            }
        }
        toastr.success(resp.msg);
    }
    closeLoading();
    // 释放提交按钮
    $(obj).data('loading',false).val('');
}