<body style="background-image:url('/img/bg.jpg');">
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
            <li class="layui-this"> <i class="layui-icon layui-icon-find-fill"></i> 菜单</li>
            <li class=""> <i class="layui-icon layui-icon-friends"></i> 账号</li>

        </ul>
        <div class="layui-tab-content" style="padding:0 0 10px 0;">
            <div class="layui-tab-item layui-show" id="nav">

            </div>
            <div class="layui-tab-item">
                <form class="layui-form layui-form-pane" action="/sys/master/modify" method="post" onsubmit="return false;">
                    <div class="layui-form-item">
                        <label class="layui-form-label">账户：</label>
                        <div class="layui-input-block">
                            <input type="text" name="Account" value="<?php echo $_SESSION['Master']['Account']; ?>" required  lay-verify="required" placeholder=""  class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名：</label>
                        <div class="layui-input-block">
                            <input type="text" name="Name" value="<?php echo $_SESSION['Master']['Name']; ?>" required  lay-verify="required" placeholder=""  class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">角色：</label>
                        <div class="layui-input-block">
                            <input type="text" value="<?php echo $_SESSION['Master']['RoleName']; ?>" readonly class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-btn-group" id="btn-list">
                            <button class="layui-btn layui-btn-primary layui-btn-sm" lay-submit lay-filter="btnFormSubmit">
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
    function getNavList(){
        ajaxData('/sys/home/authNav', '', 'get', function (resp) {
            if(resp.code){
                if(resp.url){
                    toastr.options.onHidden = function() {
                        location.href = resp.url;
                    }
                }
                toastr.warning(resp.msg);
            }else{
                var dom = makeNavDom(resp.data,0,1);
                $('#nav').html(dom);
                renderElem('nav', 'nav-menu');
            }
        });
    }

    // 生成导航DOM
    function makeNavDom(list,parentID,level) {
        if(0 === list.length){
            return '';
        }
        var dom = '';
        var group = getChilds(list,parentID);
        if(0 === group.childs.length){
            return dom;
        }
        if(1 === level){
            dom += '<ul class="layui-nav layui-nav-tree layui-bg-cyan" id="nav-menu" lay-filter="nav-menu" style="width: 100%;">';

            $.each(group.childs,function (i,row) {
                dom += '<li class="layui-nav-item"><a href="javascript:;" data-url="'+row.URI+'">'+row.Icon+' '+row.Name+'</a>';
                dom += makeNavDom(group.other,row.ID,level+1);
                dom += '</li>';
            });
            dom += '</ul>';
        }else{
            var classVal = 0 === group.other.length ? 'nav' : 'layui-nav-child';
            dom += '<dl class="'+classVal+'">';
            $.each(group.childs,function (i,row) {
                dom += '<dd><a href="javascript:;" data-url="'+row.URI+'">'+row.Icon+' '+row.Name+'</a>';
                dom += makeNavDom(group.other,row.ID,level+1);
                dom += '</dd>';
            });
            dom += '</dl>';
        }

        return dom;
    }

    layui.use(['element','layer'], function(){
        var element = layui.element;
        var layer = layui.layer;

        // 获取导航
        getNavList();
        // 打开导航
        var area = ['270px', '100%'];
        var title = ['铿锵三人行','text-align: center;'];
        var content = $('#menu');
        var options = {
            btn:['<i class="layui-icon layui-icon-close"></i>关闭所有','<i class="layui-icon layui-icon-refresh"></i>退出登录']
            ,btnAlign: 'c'
            ,yes:function (index,layero) {
                layer.closeAll('iframe');
                layer.min(index);
            }
            ,btn2:function (index,layero) {
                ajaxData('/sys/welcome/logout',{},'get');
            }
            ,offset:'l'
            ,closeBtn:0
            ,shade:0
            ,shadeClose:0
        };
        openDom(area,title,content,options);

        // 打开窗口
        element.on('nav(nav-menu)', function(elem){
            var url = elem.data('url');
            var name = elem.text();
            var isRealUrl = url.indexOf('#');
            // 打开窗口
            if(-1 == isRealUrl){
                openIframe([name,'font-size:20px;'], url);
            }
        });

    });

    // 修改密码
    $('#editPasswd').on('click',function () {
        var option = {
            width:500
            , height:300
        };
        openIframe('修改密码','/sys/master/editPasswd',option);
    });
</script>