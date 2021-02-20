<style>
    .layui-form {
        padding: 30px;
    }
</style>

<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">股票类型</label>
        <div class="layui-input-block">
            <?php $stockType = $data['stock_type'] ?? null;?>
            <select name="stock_type" lay-verify="required">
                <option value="sh" <?= $stockType == 'sh' ? 'selected' : '';?>>上证</option>
                <option value="sz" <?= $stockType == 'sz' ? 'selected' : '';?>>深证</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">股票代码</label>
        <div class="layui-input-block">
            <input type="text" name="stock_code" required  lay-verify="required" placeholder="股票代码" autocomplete="off" class="layui-input" value="<?= $data['stock_code'] ?? '';?>">
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
        <label class="layui-form-label">是否预警</label>
        <div class="layui-input-block">
            <input type="checkbox" name="is_warning" lay-skin="switch" <?= empty($data['is_warning']) || $data['is_warning'] == \App\Models\StockModel::IS_WARNING_NO ? '' : 'checked';?>>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">预警上下限%(当天)</label>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="day_warning_min" placeholder="下限%" autocomplete="off" class="layui-input" value="<?= $data['day_warning_min'] ?? '';?>">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="day_warning_max" placeholder="上限%" autocomplete="off" class="layui-input" value="<?= $data['day_warning_max'] ?? '';?>">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">成本价</label>
        <div class="layui-input-block">
            <input type="text" name="cost" required lay-verify="required" placeholder="成本价" autocomplete="off" class="layui-input" value="<?= $data['cost'] ?? '';?>">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">预警上下限%(成本价)</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" name="cost_warning_min" placeholder="下限%" autocomplete="off" class="layui-input" value="<?= $data['cost_warning_min'] ?? '';?>">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" name="cost_warning_max" placeholder="上限%" autocomplete="off" class="layui-input" value="<?= $data['cost_warning_max'] ?? '';?>">
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
            <button class="layui-btn" type="button" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script>
    var data = <?= json_encode($data); ?>;
    var action = data.id ? 'edit/'+data.id : 'add';
        console.log(action);

    //Demo
    layui.use('form', function(){
        var form = layui.form;
        form.render();  //需要加上这句才会渲染，文档居然没有。。。。

        //监听提交
        form.on('submit(formDemo)', function(formData){
            var action = data.id ? 'edit/'+data.id : 'add';
            console.log(action);

            $.post(URL_PREFIX+'/stock/'+action, formData.field, function(res){
                if (!layerAlert.code) {
                    // layer.close(layui.index);
                    parent.location.href=URL_PREFIX+'/stock/index'
                } else {
                    layerAlert(res.message, res.code);
                }
                // layer.msg(JSON.stringify(data.field));
            });
            return false;
        });
    });
</script>