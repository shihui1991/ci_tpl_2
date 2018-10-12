<body>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="">
                        <a href="/admin/api">接口列表</a>
                    </li>
                    <li class="">
                        <a href="/admin/api/add">添加接口</a>
                    </li>
                    <li class="">
                        <a href="/admin/api/edit?Id=<?php echo $data['Id']?>">修改接口</a>
                    </li>
                    <li class="layui-this">
                        详情
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td>名称：</td>
                                <td><?php echo $data['List']['Name'];?></td>
                            </tr>
                            <tr>
                                <td>接口URL：</td>
                                <td><?php echo $data['List']['Url'];?></td>
                            </tr>
                            <tr>
                                <td>事件ID：</td>
                                <td><?php echo $data['List']['EventId'];?></td>
                            </tr>
                            <tr>
                                <td>状态：</td>
                                <td><?php echo STATE_ON == $data['List']['State'] ?'开启':'关闭';?></td>
                            </tr>
                            <tr>
                                <td>请求参数：</td>
                                <td>
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th>参数</th>
                                            <th>类型</th>
                                            <th>必填/可选</th>
                                            <th>名称</th>
                                            <th>说明</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $requestList=$data['List']['Request']; ?>
                                        <?php if(!empty($requestList)): ?>
                                            <?php foreach($requestList as $k=>$request):?>
                                                <tr data-tt-id="<?php echo $request['Id'];?>" data-tt-parent-id="<?php echo $request['ParentId'];?>">
                                                    <td><?php echo $request['VarName'];?></td>
                                                    <td><?php echo $request['Type'];?></td>
                                                    <td><?php echo $request['Required'];?></td>
                                                    <td><?php echo $request['Name'];?></td>
                                                    <td><?php echo $request['Infos'];?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>响应参数：</td>
                                <td>
                                    <table class="layui-table treetable">
                                        <thead>
                                        <tr>
                                            <th>参数</th>
                                            <th>类型</th>
                                            <th>选项</th>
                                            <th>名称</th>
                                            <th>说明</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $responseList=$data['List']['Response']; ?>
                                        <?php if(!empty($responseList)): ?>
                                            <?php foreach($responseList as $i=>$response):?>
                                                <tr data-tt-id="<?php echo $response['Id'];?>" data-tt-parent-id="<?php echo $response['ParentId'];?>">
                                                    <td><?php echo $response['VarName'];?></td>
                                                    <td><?php echo $response['Type'];?></td>
                                                    <td><?php echo $response['Required'];?></td>
                                                    <td><?php echo $response['Name'];?></td>
                                                    <td><?php echo $response['Infos'];?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><a class="layui-btn layui-btn-sm layui-btn-normal" onclick="test()" title="点击测试">响应示例</a>：</td>
                                <td>
                                    <ul class="img-box">
                                        <?php if(!empty($data['List']['Example'])):?>
                                            <?php foreach($data['List']['Example'] as $img):?>
                                                <li style="display: inline-block;margin:10px;">
                                                    <div style="width:300px;height:200px;">
                                                        <img style="max-width:250px;max-height:200px;" src="<?php echo $img;?>" alt="">
                                                        <div class="text">
                                                            <div class="inner">
                                                                <a class="layui-btn layui-btn-xs layui-btn-normal" onclick="viewPic(this)">查看</a>
                                                            </div>
                                                        </div>
                                                        <input type="text" name="Image" value="<?php echo $img;?>" placeholder="" readonly  class="layui-input">
                                                    </div>
                                                </li>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>说明：</td>
                                <td><?php echo $data['List']['Infos'];?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="test" style="display: none;">
    <fieldset class="layui-elem-field">
        <legend>请求参数</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="text-form" action="<?php echo $data['List']['Url'];?>" method="post" onsubmit="return false;">
                <?php if(!empty($requestList)): ?>
                    <?php foreach($requestList as $k=>$request):?>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><?php echo $request['VarName'];?>：</label>
                            <div class="layui-input-block">
                                <input type="text" name="<?php echo $request['VarName'];?>" placeholder="<?php echo $request['Name'];?>"  class="layui-input">
                            </div>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </form>
        </div>
    </fieldset>

    <fieldset class="layui-elem-field">
        <legend>响应结果</legend>
        <div class="layui-field-box">
<pre id="test-response">

</pre>
        </div>
    </fieldset>
</div>

<link rel="stylesheet" href="/treetable/treetable.min.css" />
<script src="/treetable/jquery.treetable.min.js"></script>

<link rel="stylesheet" href="/viewer/viewer.min.css">
<script src="/viewer/viewer.min.js"></script>
<script src="/js/upload-images.js"></script>

<script>
    $(".treetable").treetable({
        expandable: true
        , initialState: "expanded"//默认打开所有节点
        , stringCollapse: '关闭'
        , stringExpand: '展开'
    });

    function test() {
        layui.use(['form','layer'], function(){
            var form = layui.form;
            var layer = layui.layer;

            // 打开导航
            layer.ready(function () {
                layer.open({
                    type:1
                    ,skin:'layui-layer-lan'
                    ,area: ['60%', '80%']
                    ,offset:'c'
                    ,closeBtn:1
                    ,shade:0
                    ,maxmin:true
                    ,moveOut: true
                    ,title:['<?php echo $data['List']['Name'];?>','text-align: center;']
                    ,content:$('#test')
                    ,btn:['提交测试','取消']
                    ,btnAlign: 'c'
                    ,yes:function (index,layero) {
                        var formObj=$('#text-form');
                        var url = formObj.attr('action');
                        var data = formObj.serialize();
                        var dom='';
                        ajaxSubmit(url,data,'post');
                        if(!ajaxResp || "undefined" === typeof ajaxResp){
                            dom = '请求失败';
                        }else{
                            dom = JSON.stringify(ajaxResp,null,4)
                        }
                        $('#test-response').html(dom);
                    }
                    ,btn2:function (index,layero) {

                    }
                });
            });
        });
    }
</script>