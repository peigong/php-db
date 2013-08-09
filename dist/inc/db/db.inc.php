<?php
/**
 * 数据库访问功能片断。
 */
interface IDbAccessEnable
{
	/**
	 * 运行Sql,以多维数组方式返回结果集。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	function getData($sql, $db = null);

	/**
	 * 运行Sql,以数组方式返回结果集第一条记录。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	function getLine($sql, $db = null);
	
	/**
	 * 运行Sql,返回结果集第一条记录的第一个字段值。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功时返回一个值，失败时返回false。
	 */
	function getVar($sql, $db = null);
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 */
	function runSql($sql, $db = null);
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return Int 新添加的ID（异常为-1）。
	 */
	function insert($sql, $db = null);
}
?>
