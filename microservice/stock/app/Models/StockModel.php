<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 21:13
 */

namespace App\Models;



class StockModel extends BaseModel
{
    protected $table = 'stock';

    protected $allowedFields = ['stock_code', 'stock_type', 'is_warning', 'user_id', 'cost', 'stock_name', 'day_warning_min',
        'day_warning_max', 'cost_warning_min', 'cost_warning_max', 'status'];

    CONST IS_WARNING_YES = 1;
    CONST IS_WARNING_NO = 2;

    public static $isWarningMaps = [
        self::IS_WARNING_YES => '是',
        self::IS_WARNING_NO => '否',
    ];


    public static $stockTypeMaps = [
        'sh' => '上证',
        'sz' => '深证',
    ];
}