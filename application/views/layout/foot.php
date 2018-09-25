<style>
    .layui-fixbar{
        opacity: 0.3;
        filter:Alpha(opacity=30);
    }
    .layui-fixbar:hover{
        opacity: 1;
        filter:Alpha(opacity=100);
    }
    .layui-fixbar li{
        display: list-item;
        width: 35px;
        height: 35px;
        line-height: 35px;
        font-size: 18px;
    }
    .layui-fixbar-top{
        font-size: 36px !important;
    }
</style>
<ul class="layui-fixbar">
    <li class="layui-icon layui-icon-return" id="body-back" title="返回" onclick="window.history.back();" ></li>
    <li class="layui-icon layui-icon-refresh" id="body-reload" title="刷新" onclick="location.reload();"></li>
    <li class="layui-icon layui-icon-top layui-fixbar-top" id="body-top" title="返回顶部" onclick="window.scrollTo(0,0)"></li>
</ul>

<script>
    layui.use(['element','form'], function(){
        var element = layui.element;
        var form = layui.form;

        $('.layui-fixbar').find('li').html('');
    });
</script>
</body>
</html>