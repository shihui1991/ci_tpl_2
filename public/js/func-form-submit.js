layui.use(['form','layer'], function(){
    var form = layui.form;
    var layer = layui.layer;

    //监听提交
    form.on('submit(formSubmit)', function(data){
        btnAct(data.elem);
        return false;
    });
});