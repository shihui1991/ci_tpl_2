<body style="background-image:url('/img/bg.jpg');">
<style>
    #LAY_app,body,html{height:100%;background: #eeeeee;}.layui-layout-body{overflow:auto}#LAY-user-login,.layadmin-user-display-show{display:block!important}.layadmin-user-login{position:relative;left:0;top:0;padding:110px 0;min-height:100%;box-sizing:border-box}.layadmin-user-login-main{width:375px;margin:0 auto;box-sizing:border-box}.layadmin-user-login-box{padding:20px}.layadmin-user-login-header{text-align:center}.layadmin-user-login-header h2{margin-bottom:10px;font-weight:300;font-size:30px;color:#000}.layadmin-user-login-header p{font-weight:300;color:#999}.layadmin-user-login-body .layui-form-item{position:relative}.layadmin-user-login-icon{position:absolute;left:1px;top:1px;width:38px;line-height:36px;text-align:center;color:#d2d2d2}.layadmin-user-login-body .layui-form-item .layui-input{padding-left:38px}.layadmin-user-login-codeimg{max-height:38px;width:100%;cursor:pointer;box-sizing:border-box}.layadmin-user-login-other{position:relative;font-size:0;line-height:38px;padding-top:20px}.layadmin-user-login-other>*{display:inline-block;vertical-align:middle;margin-right:10px;font-size:14px}.layadmin-user-login-other .layui-icon{position:relative;top:2px;font-size:26px}.layadmin-user-login-other a:hover{opacity:.8}.layadmin-user-jump-change{float:right}.layadmin-user-login-footer{position:absolute;left:0;bottom:0;width:100%;line-height:30px;padding:20px;text-align:center;box-sizing:border-box;color:rgba(0,0,0,.5)}.layadmin-user-login-footer span{padding:0 5px}.layadmin-user-login-footer a{padding:0 5px;color:rgba(0,0,0,.5)}.layadmin-user-login-footer a:hover{color:rgba(0,0,0,1)}.layadmin-user-login-main[bgimg]{background-color:#fff;box-shadow:0 0 5px rgba(0,0,0,.05)}.ladmin-user-login-theme{position:fixed;bottom:0;left:0;width:100%;text-align:center}.ladmin-user-login-theme ul{display:inline-block;padding:5px;background-color:#fff}.ladmin-user-login-theme ul li{display:inline-block;vertical-align:top;width:64px;height:43px;cursor:pointer;transition:all .3s;-webkit-transition:all .3s;background-color:#f2f2f2}.ladmin-user-login-theme ul li:hover{opacity:.9}@media screen and (max-width:768px){.layadmin-user-login{padding-top:60px}.layadmin-user-login-main{width:300px}.layadmin-user-login-box{padding:10px}}
    .layadmin-user-login-main{
        background:white;
        border: 1px solid #CCCCCC;
    }
</style>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>铿锵三人行</h2>
            <p>后台管理</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body">
            <form class="layui-form" method="post" action="<?php echo $data['LoginURI']; ?>" onsubmit="return false;">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="Account" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input" >
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="Password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
                </div>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="btnFormSubmit" data-after-act="afterLogin">登 入</button>
                </div>
            </form>
        </div>

    </div>

    <div class="layui-trans layadmin-user-login-footer">

        <p>© <?php echo date('Y');?> <a href="/" target="_blank">首页</a></p>
    </div>

</div>


<script>
    if (window.parent !== window.self) {
        document.write = '';
        window.parent.location.href = window.self.location.href;
        setTimeout(function () {
            document.body.innerHTML = '';
        },0);
    }

    // 登录之后
    function afterLogin(resp,obj) {
        closeLoading();
        if(!resp){
            toastr.error('未知错误');
        }
        else if(resp.code){
            toastr.warning(resp.msg);
            obj.data('loading',false);
        }
        else{
            if(resp.url){
                toastr.options.onHidden = function() {
                    location.href = resp.url;
                }
            }
            toastr.success('登录成功！正在跳转……');
        }
    }

</script>
