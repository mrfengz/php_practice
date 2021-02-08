<style>
    .layui-form {
        padding: 30px;
    }
</style>

<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">群名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" required  lay-verify="required" placeholder="群名称" autocomplete="off" class="layui-input" value="<?= $data['name'] ?? '';?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">钉钉通知Token</label>
        <div class="layui-input-block">
            <input type="text" name="token" required  lay-verify="required" placeholder="钉钉通知Token" autocomplete="off" class="layui-input" value="<?= $data['token'] ?? '';?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">钉钉通知Secret</label>
        <div class="layui-input-block">
            <input type="text" name="secret" required  placeholder="钉钉通知Secret,未设置可不填写" autocomplete="off" class="layui-input" value="<?= $data['secret'] ?? '';?>">
        </div>
    </div>

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">复选框</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="checkbox" name="like[write]" title="写作">-->
<!--            <input type="checkbox" name="like[read]" title="阅读" checked>-->
<!--            <input type="checkbox" name="like[dai]" title="发呆">-->
<!--        </div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">启用</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" <?= empty($data['status']) || $data['status'] == STATUS_ACTIVE ? 'checked' : '';?>>
        </div>
    </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">@人手机号</label>
            <div class="layui-input-block">
                <textarea name="at_mobiles" placeholder="每行一个" class="layui-textarea"><?= $data['at_mobiles'] ?? '';?></textarea>
            </div>
        </div>

<!--    -->
<!---->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">单选框</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="radio" name="sex" value="男" title="男">-->
<!--            <input type="radio" name="sex" value="女" title="女" checked>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item layui-form-text">-->
<!--        <label class="layui-form-label">文本域</label>-->
<!--        <div class="layui-input-block">-->
<!--            <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>-->
<!--        </div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" lay-submit lay-filter="pushSetting">立即提交</button>
<!--            <button type="reset" class="layui-btn layui-btn-primary">重置</button>-->
        </div>
    </div>
</form>

<script>
    var data = <?= json_encode($data); ?>
    //Demo
    layui.use('form', function(){
        var form = layui.form;
        form.render();  //需要加上这句才会渲染，文档居然没有。。。。

        //监听提交
        form.on('submit(pushSetting)', function(formData){
            var action = data.id ? 'edit/'+data.id : 'add';
            $.post(URL_PREFIX+'/pushSetting/' + action, formData.field, function(res){
                if (!layerAlert.code) {
                    // layer.close(layui.index);
                    parent.location.href=URL_PREFIX+'/pushSetting/index'
                } else {
                    layerAlert(res.message, res.code);
                }
                // layer.msg(JSON.stringify(data.field));
            });
            return false;
        });
    });
</script>