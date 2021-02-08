<button class="layui-btn" type="button" id="socket-switch" data-switch="on">断开连接</button>
<section class="container">
    <section class="dynamic"></section>
    <?= $_SESSION['user_token'];?>
</section>

<script>
    $(function(){
        var token = "<?= $_SESSION['user_token'] ?? '';?>";
        var ws;
        if(ws == null){
            var wsServer = 'ws://'+ location.hostname +':8890';
            console.log(wsServer)

            ws = new WebSocket(wsServer);
            ws.onopen = function(){
                console.log("socket连接已打开");
                ws.send('token='+token); //发送用户标识
            };
            ws.onmessage = function(e){
                console.log("message:" + e.data);
                $('.dynamic').prepend('<p>'+e.data+'</p>');
            };
            ws.onclose = function(){
                console.log("socket连接已断开");
            };
            ws.onerror = function(e){
                console.log("ERROR:" + e.data);
            };
            //离开页面时关闭连接
            $(window).bind('beforeunload',function(){
                ws.close();
                console.log(ws);
            });
        }

        $('#socket-switch').on('click',function(){
            let flag = $(this).data('switch');
            $(this).data('switch', flag == 'on' ? 'off' : 'on');
            if (flag === 'on') {
                ws.close()
            } else {
                ws.send('hello test');
            }
        });
    });
</script>