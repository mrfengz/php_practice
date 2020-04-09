<?php
class ExporterCSV
{
    public $name = 'Undefined';
    protected $rowKeys = [];
    /**
     * @var resource
     */
    private $_handle;
    private $_tempFile;

    public function __construct()
    {
        set_time_limit(0);
    }

    private function getTempFilename()
    {
        if (!$this->_tempFile)
            $this->_tempFile = tempnam(sys_get_temp_dir(), 'exporterCSV');
        return $this->_tempFile;
    }

    /**
     * @return resource
     */
    private function getHandle()
    {
        if (!$this->_handle) {
            $this->_handle = fopen($this->getTempFilename(), 'w');
        }
        return $this->_handle;
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
     * @return $this
     */
    public function writeBom()
    {
        fwrite($this->getHandle(), "\xEF\xBB\xBF");
        return $this;
    }

    /**
     * 设置行头
     * @param $data
     * @return $this
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
     */
    public function addRow($data)
    {
        if (!$this->rowKeys) {
            $this->rowKeys = array_keys($data);
        }

        $fields = [];
        foreach($this->rowKeys as $key) {
            $fields[] = $data[$key];
        }
        fputcsv($this->getHandle(), $fields);
        return $this;
    }

    public function output()
    {
        $zipFile = $this->getTempFilename().'.zip';
        $zip = new ZipArchive();
        $zip->open($zipFile, ZipArchive::CREATE);
        $zip->addFile($this->getTempFilename(), iconv('utf-8','GBK',$this->name).'.csv');
        $zip->close();

        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment;filename="'.$this->name.'.zip"');
        header('Expires:0');
        header('Pragma:public');
        header('Cache-Control: max-age=0');
        header('Content-Length: '.filesize($zipFile));
        fclose($this->getHandle());
        readfile($zipFile);
        unlink($this->getTempFilename());
        unlink($zipFile);
    }

    public function bundleOutput($files = [])
    {
        if ($files)
        {
            $zipFile    = $this->getTempFilename() . '.zip';
            $zip        = new ZipArchive();
            $zip->open($zipFile, ZipArchive::CREATE);
            foreach ($files as $vFile) {
                $zip->addFile($vFile->getTempFilename(), iconv('utf-8', 'GBK', $vFile->name) . '.csv');
            }
            $zip->close();

            header("Content-type: application/octet-stream");
            header('Content-Disposition: attachment;filename="' . $this->name . '.zip"');
            header('Expires:0');
            header('Pragma:public');
            header('Cache-Control: max-age=0');
            header('Content-Length: '.filesize($zipFile));
            fclose($this->getHandle());
            readfile($zipFile);
            unlink($this->getTempFilename());
            unlink($zipFile);
        }
    }
}