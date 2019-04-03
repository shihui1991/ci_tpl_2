<?php
/**
 *  PHPExcel 功能
 * @author 罗仕辉
 * @create 2018-09-13
 */

namespace libraries;

class Excel
{
    static protected $objs;
    protected $objPHPExcel;
    protected $fields;
    protected $rowStart;

    public function __construct()
    {
        $this->objPHPExcel = new \PHPExcel();
    }

    /**  获取实例
     * @param int $k
     * @return Excel
     */
    static public function instance($k=0)
    {
        if(empty(static::$objs[$k])){
            static::$objs[$k] = new static();
        }
        return static::$objs[$k];
    }

    /** 销毁实例
     * @param int $k
     */
    public function _unset($k=0)
    {
        if(isset(static::$objs[$k])){
            unset(static::$objs[$k]);
        }
    }

    /** 导出数据
     * @param array $list
     * @param array $fields
     * @param string $fileName
     * @param bool $output
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportData(array $list, array $fields=array(), $fileName='exportData', $output=true)
    {
        $this->setActiveSheet(0,$fileName);    // 设置工作表
        $this->setFields($fields);  // 设置字段表头
        $this->setDataList($list,$fields);   // 设置数据列表
        // 直接文件输出
        if($output){
            $this->output($fileName);
        }
        // 生成文件
        else{
            $result=$this->make($fileName);
            return $result;
        }
    }

    /** 导出分表数据
     * @param array $list
     * @param array $group
     * @param string $fileName
     * @param bool $output
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportGroupData(array $list, array $group=array(), $fileName='exportGroupData', $output=true)
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
        // 直接文件输出
        if($output){
            $this->output($fileName);
        }
        // 生成文件
        else{
            $result=$this->make($fileName);
            return $result;
        }
    }

    /** 导出配置
     * @param array $list
     * @param array $columns
     * @param string $fileName
     * @param bool $output
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportConfig(array $list, array $columns, $fileName='exportConfig', $output=true)
    {
        $this->setActiveSheet(0,$fileName);    // 设置工作表
        $this->setHeader($columns); // 设置数据表头
        $this->setDataList($list);  // 设置数据列表
        // 直接文件输出
        if($output){
            $this->output($fileName);
        }
        // 生成文件
        else{
            $result=$this->make($fileName);
            return $result;
        }
    }

    /** 导出所有配置
     * @param array $list
     * @param array $group
     * @param string $fileName
     * @param bool $output
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportGroupConfig(array $list, array $group, $fileName='exportGroupConfig', $output=true)
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
        // 直接文件输出
        if($output){
            $this->output($fileName);
        }
        // 生成文件
        else{
            $result=$this->make($fileName);
            return $result;
        }
    }


    /** 设置工作表
     * @param int $index
     * @param string $title
     * @throws \PHPExcel_Exception
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
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 6, $column['rules']);
            $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 7, '/');
            $index++;
        }

        $this->rowStart = 8;
        $this->fields = $fields;
    }

    /** 设置数据列表
     * @param array $list
     * @param array $fields
     * @param int $rowStart
     */
    public function setDataList(array $list, array $fields=array(), $rowStart=0)
    {
        if(empty($list)){
            return array();
        }
        if(empty($fields)){
            $fields = $this->fields;
        }
        if($rowStart < 1){
            $rowStart = $this->rowStart;
        }
        foreach(makeArrayIterator($list) as $k => $row){
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
     * @return bool|string
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function make($fileName='excel')
    {
        $result=false;
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $path= DOWNLOAD_DIR.'/excel';
        $realpath=FCPATH.$path;
        $fileName .= '_'.date('YmdHis');
        $file=$realpath.'/'.$fileName.'.xls';
        if($file){
            $result='/'.$path.'/'.$fileName.'.xls';
            $objWriter->save($file);
        }

        return $result;
    }

    /**  直接输出
     * @param string $fileName
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function output($fileName='excel')
    {
        $fileName .= '_'.date('YmdHis');
        outputHeaderForFile($fileName.'.xls'); # 文件输出头

        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /** 获取所有工作表
     * @param string $file
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getAllSheets($file)
    {
        $realPath=realpath($file);
        if(false == $realPath){
            throw new \Exception('文件不存在',EXIT_USER_INPUT);
        }
        $this->objPHPExcel=\PHPExcel_IOFactory::load($realPath);
        $sheets=$this->objPHPExcel->getAllSheets();
        if(empty($sheets)){
            throw new \Exception('文件为空',EXIT_USER_INPUT);
        }

        return $sheets;
    }

    /** 获取所有工作表内容
     * @param string $file
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getAllSheetsDataList($file)
    {
        $sheets = $this->getAllSheets($file);
        $result=array();
        foreach(makeArrayIterator($sheets) as $sheet){
            $sheetName=$sheet->getTitle();
            $list=$sheet->toArray();

            $result[$sheetName]=$list;
        }

        return $result;
    }

    /** 整理数据
     * @param array $dataList
     * @return array
     */
    public function makeDataList(array $dataList)
    {
        // 所有字段
        $fields=$dataList[0];
        // 过滤空字段
        foreach ($fields as $i=>$field) {
            $field = trim($field);
            if (empty($field)) {
                unset($fields[$i]);
                continue;
            }
        }
        // 整理数据列表
        unset($dataList[0]);
        $list=array();
        if(!empty($dataList)){
            foreach(makeArrayIterator($dataList) as $data){
                // 过滤空数据
                $temp=array_filter($data);
                if(empty($temp)){
                    continue;
                }
                // 取字段列数据
                $row = array();
                foreach($fields as $i=>$field){
                    $row[$field] = $data[$i];
                }
                $list[]=$row;
            }
        }
        $result=array(
            'fields'=>$fields,
            'list'=>$list,
        );

        return $result;
    }

    /** 整理配置数据
     * @param array $dataList
     * @return array
     */
    public function makeConfigDataList(array $dataList)
    {
        $fields  = $dataList[0];  // 所有字段
        $names   = $dataList[1];  // 所有字段名称
        $aliases = $dataList[2];  // 所有字段映射
        $attrs   = $dataList[3];  // 所有字段属性
        $descs   = $dataList[4];  // 所有字段描述
        $ruleses = $dataList[5];  // 所有字段验证规则
        $columns=array();   // 所有字段详情
        foreach ($fields as $i=>$field){
            $field = trim($field);
            if(empty($field)){
                // 过滤空字段
                unset($fields[$i]);
                continue;
            }
            $columns[$field]=array(
                'field' => $field,
                'name'  => $names[$i],
                'alias' => $aliases[$i],
                'attr'  => $attrs[$i],
                'desc'  => $descs[$i],
                'rules' => $ruleses[$i],
            );
        }
        // 整理数据列表
        unset(
            $dataList[0],
            $dataList[1],
            $dataList[2],
            $dataList[3],
            $dataList[4],
            $dataList[5],
            $dataList[6]
        );
        $list=array();
        if(!empty($dataList)){
            foreach(makeArrayIterator($dataList) as $data){
                // 过滤空数据
                $temp=array_filter($data);
                if(empty($temp)){
                    continue;
                }
                // 取字段列数据
                $row = array();
                foreach($fields as $i=>$field){
                    $row[$field] = $data[$i];
                }

                $list[]=$row;
            }
        }

        $result=array(
            'columns'=>$columns,
            'list'=>$list,
        );

        return $result;
    }

    /** 获取所有数据表
     * @param string $file
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getAllDataList($file)
    {
        $sheets = $this->getAllSheets($file);
        $result=array();
        foreach(makeArrayIterator($sheets) as $sheet){
            $dataList=array_filter($sheet->toArray()); //  工作表内容
            if(empty($dataList)){
                continue;
            }
            $array1=array_filter($dataList[0]);
            if(empty($array1)){
                continue;
            }
            $sheetName=$sheet->getTitle(); // 工作表名称
            $dataList=$this->makeDataList($dataList);
            $result[$sheetName]=$dataList;
        }

        return $result;
    }

    /** 获取所有配置工作表
     * @param string $file
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getAllConfigDataList($file)
    {
        $sheets = $this->getAllSheets($file);
        $result=array();
        foreach(makeArrayIterator($sheets) as $sheet){
            $dataList=array_filter($sheet->toArray()); //  工作表内容
            if(empty($dataList)){
                continue;
            }
            $array1=array_filter($dataList[0]);
            if(empty($array1)){
                continue;
            }

            $sheetName=$sheet->getTitle(); // 工作表名称
            $dataList = $this->makeConfigDataList($dataList);
            $result[$sheetName]=$dataList;
        }

        return $result;
    }

    /** 通过工作表名获取数据
     * @param string $file
     * @param string $sheetName
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getOneSheetDataListByName($file, $sheetName)
    {
        $realPath=realpath($file);
        if(false == $realPath){
            throw new \Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        $this->objPHPExcel=\PHPExcel_IOFactory::load($realPath);
        $sheet=$this->objPHPExcel->getSheetByName($sheetName);
        if(empty($sheet)){
            throw new \Exception('工作表不存在',EXIT_UNKNOWN_FILE);
        }
        $list=array_filter($sheet->toArray()); //  工作表内容
        if(empty($list)){
            throw new \Exception('工作表为空',EXIT_USER_INPUT);
        }
        $array1=array_filter($list[0]);
        if(empty($array1)){
            throw new \Exception('工作表为空',EXIT_USER_INPUT);
        }

        return $list;
    }

    /**  通过工作表名获取数据表
     * @param string $file
     * @param string $sheetName
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getOneDataListByName($file, $sheetName)
    {
        $dataList=$this->getOneSheetDataListByName($file, $sheetName);
        $result=$this->makeDataList($dataList);

        return $result;
    }

    /** 通过工作表名获取配置数据
     * @param string $file
     * @param string $sheetName
     * @return array
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getOneConfigDataListByName($file, $sheetName)
    {
        $dataList=$this->getOneSheetDataListByName($file, $sheetName);
        $result = $this->makeConfigDataList($dataList);
        
        return $result;
    }
}