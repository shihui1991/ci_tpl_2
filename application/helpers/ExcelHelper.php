<?php
/**
 *  PHPExcel 读取数据与导出文件
 * @author MSL
 * @create 2019-03-07
 */
namespace app\helpers;

require_once '@vendor//phpexcel/PHPExcel.php';

class ExcelHelper
{
    protected $phpExcel;
    static protected $instances; # 实例
    # 支持的文件读取与输出格式
    static public $typesToExt = array(
        'Excel2007'    => array('xlsx','xlsm','xltx','xltm'),
        'Excel5'       => array('xls','xlt'),
        'Excel2003XML' => array('xml'),
        'OOCalc'       => array('ods','ots'),
        'SYLK'         => array('slk'),
        'Gnumeric'     => array('gnumeric'),
        'HTML'         => array('html','htm'),
        'CSV'          => array('csv'),
    );

    public function __construct()
    {

    }

    /**  获取实例
     * @param string $k
     * @return static
     */
    static public function instance($k = 0)
    {
        if(empty($k)){
            $k = md5(get_called_class());
        }
        if(empty(static::$instances[$k])){
            static::$instances[$k] = new static();
        }
        return static::$instances[$k];
    }

    /** 读取所有工作表数据
     * @param string $file
     * @return array
     * @throws \PHPExcel_Reader_Exception
     */
    public function getAllSheetsData($file)
    {
        # 读取所有工作表
        $sheets = $this->getAllSheets($file);
        # 读取工作表内容
        $list = array();
        foreach($sheets as $sheet){
            # 获取名称
            $name = $sheet->getTitle();
            # 转换内容
            $data = $sheet->toArray();
            if(empty($data) || count($data) < 2){
                continue;
            }
            $list[$name] = $data;
        }

        return $list;
    }

    /** 读取所有工作表
     * @param string $file
     * @return \PHPExcel_Worksheet[]
     * @throws \PHPExcel_Reader_Exception
     * @throws \Exception
     */
    public function getAllSheets($file)
    {
        $realPath = realpath($file);
        if(false == $realPath){
            throw new \Exception('文件不存在',EXIT_USER_INPUT);
        }
        $this->phpExcel = \PHPExcel_IOFactory::load($realPath);
        $sheets = $this->phpExcel->getAllSheets();
        if(empty($sheets)){
            throw new \Exception('文件为空',EXIT_USER_INPUT);
        }

        return $sheets;
    }

    /** 数据导出到excel文件
     * @param array $list
     * @param string $file
     * @param string $saveDir
     * @param string $type
     * @return int|mixed
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function putListToFile(array $list, $file, $saveDir = '', $type = 'Excel5')
    {
        $this->phpExcel = new \PHPExcel();
        $i = 0;
        if(empty($list)){
            goto result;
        }
        foreach($this->getArrIterator($list) as $name => $data){
            # 创建工作表
            $this->createSheet($i, $name);
            # 将数据写入工作表
            $this->putListToActiveSheet($data);
            $i ++ ;
        }
        result:
        # 导出
        $res = $this->output($file,$saveDir,$type);

        return $res;
    }

    /** 创建工作表
     * @param int $index
     * @param string $name
     * @return bool
     */
    public function createSheet($index = 0, $name = '')
    {
        $name = empty($name) ? 'sheet'.$index : $name;
        # 创建工作表
        $this->phpExcel->createSheet($index);
        # 设置为活跃工作表
        $this->phpExcel->setActiveSheetIndex($index);
        # 重命名工作表
        $this->phpExcel->getActiveSheet()->setTitle($name);

        return true;
    }

    /** 将数据写入工作表
     * @param array $list
     * @return bool
     */
    public function putListToActiveSheet(array $list)
    {
        if(empty($list)){
            return true;
        }
        $row = 1;
        foreach($this->getArrIterator($list) as $data){
            $col = 0;
            foreach($this->getArrIterator($data) as $val){
                $val = sprintf('%s',$val);
                $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
                $col ++ ;
            }
            $row ++ ;
        }

        return true;
    }

    /** 写入文件或输出文件
     * @param string $file
     * @param string $type
     * @return bool
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function output($file, $saveDir = '', $type = 'Excel5')
    {
        $writer = \PHPExcel_IOFactory::createWriter($this->phpExcel, $type);
        if($saveDir){
            if(!file_exists($saveDir)){
                mkdir($saveDir,DIR_WRITE_MODE,true);
            }
            $file = $saveDir.$file;
            # 写入到文件
            $writer->save($file);
        }else{
            # 文件输出头
            $this->outputHeaderForFile($file);
            # 输出文件
            $writer->save('php://output');
        }

        return $file;
    }

     /**
     * 文件输出头
     */
    public function outputHeaderForFile($name, $size = 0)
    {
        // Redirect output to a client’s web browser (Excel5)
        header ( "Content-Type: application/octet-stream" );
        header ( "Content-Transfer-Encoding: binary" );
        Header ( "Accept-Ranges: bytes ");
        header ('Content-Disposition: attachment;filename="'.$name.'"');
        if($size > 0){
            header ( 'Content-Length: ' . $size);
        }

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        header ('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header ('Cache-Control: max-age=1');
        header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0 ");
    }

    /** 生成数组迭代器
     * @return mixed
     */
    public function getArrIterator($array = array())
    {
        return (new \ArrayObject($array))->getIterator();
    }
}