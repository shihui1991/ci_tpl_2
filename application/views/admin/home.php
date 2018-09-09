<body>
<style>
    #tab-list ,#btn-list {
        display: -webkit-box; /* Chrome 4+, Safari 3.1, iOS Safari 3.2+ */
        display: -moz-box; /* Firefox 17- */
        display: -webkit-flex; /* Chrome 21+, Safari 6.1+, iOS Safari 7+, Opera 15/16 */
        display: -moz-flex; /* Firefox 18+ */
        display: -ms-flexbox; /* IE 10 */
        display: flex; /* Chrome 29+, Firefox 22+, IE 11+, Opera 12.1/17/18, Android 4.4+ */
    }
    #tab-list li ,#btn-list .layui-btn {
        -moz-box-flex:1;
        -webkit-box-flex:1;
        box-flex:1;
        -webkit-flex:1;
        -ms-flex:1;
        -ms-flex-positive:1;
        -ms-flex-negative:0;
        -ms-flex-preferred-size:0;
        flex: 1;
    }
    #nav-menu .layui-nav-child dd{
        padding-left: 20px;
    }
</style>

<div class="layui-fluid" id="body">
    <div class="layui-row" >
        <div class="layui-col-xs12" >
            控制台
        </div>
    </div>
</div>

<div id="menu" style="display: none;">
    <div class="layui-tab layui-tab-card layui-tab-brief" style="margin: 0;">
        <ul class="layui-tab-title" id="tab-list">
            <li class="layui-this"> <i class="layui-icon layui-icon-find-fill"></i> 导航</li>
            <li class=""> <i class="layui-icon layui-icon-friends"></i> 用户</li>

        </ul>
        <div class="layui-tab-content" style="padding:0 0 10px 0;">
            <div class="layui-tab-item layui-show">
                <ul class="layui-nav layui-nav-tree layui-bg-cyan" id="nav-menu" lay-filter="nav-menu" lay-shrink="all" style="width: 100%;">

                </ul>
            </div>
            <div class="layui-tab-item">
                <form class="layui-form layui-form-pane" action="/admin/master/modify" method="post">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户名：</label>
                        <div class="layui-input-block">
                            <input type="text" name="Username" value="dev" required  lay-verify="required" placeholder=""  class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名：</label>
                        <div class="layui-input-block">
                            <input type="text" name="Realname" value="开发者账号" required  lay-verify="required" placeholder=""  class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">角色：</label>
                        <div class="layui-input-block">
                            <input type="text" value="开发者" readonly class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-btn-group" id="btn-list">
                            <button class="layui-btn layui-btn-primary layui-btn-sm" lay-submit lay-filter="formSubmit" type="button">
                                <i class="layui-icon layui-icon-edit"></i>
                                确认修改
                            </button>
                            <button class="layui-btn layui-btn-normal layui-btn-sm" id="editPasswd" type="button">
                                <i class="layui-icon layui-icon-password"></i>
                                修改密码
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    // 获取导航
    function getNavList(parentId){
        var navList=[];
        ajaxSubmit('/admin/home/nav',{"ParentId":parentId},'post');
        if(!ajaxResp || "undefined" === typeof ajaxResp){
            return navList;
        }
        if(ajaxResp.code){
            alert(ajaxResp.msg);
            if(ajaxResp.url){
                location.href=ajaxResp.url;
            }
            return navList;
        }
        else{
            navList=ajaxResp.data.List;
        }
        return navList;
    }

    // 获取一级导航
    var navList=getNavList(0);
    var topNavDom='';
    $.each(navList,function (i,data) {
        topNavDom += '<li class="layui-nav-item"><a href="javascript:;" data-url="'+data.Url+'" data-id="'+data.Id+'" data-loaded="false">'+data.Icon+' '+data.Name+'</a></li>';
    });
    $('#nav-menu').html(topNavDom);

    layui.use(['element','layer','form'], function(){
        var element = layui.element;
        var layer = layui.layer;
        var form = layui.form;

        // 打开导航
        layer.ready(function () {
            layer.open({
                type:1
                //,skin:'layui-layer-lan'
                ,area: ['300px', '600px']
                ,offset:'l'
                ,closeBtn:0
                ,shade:0
                ,maxmin:true
                ,title:['导航目录','text-align: center;']
                ,content:$('#menu')
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero,index){
                    layer.setTop(layero); //重点2
                }
                ,btn:['<i class="layui-icon layui-icon-close"></i>关闭所有','<i class="layui-icon layui-icon-refresh"></i>退出登录']
                ,btnAlign: 'c'
                ,yes:function (index,layero) {
                    layer.closeAll('iframe');
                }
                ,btn2:function (index,layero) {
                    ajaxSubmit('/admin/welcome/logout',{},'get');
                    if(!ajaxResp || "undefined" === typeof ajaxResp){
                        layer.msg('网络出问题了……',{icon:5});
                    }
                    if(ajaxResp.code){
                        layer.msg(ajaxResp.msg,{icon:5});
                    }
                    else{
                        layer.msg(ajaxResp.msg,{icon:1});
                        if(ajaxResp.url){
                            location.href=ajaxResp.url;
                        }else{
                            window.close();
                        }
                    }
                    return false;
                }
            });
        });


        // 打开窗口，加载菜单
        element.on('nav(nav-menu)', function(elem){
            var id=elem.data('id');
            var url=elem.data('url');
            var name=elem.text();
            var isRealUrl=url.indexOf('#');
            // 打开窗口
            if(-1 == isRealUrl){
                layer.open({
                    type: 2
                    ,skin:'layui-layer-molv'
                    ,area: ['1000px', '600px']
                    ,offset: [
                        Math.random()*($(window).height()-600)
                        ,Math.random()*($(window).width()-1000)
                    ]
                    ,shade: 0
                    ,maxmin: true
                    ,title: name
                    ,content: url
                    ,zIndex: layer.zIndex //重点1
                    ,success: function(layero){
                        layer.setTop(layero); //重点2
                    }
                });
            }
            // 加载菜单
            if(!elem.data('loaded')){
                // 获取下级导航
                var navList=getNavList(id);
                var navDom='<dl class="layui-nav-child">';
                $.each(navList,function (i,data) {
                    navDom +='<dd><a href="javascript:;" data-url="'+data.Url+'" data-id="'+data.Id+'" data-loaded="false">'+data.Icon+' '+data.Name+'</a></dd>';
                });
                navDom +='</dl>';
                if(navList.length>0){
                    elem.after(navDom);
                }
                elem.data('loaded',true);
                elem.parent().addClass('layui-nav-itemed');
                element.render('nav', 'nav-menu');
            }
        });

        // 关闭所有窗口
        $('#close-iframe').on('click',function () {
            layer.closeAll('iframe');
        });

        // 修改资料
        form.on('submit(formSubmit)', function(data){
            editInfo(data.elem);
        });
    });

    // 修改资料
    function editInfo(obj)
    {
        layui.use(['layer'], function(){
            var layer = layui.layer;
            var loading=layer.load();

            btnFormSubmit(obj);

            layer.close(loading);

            if(!ajaxResp || "undefined" === typeof ajaxResp){
                layer.msg('网络出问题了……',{icon:5});
            }

            if(ajaxResp.code){
                layer.msg(ajaxResp.msg,{icon:2});
            }
            else{
                layer.msg(ajaxResp.msg,{icon:1,time:1000});

            }
        });
    }

    // 修改密码
    $('#editPasswd').on('click',function () {
        layui.use(['layer'], function(){
            var layer=layui.layer;

            layer.open({
                type: 2
                ,skin:'layui-layer-molv'
                ,area: ['500px', '300px']
                ,offset: [
                    Math.random()*($(window).height()-600)
                    ,Math.random()*($(window).width()-1000)
                ]
                ,shade: 0
                ,maxmin: true
                ,title: '修改密码'
                ,content: "/admin/master/editPasswd"
                ,zIndex: layer.zIndex //重点1
                ,success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        });
    });
</script>