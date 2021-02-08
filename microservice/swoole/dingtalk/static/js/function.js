function layerOpen(html) {
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '240px'], //宽高
        content: html
    });
}

function layerAlert(content, error) {
    layer.alert(content, {
        icon: error ? 2 : 1,    // 1提示，2错误
    });
}
