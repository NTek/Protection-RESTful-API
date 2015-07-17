<?php
/**
 * @author Milos Milanovic
 */
class protection_model extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	public function add($protections) {
		foreach ( $protections as $lProtection ) {
			$this->db->insert ( database_fields::TABLE_APPS, $lProtection );
			echo  $this->db->insert_id();
		}
	}
	public function update($protections) {
		foreach ( $protections as $lProtection ) {
			$this->db->where( database_fields::COLUMN_ID, $lProtection[database_fields::COLUMN_ID] );
			$this->db->update ( database_fields::TABLE_APPS, $lProtection );
		}
		return $this->get($lProtection[database_fields::COLUMN_ID], Item::BY_ID);
	}
	public function remove($id) {
		$lProtections = $this->get($id, Item::BY_ID);
		$this->db->delete(database_fields::TABLE_APPS, array(database_fields::COLUMN_ID => $id));
		return $lProtections;
	}
	public function get($id, $type) {
		switch ($type) {
			case Item::ALL_ITEMS :
				{
					$query = $this->db->get ( database_fields::TABLE_APPS );
					if ($query->result_array () != NULL) {
					return $query->result_array ();
					} else {
						http_response_code ( response_codes::ItemNotFound );
	return NULL;
					}
				}
			case Item::BY_ID :
				{
                                        // Lenght of MD5 generated string.
					if (strlen ($id) == 32) {
						$query = $this->db->get_where(database_fields::TABLE_APPS, array(database_fields::COLUMN_ID => $id));
						if ($query->result_array () != NULL) {
							return $query->result_array ();
						} else {
							http_response_code ( response_codes::ItemNotFound );
							return NULL;
						}
						
					} else {
						http_response_code ( response_codes::InvalidIDSupplied );
						return NULL;
					}
				}
		}
	}
}
?>
