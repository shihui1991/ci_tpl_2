/** 加载loading
 * layer.load(icon, options)
 *  0  三点转动
 *  1  黑色粗菊
 *  2  黑色细菊
 */
function openLoading(icon)
{
    layui.use(['layer'], function(){
        var layer = layui.layer;
        layer.load(icon,{
            zIndex: layer.zIndex //重点1
            , success: function(layero){
                layer.setTop(layero); //重点2
            }
        });
    });
}

/** 关闭所有层
 * layer.closeAll(type)
 *  undefined  所有
 *  dialog     信息框
 *  page       页面层
 *  iframe     iframe层
 *  loading    加载层
 *  tips       tips层
 */
function closeLayer(type)
{
    layui.use(['layer'], function(){
        var layer = layui.layer;
        layer.closeAll(type);
    });
}

// 关闭弹出层
// layer.close(index)
function closeLayerIndex(index)
{
    layui.use(['layer'], function(){
        var layer = layui.layer;
        layer.close(index);
    });
}

/** 弹出提示
 * layer.msg(content, options, end)
 *  0  蓝色i
 *  1  绿色勾
 *  2  红色叉
 *  3  蓝色问号
 *  4  黑色锁
 *  5  红色难过
 *  6  红色笑脸
 *  7  蓝色下载
 */
function alertMsg(msg,icon,time,callback)
{
    layui.use(['layer'], function(){
        var layer = layui.layer;
        layer.msg(msg, {
                icon: icon ,
                time: (time ? time : 2000) ,
                zIndex: layer.zIndex //重点1
                , success: function(layero){
                    layer.setTop(layero); //重点2
                }
            }
            , callback);
    });
}

// 弹出询问
// layer.confirm(content, options, yes, cancel)
function alertConfirm(msg,yes,cancel,title) {
    layui.use(['layer'], function(){
        var layer = layui.layer;
        layer.confirm(msg, {
            icon: 3 ,
            title: (title ? title : '确认提示') ,
            zIndex: layer.zIndex //重点1
            , success: function(layero){
                layer.setTop(layero); //重点2
            }
        }
        , yes, cancel);
    });
}

// 常用元素初始化
function renderLayelem(ele,filter) {
    layui.use(['element'], function(){
        var element = layui.element;
        element.render(ele, filter);
    });
}

// 初始化时间插件
function renderLaydate() {
    layui.use(['laydate'], function() {
        var $ = layui.$; //重点处
        var laydate = layui.laydate;

        var dateObjs = $('body').find('.laydate');
        $.each(dateObjs, function (i, dateDom) {
            var obj = $(dateDom);
            var type = obj.data('type');
            var index = $.inArray(type, ['time', 'date', 'datetime', 'month', 'year']);
            if(-1 === index){
                return true;
            }
            var range = obj.data('range'); //true，将默认采用 “ - ” 分割
            var min = obj.data('min'); // min: '1900-1-1',如果值为整数类型，且数字＜86400000，则数字代表天数，如：min: -7，即代表最小日期在7天前，正数代表若干天后
            var max = obj.data('max'); // max: '2099-12-31',如果值为整数类型，且数字 ≥ 86400000，则数字代表时间戳，如：max: 4073558400000，即代表最大日期在：公元3000年1月1日

            var callDone = obj.data('done');
            var callChange = obj.data('change');
            var options = {
                elem: dateDom ,
                type: type
            };
            if(range){
                options['range'] = range;
            }
            if(min){
                options['min'] = min;
            }
            if(max){
                options['max'] = max;
            }
            if(callDone){
                options['done'] = window[callDone];
            }
            if(callDone){
                options['change'] = window[callChange];
            }
            laydate.render(options);
        });
    });
}

// 打开弹窗
// layer.open(options)
function openLayer(args) {
    var $ = layui.$; //重点处
    var w = $(window).width();
    var h = $(window).height();
    var isFull = false;
    // 小屏最大化
    if(w < 600 || h < 400){
        isFull = true;
    }
    layui.use(['layer'], function() {
        var layer = layui.layer;
        var options = {
            zIndex: layer.zIndex //重点1
            , success: function(layero){
                layer.setTop(layero); //重点2
            }
            , moveEnd:function (layero) {
                var pos = layero.offset();
                var index = layero.attr('times');
                if(pos.top < -30){
                    layer.full(index);
                }
            }
            , full:function (layero) {
                layero.offset({top:0,left:0});
            }
            , restore:function (layero) {
                var pos = layero.offset();
                if(pos.top < -30){
                    layero.offset({top:0,left:pos.left});
                }
                if(isFull){
                    layero.offset({top:0,left:0});
                }
            }
        };
        $.extend(options,args);

        layer.ready(function () {
            var i = layer.open(options);
            // 小屏最大化
            if (isFull) {
                layer.full(i);
            }
        });
    });
}

// 打开Iframe页面
function openLayerIframe(title,url,args) {
    var $ = layui.$; //重点处
    var w = $(window).width();
    var h = $(window).height();
    var width = args['width'] ? args['width'] : 1000;
    var height = args['height'] ? args['height'] : 600;
    if(w < width || h < height){
        width = w;
        height = h;
    }
    var offset = args['offset'] ? args['offset'] : [Math.random()*(h-height), Math.random()*(w-width)];
    var shade = args['shade'] ? args['shade'] : 0 ;

    var options = {
        type: 2
        , title: title
        , content: url
        , skin:'layui-layer-molv'
        , area: [width+'px', height+'px']
        , offset: offset
        , shade: shade
        , maxmin: true
        , moveOut: true
    };
    $.extend(options,args);
    openLayer(options);
}

// 打开弹层
function openLayerDom(area,title,dom,args) {
    var $ = layui.$; //重点处
    var options = {
        type: 1
        , skin: 'layui-layer-lan'
        , area: area
        , title: title
        , content: dom
        , maxmin: true
    };
    $.extend(options,args);
    openLayer(options);
}