<section>
    <button id="add" type="button" 	class="layui-btn">添加</button>
</section>
<section class="container">
    <table class="layui-table">
        <colgroup>
            <col width="150">
            <col width="200">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>股票名称</th>
            <th>股票类型</th>
            <th>是否预警</th>
            <th>成本</th>
            <th>基于成本预警</th>
            <th>涨跌幅预警(基于昨日收盘价)</th>
            <th>编辑</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list ?: [] as $row) { ?>
        <tr>
            <td><?= $row['stock_name'];?></td>
            <td><?= $row['stock_type'];?></td>
            <td><?= $row['is_warning'];?></td>
            <td><?= $row['cost'];?></td>
            <td><?= $row['cost_warning'];?></td>
            <td><?= $row['day_warning'];?></td>
            <td class="display-flex2">
                <i class="layui-icon layui-icon-delete medium danger delete" data-id="<?= $row['id'];?>"></i>
                <i class="layui-icon layui-icon-edit medium edit" data-id="<?= $row['id'];?>"></i>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</section>

<script>
    $(function(){
       $('#add').on('click',function(){
          layerOpen(URL_PREFIX + '/stock/add', "添加股票", {type: 2, area: ['600px', '800px'], full: false});
       });

        var $container = $('.container');

        $container
            .on('click', '.delete', function(){
                $.post(URL_PREFIX + '/stock/delete', {id: $(this).data('id')}, function(res){
                   console.log(res);
                   // return;
                    if (res.code) {
                       layerAlert(res.message, res.code)
                   } else {
                        layerAlert(res.message, res.code)
                       // location.reload()
                   }
                });
            })
            .on('click', '.edit', function(){
                layerOpen(URL_PREFIX + '/stock/edit/'+$(this).data('id'), "编辑股票信息", {type: 2, area: ['600px', '800px'], full: false});
            });
    });
</script>