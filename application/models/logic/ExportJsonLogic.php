<?php
/**
 *  ExportJson 逻辑模型
 * @author 罗仕辉
 * @create 2019-01-14
 */

namespace models\logic;

class ExportJsonLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

    }

    /** 通用导出 JSON 方法
     * @param string $table
     * @param bool $output  true输出文件,false保存文件
     * @param string $savePath
     * @return mixed
     * @throws \Exception
     */
    public function exportJson($table,$output=true,$savePath='')
    {
        // 获取所有数据
        $list = TplLogic::instance($table)->isAlias(false)->getAll();
        if(empty($list)){
            die('配置为空');
        }

        $method = 'handleListFor'.$table;
        if(method_exists($this,$method)){
            // 特定配置按格式处理数据
            $list = $this->$method($list);
        }else{
            // 全部导出
            foreach(makeArrayIterator($list) as $i => $row){
                $list[$i] = TplLogic::instance($table)->dataModel->format($row,true);
            }
        }
        $str = json_encode($list,JSON_UNESCAPED_UNICODE);
        // 文件输出
        if($output){
            outputHeaderForFile($table.'.json');
            echo $str;
            exit;
        }
        // 生成文件
        else{
            # 默认保存目录
            if(empty($savePath)){
                $savePath = DOWNLOAD_DIR.'/configJson';
            }
            # 创建目录
            if(!file_exists($savePath)){
                mkdir($savePath,DIR_WRITE_MODE,true);
            }
            # 保存文件
            $path = $savePath.'/'.$table.'.json';
            $res = file_put_contents($path,$str);
            if($res){
                return $path;
            }else{
                return false;
            }
        }
    }

    /** 导出全部配置表 JSON
     * @throws \Exception
     */
    public function exportJsonAllToZip()
    {
        # 获取全部配置表名
        $where = array(
            array('State','eq',STATE_ON),
        );
        $orderBy = array();
        $select = array(
            'Table',
        );
        $tableList = ConfigLogic::instance()->getAll($where,$orderBy,$select);
        if(empty($tableList)){
            throw new \Exception('配置为空',EXIT_DATABASE);
        }
        # 生成 JSON 文件
        $savePath = DOWNLOAD_DIR.'/configJson/json';
        $files = array();
        foreach(makeArrayIterator($tableList) as $row){
            $files[] = $this->exportJson($row['Table'],false,$savePath);
        }
        # 生成 zip 文件
        $zipFile = DOWNLOAD_DIR.'/configJson/json.zip';
        $zipObj = new \ZipArchive();
        # 存在则覆写
        if(true !== $zipObj->open($zipFile,\ZipArchive::OVERWRITE)){
            # 不存在则创建
            if(true !== $zipObj->open($zipFile,\ZipArchive::CREATE)){
                throw new \Exception('文件创建失败',EXIT_CONFIG);
            }
        }
        # 写入文件
        foreach(makeArrayIterator($files) as $file){
            $zipObj->addFile($file,basename($file));
        }
        $zipObj->close();
        # 输出文件
        outputHeaderForFile(basename ( $zipFile ),filesize ( $zipFile ));
        @readfile ( $zipFile ); //输出文件;
        exit;
    }

    /** 处理数据 -> 矿场升级
     * @param array $list
     * @return array
     *
     * @throws \Exception
     */
    public function handleListForMineLevel(array $list)
    {
        $select = array(
            'MineId',
            'LevelStart',
            'LevelEnd',
            'MinerNum',
            'ModulusUpdPrice',
            'ModulusMining',
            'ModulusOfflineGain',
            'ModulusOreCarryTop',
            'Desc',
            'SourceId',
            'Star',
        );
        $resList = array();
        foreach(makeArrayIterator($list) as $row){
            $data = array();
            foreach($select as $field){
                $data[$field] = $row[$field];
            }
            $resList[$row['MineId']][] = TplLogic::instance('MineLevel')->dataModel->format($data,true);
        }
        // 改矿场编号为自然索引
        ksort($resList);
        $resList = array_values($resList);

        return $resList;
    }

    /** 处理数据 -> 升级价格
     * @param array $list
     * @return array
     *
     * @throws \Exception
     */
    public function handleListForLevelPrice(array $list)
    {
        $select = array(
            'MinePrice',
            'CityPrice',
        );
        $resList = array();
        foreach(makeArrayIterator($list) as $row){
            $data = array();
            foreach($select as $field){
                $data[$field] = $row[$field];
            }
            $resList[$row['Id']] = TplLogic::instance('MineLevel')->dataModel->format($data,true);
        }
        // 等级为自然索引
        ksort($resList);
        $resList = array_values($resList);

        return $resList;
    }

    /** 处理数据 -> 综合配置
     * @param array $list
     * @return array
     *
     * @throws \Exception
     */
    public function handleListForCommon(array $list)
    {
        return $list[0];
    }
}