<?php
use Swagger\Annotations as SWG;

/**
 * @author Milos Milanovic
 * @SWG\Model(id="ProtectionItem")
 */
class protection_item extends item {

    /**
 	* @SWG\Property(
 	* name="name",
 	* type="string",
 	* description="Application Name"
 	* )
 	*/
	public $mName = NULL;
	/**
	 * @SWG\Property(
	 * name="id",
	 * type="string",
	 * description="Unique ID for Application"
	 * )
	 */
	public $mID = NULL;
	/**
	 * @SWG\Property(
	 * name="isEnabled",
	 * type="boolean",
	 * description="Is Applications Enabled"
	 * )
	 */
	public $mIsEnabled = NULL;
	// Constructor
	public function __construct() {
		$arguments = func_get_args ();
		switch (sizeof ( func_get_args () )) {
			case 1 : // JsonObject
				$this->initializeFieldsWithJson ( $arguments [0] );
				break;
			case 3 : // Name, Id, isEnabled
				$this->initializeFields ( $arguments [0], $arguments [1], $arguments [2] );
				break;
		}
	}
	private function initializeFields($name, $id, $isEnabled) {
		$this->mName = str_replace(" ", "", $name);
		$this->mID = $id;
		$this->mIsEnabled = $isEnabled;
	}
	private function initializeFieldsWithJson($jsonObject) {
		$this->initializeFields ( $jsonObject [json_fields::JSON_NAME], $jsonObject [json_fields::JSON_ID], $jsonObject [json_fields::JSON_IS_ENABLED] );
	}
	public function prepareFieldsForDB() {
		return array (
				database_fields::COLUMN_NAME => $this->mName,
				database_fields::COLUMN_ID => $this->mID,
				database_fields::COLUMN_IS_ENABLED => $this->mIsEnabled 
		);
	}
	public function prepareFieldsForJson() {
		return array (
			    json_fields::JSON_NAME => $this->mName,
				json_fields::JSON_ID => $this->mID,
				Json_fields::JSON_IS_ENABLED => $this->mIsEnabled == 1 ? true : false
		);
	}
	public static function add($name, $enabled) {
		// Unique ID is Current Generated MD5 from Current Time
		$lId = md5(time());
		$lProtections = array ();
		$lProtectionItem = new Protection_item (  
				$name, 
				$lId, 
				$enabled );
		array_push ( $lProtections, $lProtectionItem->prepareFieldsForDB () );
		return $lProtections;
	}
	public static function update($id, $name, $enabled) {
		$lProtections = array ();
		$lProtectionItem = new protection_item (  
				$name, 
				$id, 
				$enabled );
		array_push ( $lProtections, $lProtectionItem->prepareFieldsForDB () );
		return $lProtections;
	}
	public static function remove($id) {
	}
	public static function get($protections) {
		$lProtections = array ();
		foreach ( $protections as $lProtection ) {
			$lProtectionItem = new protection_item (  
					$lProtection [database_fields::COLUMN_NAME], 
					$lProtection [database_fields::COLUMN_ID], 
					$lProtection [database_fields::COLUMN_IS_ENABLED] );
			array_push ( $lProtections, $lProtectionItem->prepareFieldsForJson () );
		}
		if (count($lProtections) > 0 ) {
		return json_encode ( $lProtections, JSON_PRETTY_PRINT);
		} else {
			http_response_code ( ResponseCodes::InvalidInput );
		}
	}
}
?>
