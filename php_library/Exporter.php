<?php
namespace youmobi;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;

class Exporter
{
    /**
     * 导出文件类型
     */
    const TYPE_XLSX = 1;
    const TYPE_XLS = 2;
    const TYPE_CSV = 3;

    /**
     * 类型map
     * @var array
     */
    private $typeMaps = [
        self::TYPE_XLSX => 'xlsx',
        self::TYPE_XLS => 'xls',
        self::TYPE_CSV => 'csv',
    ];

    public $name = 'Undefined';
    protected $rowCount = 0;
    protected $rowKeys = [];
    /**
     * @var Spreadsheet
     */
    private $_spreadsheet;

    private $_tempFile;

    /**
     * 导出格式，可选 $typeMaps
     * 默认为 self::TYPE_XLSX
     * @var int
     */
    private $_type = self::TYPE_XLSX;

    /**
     * 是否需要压缩
     * 默认为 true
     * @var bool
     */
    private $_zip = true;

    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        /*$simpleCache = new Cache();
        $simpleCache->baseCache = 'tmpCache';
        $simpleCache->defaultTtl = 1800;

        \PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);*/

        foreach (Yii::$app->getLog()->targets as $target) {
            $target->except[] = "yii\web\HeadersAlreadySentException";
        }
    }

    /**
     * @return Spreadsheet
     */
    private function getSpreadsheet()
    {
        if (!$this->_spreadsheet)
            $this->_spreadsheet = new Spreadsheet();
        return $this->_spreadsheet;
    }

    /**
     * 设置文件名
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 设置行头
     * @param $data
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setHeader($data)
    {
        $this->addRow($data);
        return $this;
    }

    /**
     * 批量添加数据
     * @param $data
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function addData($data)
    {
        foreach($data as $row) {
            $this->addRow($row);
        }
        return $this;
    }

    /**
     * 添加一行数据
     * @param $data
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function addRow($data)
    {
        if (!$this->rowKeys) {
            $this->rowKeys = array_keys($data);
        }

        $this->rowCount += 1;
        $sheet = $this->getSpreadsheet()->getActiveSheet();
        foreach($this->rowKeys as $index=>$key) {
            $value = $data[$key];
            if (is_numeric($value)) {
                $firstLetter = substr($value, 0, 1);
                // 纯数字超过10位会被Excel加E显示
                if ($value >= 1000000000 || $firstLetter == '+' || ($firstLetter == '0' && $value > 1)){
                    $sheet->setCellValueExplicitByColumnAndRow($index + 1, $this->rowCount, $value, 's');
                    continue;
                }
            } elseif ($value[0] === '=' ) { // 首字符为=号会默认触发formula，进行函数计算
                $sheet->setCellValueExplicitByColumnAndRow($index + 1, $this->rowCount, $value, 's');
                continue;
            }

            $sheet->setCellValueByColumnAndRow($index + 1, $this->rowCount, $value);
        }
        return $this;
    }

    /**
     * 设置文件类型
     * @param $type
     * @return $this
     * @throws \Exception
     */
    public function setType($type)
    {
        if (!in_array($type, array_keys($this->typeMaps))) {
            throw new \Exception('Exporter set type error: only allows these types ['.implode(', ', array_keys($this->typeMaps)).']');
        }
        $this->_type = $type;
        return $this;
    }

    /**
     * 获取文件类型字符串
     * @return mixed
     */
    private function getTypeString()
    {
        return $this->typeMaps[$this->_type];
    }

    /**
     * 设置是否需要压缩，默认为true
     * @param $needZip
     * @return $this
     */
    public function setZip($needZip)
    {
        $this->_zip = $needZip;
        return $this;
    }

    /**
     * 导出文件
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function output()
    {
        $writer = IOFactory::createWriter($this->getSpreadsheet(),  ucfirst($this->getTypeString()));

        if ($this->_zip) {
            $writer->save($this->getTempFilename());
            $this->outputZip();
        } else {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$this->getFileName($this->getTypeString()).'"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
        exit;
    }

    /**
     * 获取临时文件名，用来保存文件，后续再删除
     * @return bool|string
     */
    private function getTempFilename()
    {
        if (!$this->_tempFile)
            $this->_tempFile = tempnam(sys_get_temp_dir(), 'exporterSpreadsheet');
        return $this->_tempFile;
    }

    /**
     * 获取导出名称
     * @param $ext
     * @return string
     */
    private function getFileName($ext)
    {
        return iconv('utf-8','GBK',$this->name).'.'.strtolower($ext);
    }

    /**
     * 压缩后导出，zip压缩
     */
    public function outputZip()
    {
        $ext = $this->typeMaps[$this->_type];
        $zipFile = $this->getTempFilename().'.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE);
        $zip->addFile($this->getTempFilename(), $this->getFileName($ext));
        $zip->close();
        unlink($this->getTempFilename());

        header("Content-type: application/zip");
        header('Content-Disposition: attachment;filename="'.$this->getFileName('zip').'"');
        header('Expires:0');
        header('Pragma:public');
        header('Cache-Control: max-age=0');
        header('Content-Length: '.filesize($zipFile));
        readfile($zipFile);
        unlink($zipFile);
    }

    /**
     * 保存为文件
     *
     * author: august 2019/12/10
     * @param $path 要保存到的路径 名字默认为setName设置的值
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function saveAsFile($path)
    {
        $ext = $this->typeMaps[$this->_type];
        $file = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getFileName($ext);
        $writer = IOFactory::createWriter($this->getSpreadsheet(),  ucfirst($this->getTypeString()));

        if ($this->_zip) {
            $writer->save($this->getTempFilename());
            $zip = new \ZipArchive();
            $zip->open($file .'.zip', \ZipArchive::CREATE);
            $zip->addFile($this->getTempFilename(), $this->getFileName($ext));
            $zip->close();
            @unlink($this->getTempFilename());
        } else {
            $writer->save($file);
        }
    }

    /**
     * 获取(服务器上保存的)文件名，与saveAsFile()搭配使用
     *
     * @usage (new Exporter())->setZip($isZip)->setType($type)->setName($name)->getName($path)
     * author: august 2019/12/10
     * @return string
     */
    public function getName($path)
    {
        $file = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getFileName($this->typeMaps[$this->_type]);
        if ($this->_zip) {
            return $file . '.zip';
        } else {
            return $file;
        }
    }
}