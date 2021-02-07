<section>
    <button id="add" type="button" 	class="layui-btn">添加</button>
</section>
<section class="container">
    <table class="layui-table">
<!--        <colgroup>-->
<!--            <col width="150">-->
<!--            <col width="200">-->
<!--            <col>-->
<!--        </colgroup>-->
        <thead>
        <tr>
            <th>群名称</th>
            <th>TOKEN</th>
            <th>状态</th>
            <th>@人</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>编辑</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list ?: [] as $row) { ?>
        <tr>
            <td><?= $row['name'];?></td>
            <td><?= $row['token'];?></td>
            <td><?= $row['status'];?></td>
            <td><?= $row['atMobiles'];?></td>
            <td><?= $row['create_time'];?></td>
            <td><?= $row['update_time'];?></td>
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
          layerOpen(URL_PREFIX + '/pushSetting/add', "添加股票", {type: 2, area: ['600px', '800px'], full: false});
       });

        var $container = $('.container');

        $container
            .on('click', '.delete', function(){
                deleteOne('/pushSetting/delete', $(this).data('id'));
                // $.post(URL_PREFIX + '/pushSetting/delete', {id: $(this).data('id')}, function(res){
                //    // return;
                //     if (res.code) {
                //        layerAlert(res.message, res.code)
                //    } else {
                //         // layerAlert(res.message, res.code)
                //        location.reload()
                //    }
                // });
            })
            .on('click', '.edit', function(){
                layerOpen(URL_PREFIX + '/pushSetting/edit/'+$(this).data('id'), "编辑股票信息", {type: 2, area: ['600px', '800px'], full: false});
            });
    });
</script>