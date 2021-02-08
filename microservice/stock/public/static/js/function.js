function layerOpen(html, title, params) {
    params = params || {}
    type = params.type || 1;
    area = params.area || ['420px', '240px'];
    var index = layer.open({
        type: type,
        skin: 'layui-layer-rim', //加上边框
        area: area, //宽高
        content: html,
        title: title ? title : '信息'
    });
    if (params.full) {
        layer.full(index)
    }
    return index;
}

function layerAlert(content, error) {
    layer.alert(content, {
        icon: error ? 2 : 1,    // 1提示，2错误
    });
}
