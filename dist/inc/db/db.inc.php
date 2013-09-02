<?php
/*数据库对象的类型*/
define('DbObjectType_Table', 'table');
define('DbObjectType_Index', 'index');

/**
 * 数据库访问功能片断。
 */
interface IDbAccessEnable
{
	/**
	 * 运行Sql,以多维数组方式返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	function getData($sql, $db = null);

	/**
	 * 运行Sql,以数组方式返回结果集第一条记录。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	function getLine($sql, $db = null);
	
	/**
	 * 运行Sql,返回结果集第一条记录的第一个字段值。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功时返回一个值，失败时返回false。
	 */
	function getVar($sql, $db = null);
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 */
	function runSql($sql, $db = null);
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return Int 新添加的ID（异常为-1）。
	 */
	function insert($sql, $db = null);
}

interface IDbUtil{
    /**
    * 导入数据库。
    * @param $sql {Int} 数据定义的SQL文件。
    * @param $db {String} 数据库。
    */
    function import($sql, $db);

    /**
    * 导出数据表。
    * @param $db {String} 数据库。
    * @param $options {Array} 配置导出行为的可选项。
    * @return {String} 导出的SQL。
    */
    function export($db, $options);

	/**
	* 获取数据库对象的列表。
	* @param $db {String} 数据库。
	* @param $type {String} 数据库对象的类型。
	*/
	function getObjects($db, $type = '');
}
?>
