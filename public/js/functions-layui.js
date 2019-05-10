var layerMsgIcon = {
    0 : '黄色叹号',
    1 : '绿色勾',
    2 : '红色叉',
    3 : '黄色问号',
    4 : '灰色锁',
    5 : '红色难过',
    6 : '绿色笑脸',
};

// 弹出提示消息
function alertMsg(msg,icon) {
    layui.use(['layer'], function(){
        var layer = layui.layer;

        layer.msg(msg, {icon: icon});
    });
}
// 开启加载层
function showLoading() {
    layui.use(['layer'], function(){
        layui.layer.load();
    });
}

// 关闭加载层
function closeLoading() {
    layui.use(['layer'], function(){
        layui.layer.closeAll('loading');
    });
}

// 关闭页面层
function closePage() {
    layui.use(['layer'], function(){
        layui.layer.closeAll('page');
    });
}

// 关闭弹窗层
function closeIframe() {
    layui.use(['layer'], function(){
        layui.layer.closeAll('iframe');
    });
}

// 关闭信息框
function closeDialog() {
    layui.use(['layer'], function(){
        layui.layer.closeAll('dialog');
    });
}

// 关闭所有层
function closeAllLayer() {
    layui.use(['layer'], function(){
        layui.layer.closeAll();
    });
}

// 常用元素初始化
function renderElem(ele,filter) {
    layui.use(['element'], function(){
        layui.element.render(ele, filter);
    });
}

// 表单初始化
function renderForm() {
    var ele = arguments[0];
    layui.use(['form'], function(){
         layui.form.render(ele);
    });
}

// 初始化 时间插件
function renderDate() {
    layui.use(['form','laydate'], function() {
        var form = layui.form;
        var laydate = layui.laydate;

        var laydates = $('body').find('.laydate');
        $.each(laydates, function (i, obj) {
            var type = $(obj).data('type');
            var index = $.inArray(type, ['time', 'date', 'datetime', 'month', 'year']);
            if (index > -1) {
                laydate.render({
                    elem: obj
                    , type: type
                });
            }
        });
        if(laydates.length){
            form.render();
        }
    });
}

// 加载code模块
function renderCode() {
    layui.use('code', function(){
        layui.code({
            about:false
            , encode:true
        });
    });
}

// 监听表单提交
layui.use(['form','layer'], function(){
    var form = layui.form;

    form.on('submit(btnFormSubmit)', function(data){
        btnFormAjaxSubmit(data.elem);
        return false;
    });
});

// 打开弹窗
function openLayer(options) {
    var w = $(window).width();
    var h = $(window).height();
    var isFull = false;
    // 小屏最大化
    if(w < 600 || h < 400){
        isFull = true;
    }

    var other = {
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
    $.extend(options,other);

    layui.use(['layer'], function() {
        var layer = layui.layer;

        layer.ready(function () {
            var i = layer.open(options);
            // 小屏最大化
            if (isFull) {
                layer.full(i);
            }
        });
    });
}

// 打开一个弹窗
function openIframe(title,url) {
    var w = $(window).width();
    var h = $(window).height();
    var other = undefined !== arguments[2] ? arguments[2] : {} ;
    var width = other['width'] ? other['width'] : 1000;
    var height = other['height'] ? other['height'] : 600;
    if(w < width || h < height){
        width = w;
        height = h;
    }
    var offset = other['offset'] ? other['offset'] : [Math.random()*(h-height), Math.random()*(w-width)];
    var shade = other['shade'] ? other['shade'] : 0 ;

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
    $.extend(options,other);
    openLayer(options);
}

// 打开一个弹框
function openDom(area,title,content) {
    var other = undefined !== arguments[3] ? arguments[3] : {} ;

    var options = {
        type: 1
        , skin: 'layui-layer-lan'
        , area: area
        , title: title
        , content: content
        , maxmin: true
    };
    $.extend(options,other);
    openLayer(options);
}