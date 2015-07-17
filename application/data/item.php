<?php
/**
 * @author Milos Milanovic 
 */
abstract class item {
	const ALL_ITEMS = "all";
	const BY_ID = "id";
	public abstract function prepareFieldsForDB();
	public abstract function prepareFieldsForJson();
	public static abstract function add($arg0, $arg1);
	public static abstract function update($arg0, $arg1, $arg2);
	public static abstract function remove($arg0);
	public static abstract function get($arg0);
}
?>
