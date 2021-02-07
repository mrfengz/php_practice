<!-- CONTENT -->

<section>

	<h1>August' Stock Helper</h1>

</section>


<script>
    $(function(){
        $('#login').on('click', function(){
            var html = `
<form class="layui-form" action="" style="padding: 20px;">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="username" required  lay-verify="required" placeholder="用户名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <!--        <div class="layui-form-mid layui-word-aux">辅助文字</div>-->
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" id="submit" >立即提交</button>
        </div>
    </div>
</form>
            `;
            layerOpen(html, '登录');
        });

        $('body').on('click', '#submit', function(){
            $.post(URL_PREFIX + '/home/login', $(this).parents('form').serialize(), function(res){
                if (res.code) {
                    layerAlert(res.message, res.code)
                } else {
                    location.href= URL_PREFIX + '/home/index';
                    // layerAlert(res.message, res.code);
                }
            });
        });

        $('body').on('click', '#logout', function(){
            $.post(URL_PREFIX + '/home/logout', {}, function(res){
                if (res.code) {
                    layerAlert(res.message, res.code)
                } else {
                    location.href= URL_PREFIX + '/home/index';
                }
            });
        });
    });
</script>