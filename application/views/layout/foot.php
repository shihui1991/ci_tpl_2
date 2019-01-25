<style>
    .layui-fixbar{
        opacity: 0.3;
        filter:Alpha(opacity=30);
    }
    .layui-fixbar:hover{
        opacity: 0.8;
        filter:Alpha(opacity=80);
    }
    .layui-fixbar li{
        display: list-item !important;
        width: 35px;
        height: 35px;
        line-height: 35px;
        font-size: 18px;
    }
    .layui-fixbar-top,.layui-fixbar-buttom{
        font-size: 22px !important;
    }
</style>
<ul class="layui-fixbar">
    <li class="layui-icon layui-icon-up layui-fixbar-top" id="body-top" title="顶部" onclick="window.scrollTo(0,0)"></li>
    <li class="layui-icon layui-icon-return" id="body-back" title="返回" onclick="location=document.referrer;" ></li>
    <li class="layui-icon layui-icon-refresh" id="body-reload" title="刷新" onclick="location.reload();"></li>
    <li class="layui-icon layui-icon-down layui-fixbar-buttom" id="body-buttom" title="底部" onclick="window.scrollTo(0,document.documentElement.clientHeight)"></li>
</ul>

<script>
    layui.use(['element','form'], function(){
        var element = layui.element;
        var form = layui.form;

        $('.layui-fixbar').find('li').html('');

        // 修改标题
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        if(index > 0){
            parent.layer.title("<?php echo $data['CurMenu']['Name'];?>", index)
        }
    });
</script>
</body>
</html>