<?php
/**
 *  PHPExcel 功能
 * @user 罗仕辉
 * @create 2018-09-13
 */

namespace libraries;


class Excel
{
    protected $k;
    protected $objGroup;
    protected $objPHPExcel;
    protected $fields;
    protected $rowStart;

    public function __construct($k=0)
    {
        $this->setObj($k);
    }

    /**  获取实例
     * @param int $k
     * @return Excel
     */
    static public function instance($k=0)
    {
        return new static($k);
    }

    /** 实例化 PHPExcel
     * @param int $k
     * @return mixed
     */
    public function setObj($k=0)
    {
        $this->k = $k;
        if(empty($this->objGroup[$this->k])){
            $this->objGroup[$this->k] = new \PHPExcel();
        }
        $this->objPHPExcel = $this->objGroup[$this->k];

        return $this->objPHPExcel;
    }

    /** 销毁 PHPExcel
     * @param int $k
     */
    public function unsetObj($k=0)
    {
        if(isset($this->objGroup[$k])){
            unset($this->objGroup[$k]);
        }
    }

    /** 导出数据
     * @param array $list
     * @param array $fields
     * @param string $fileName
     * @param string $dir
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportData(array $list, array $fields=array(), $fileName='exportData', $dir='download/excel')
    {
        $this->setActiveSheet();    // 设置工作表
        $this->setFields($fields);  // 设置字段表头
        $this->setDataList($list,$fields);   // 设置数据列表
        $result=$this->make($fileName,$dir); // 生成文件

        return $result;
    }

    /** 导出分表数据
     * @param array $list
     * @param array $group
     * @param string $fileName
     * @param string $dir
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportGroupData(array $list, array $group=array(), $fileName='exportGroupData', $dir='download/excel')
    {
        $index=0;
        foreach($list as $key=>$data){
            $fields=array();
            if(!empty($group[$key])){
                $fields=$group[$key];
            }
            $this->setActiveSheet($index,$key);  // 设置工作表
            $this->setFields($fields);           // 设置字段表头
            $this->setDataList($data,$fields);   // 设置数据列表

            $index++;
        }
        $result=$this->make($fileName,$dir); // 生成文件

        return $result;
    }

    /** 导出配置
     * @param array $list
     * @param array $columns
     * @param string $fileName
     * @param string $dir
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportConfig(array $list, array $columns, $fileName='exportConfig', $dir='download/excel')
    {
        $this->setActiveSheet();    // 设置工作表
        $this->setHeader($columns); // 设置数据表头
        $this->setDataList($list);  // 设置数据列表
        $result=$this->make($fileName,$dir); // 生成文件

        return $result;
    }

    /** 导出所有配置
     * @param array $list
     * @param array $group
     * @param string $fileName
     * @param string $dir
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportGroupConfig(array $list, array $group, $fileName='exportGroupConfig', $dir='download/excel')
    {
        $index=0;
        foreach($list as $key=>$data){
            if(empty($group[$key])){
                continue;
            }
            $columns=$group[$key];
            $this->setActiveSheet($index,$key);  // 设置工作表
            $this->setHeader($columns);          // 设置字段表头
            $this->setDataList($data);   // 设置数据列表

            $index++;
        }
        $result=$this->make($fileName,$dir); // 生成文件

        return $result;
    }

    /** 设置工作表
     * @param int $index
     * @param string $title
     */
    public function setActiveSheet($index=0, $title='sheet1')
    {
        $this->objPHPExcel->createSheet($index);
        $this->objPHPExcel->setActiveSheetIndex($index);
        $this->objPHPExcel->getActiveSheet()->setTitle($title);
    }

    /** 设置字段表头
     * @param array $fields
     * @return mixed
     */
    public function setFields(array $fields)
    {
        $fields = array_values($fields);
        foreach($fields as $col=>$field){
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        }

        $this->rowStart = 2;
        $this->fields = $fields;

        return $this->fields;
    }

    /** 设置数据表头
     * @param array $columns
     */
    public function setHeader(array $columns)
    {
        $fields=array();
        $index=0;
        foreach($columns as $column){
            $fields[]=$column['field'];
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 1, $column['field']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 2, $column['name']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 3, $column['alias']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 4, $column['attr']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 5, $column['desc']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 6, '/');
            $index++;
        }

        $this->rowStart = 7;
        $this->fields = $fields;
    }

    /** 设置数据列表
     * @param array $list
     * @param array $fields
     * @param int $rowStart
     */
    public function setDataList(array $list, array $fields=array(), $rowStart=0)
    {
        if(empty($fields)){
            $fields = $this->fields;
        }
        if($rowStart < 1){
            $rowStart = $this->rowStart;
        }
        $list=new ListIterator($list);
        foreach($list as $k=>$row){
            if(empty($fields)){
                $fields=array_keys($row);
            }
            foreach($fields as $index=>$field){
                $pRow = $k+$rowStart;
                $value=isset($row[$field])?$row[$field]:'';
                $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, $pRow, $value);
            }
        }
    }

    /** 生成 Excel 文件
     * @param string $fileName
     * @param string $dir
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function make($fileName='excel', $dir='download/excel')
    {
        $result=false;
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $path=realpath(FCPATH.$dir);
        if(!file_exists($path)){
            mkdir($path,DIR_WRITE_MODE,true);
        }
        $file=$path.'/'.$fileName.'.xls';
        if($file){
            $result='/'.$dir.'/'.$fileName.'.xls';
            $objWriter->save($file);
        }

        return $result;
    }

    public function import()
    {

    }
}