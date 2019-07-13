// 所有选项 默认值
// branchAttr          ttBranch
// clickableNodeNames  false
// column              0
// columnElType        td
// expandable          false
// expanderTemplate    <a href="#">&nbsp;</a>
// indent              19
// indenterTemplate    <span class="indenter"></span>
// initialState        collapsed | expanded
// nodeIdAttr          ttId
// parentIdAttr        ttParentId
// stringCollapse      Collapse
// stringExpand        Expand
// 所有事件
// onInitialized      树初始化完毕后的回调函数
// onNodeCollapse     节点折叠时的回调函数
// onNodeExpand       节点展开时的回调函数
// onNodeInitialized  节点初始化完毕后的回调函数
function makeTreeTable(treeTableObj,args)
{
    var options = {
        expandable: true
        ,initialState :"collapsed"
        ,stringCollapse:'关闭'
        ,stringExpand:'展开'
        ,clickableNodeNames: true
    };
    if(args){
        $.extend(options,args);
    }
    treeTableObj.treetable(options);
}

// 折叠所有节点
function collapseAllTreeTableNodes(treeTableObj)
{
    treeTableObj.treetable('collapseAll');
}

// 折叠节点
function collapseTreeTableNode(treeTableObj,id)
{
    treeTableObj.treetable('collapseNode',id);
}

// 展开所有节点
function expandAllTreeTableNodes(treeTableObj)
{
    treeTableObj.treetable('expandAll');
}

// 展开节点
function expandTreeTableNode(treeTableObj,id)
{
    if(getTreeTableNodeByID(treeTableObj,id)){
        treeTableObj.treetable('expandNode',id);
    }
}

// 通过ID获取某行 # Select a node from the tree. Returns a TreeTable.Node object
function getTreeTableNodeByID(treeTableObj,id)
{
    return treeTableObj.treetable('node',id);
}

// 删除行及所有子行 # 从树中移除某个节点及其所有子节点
function removeTreeTableNode(treeTableObj,id)
{
    treeTableObj.treetable('removeNode',id);
}

// 添加子行 # 向树中插入新行(<tr>s), 传入参数 node 为父节点，rows为待插入的行. 如果父节点node为null ，新行被作为父节点插入
function addTreeTableNewNodes(treeTableObj,dom,id)
{
    var node = id ? getTreeTableNodeByID(treeTableObj,id) : null;
    treeTableObj.treetable('loadBranch',node,dom);
}

// 删除子行 # Remove nodes/rows (HTML <tr>s) from the tree, with parent node. Note that the parent (node) will not be removed
function delTreeTableChildNodes(treeTableObj,id)
{
    var node = getTreeTableNodeByID(treeTableObj,id);
    treeTableObj.treetable('unloadBranch',node);
}

// 移动行及所有子行 # Move node nodeId to new parent with destinationId.
function moveTreeTableNodes(treeTableObj,id,toId)
{
    treeTableObj.treetable('move',id,toId);
}

// 展示树中的某个节点
function revealTreeTableNode(treeTableObj,id)
{
    treeTableObj.treetable('reveal',id);
}

// 子行排序
function sortTreeTableNodes(treeTableObj,id,columnOrFunc)
{
    var node = getTreeTableNodeByID(treeTableObj,id);
    columnOrFunc = columnOrFunc ? columnOrFunc : null;
    treeTableObj.treetable('sortBranch',node,columnOrFunc);
}

// 删除成功后删除行及所有子行
function callRemoveNodes(resp,obj)
{
    if(obj){
        obj.data('loading',false);
    }
    if(resp.code){
        alert(resp.msg);
        return false;
    }
    if(resp.url){
        location.href = resp.url;
    }else{
        var id = obj.parents('tr:first').data('tt-id');
        removeTreeTableNode(obj.parents("table.treetable:first"),id);
    }
}