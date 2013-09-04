<?php
require_once(ROOT . "inc/db/db.inc.php");
require_once(ROOT . "inc/db/sqlite/pdosqlite.class.php");

/**
 * 使用数据库抽象层PDO类封装对SQLite数据库的访问。
 */
class SQLiteDbUtil implements IDbUtil{
    private $dao;
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->dao = new PDOSQLite(); 
    }
    
    /**
     * 构造函数。
     */
    function SQLiteDbUtil(){
        $this->__construct();
    }

    /*- IDbUtil 接口实现 START -*/
    /**
    * 导入数据库。
    * @param $sql {Int} 数据定义的SQL文件。
    * @param $db {String} 数据库。
    */
    public function import($sql, $db){
        //设定没有限期的执行时间。
        set_time_limit(0);
        $this->exec_sql($db, $sql);
    }

    /**
    * 导出数据表。
    * @param $db {String} 数据库。
    * @param $options {Array} 配置导出行为的可选项。
    * @return {String} 导出的SQL。
    */
    public function export($db, $options){
        $result = "/*\nSQLiteDbUtil SQL Export
-- Generated On " .date("D, j M Y H:i:s T"). "
-- Email:peigong@foxmail.com
-- blog:http://www.peigong.tk\n*/\n\n";
        $type = DbObjectType_Table;
        if (array_key_exists('type', $options) && $options['type']) {
            $type = $options['type'];
        }
        $objects = array();
        if (array_key_exists('objects', $options) && $options['objects']) {
            $objects = $options['objects'];
        }
        $rows = $this->getObjects($db, $type);
        foreach ($rows as $idx => $row) {
            $name = $row['name'];
            if (in_array($name, $objects)) {
                $t = $row['type'];
                $sql = $row['sql'];
                switch ($t) {
                    case DbObjectType_Table:
                        $result .= $this->export_table($db, $name, $sql);
                        break;
               }
            }
        }
        return $result;
    }

    /**
    * 获取数据库对象的列表。
    * @param $db {String} 数据库。
    * @param $type {String} 数据库对象的类型。
    */
    public function getObjects($db, $type = ''){
        $result = array();
        $sql = "select type, name, tbl_name, rootpage, sql from sqlite_master where 1=1";
        if (strlen($type) >０) {
            $sql .= " and type = '$type'";
        }
        $rows = $this->dao->getData($sql, $db);
        if ($rows && count($rows) > 0) {
            foreach($rows as $idx=>$row){
                if ($row['name'] != 'sqlite_sequence') {
                    array_push($result, $row);
                }
            }
        }
        return $result;
    }
    /*- IDbUtil 接口实现 END -*/
    
    /*- 私有方法 START -*/
    private function exec_sql($db, $path){
        if(is_file($path)){
            $cmd = @file_get_contents($path);
            if(strlen($cmd) > 0){
                $this->dao->runSql($cmd, $db);
            }
        }elseif(is_dir($path)){
            if($dh = opendir($path)){
                $sql_files = array();
                $sql_dirs = array();
                while(false !== ($file = readdir($dh))){
                    if($file != '.' && $file != '..'){
                        $sql_file = implode('/', array($path, $file));
                        if(is_file($sql_file)){
                            array_push($sql_files, $sql_file);
                        }elseif(is_dir($sql_file)){
                            array_push($sql_dirs, $sql_file);
                        }
                    }
                }
                // 先执行sql文件
                foreach($sql_files as $sql_file){
                    $this->exec_sql($db, $sql_file);
                }
                // 再执行sql目录
                foreach($sql_dirs as $sql_dir){
                    $this->exec_sql($db, $sql_dir);
                }
            }
        }else{
            //TODO:抛异常
        }
    }

    private function export_table($db, $name, $sql){
        $result = "";
        $result .= "DROP TABLE IF EXISTS $name;\n";
        $result .= $sql . ";\n\n";
        $rows = $this->dao->getData("select * from $name", $db);
        if ($rows && count($rows) > 0) {
            foreach($rows as $idx=>$row){
                $columns = implode(',', array_keys($row));
                $values = implode("', '", array_values($row));
                $values = "'" .$values. "'";
                $result .= "INSERT INTO $name ($columns) VALUES ($values);\n";
            }
        }
        return $result;
    }
    /*- 私有方法 END -*/
}
?>
