<?php
use Swagger\Annotations as SWG;
/**
 * @author Milos Milanovic
 * @SWG\Resource(
 *  basePath="http://localhost/api/protection",
 *  description="Manage Protections for Demo Applications",
 *  @SWG\Produces("application/json")
 * )
 *  resourcePath is detected as "/operations" based on the class-name.
 */
class protection extends main_controller{

	const BOOL_TRUE='true';

	/**
	 * @SWG\Api(
	 *   path="/add",
	 *   @SWG\Operation(
	 *     method="POST",
	 *     summary="Add Protection",
	 *     notes="",
	 *     type="void",
	 *     authorizations={},
	 *     @SWG\Parameter(
	 *       name="name",
	 *       description="Application Name.",
	 *       required=true,
	 *       type="string",
	 *       paramType="form"
	 *     ),
	 *     @SWG\Parameter(
	 *       name="enabled",
	 *       description="Is this Application Enabled?",
	 *       required=true,
	 *       type="boolean",
	 *       paramType="form"
	 *     ),
	 *     @SWG\ResponseMessage(code=200, message="Successfull Added."),
	 *     @SWG\ResponseMessage(code=405, message="Invalid Input.")
	 *   )
	 * )
	 */
	public function add(){
		$this->load->model("protection_model","lProtectionModel");
		$lProtections = protection_item::add($this->input->post('name'), $this->input->post('enabled') == protection::BOOL_TRUE ? 1 : 0 );
		if(count( $lProtections ) == 0 ){
			http_response_code ( response_codes::InvalidInput );
		}else{
			$this->lProtectionModel->add($lProtections);
		}
	}
	
	/**
	 * @SWG\Api(
	 *   path="/listAll",
	 *   @SWG\Operation(
	 *     method="GET",
	 *     summary="List All Protected Applications",
	 *     notes="Returns All Protected Applications.",
	 *     type="array",
	 *     @SWG\Items("ProtectionItem"),
	 *     authorizations={},
	 *     @SWG\Produces("application/json"),
	 *     @SWG\ResponseMessage(code=200, message="Successfull Listed."),
	 *     @SWG\ResponseMessage(code=404, message="Item not found."),
	 *     @SWG\ResponseMessage(code=405, message="Invalid Input.")
	 *   )
	 * )
	 */
	public function listAll(){
		$this->load->model("protection_model","lProtectionModel");
		$lProtections = $this->lProtectionModel->get(NULL,Item::ALL_ITEMS);
		if($lProtections != NULL){
			echo protection_item::get($lProtections);
		}
	}
	
	/**
	 * @SWG\Api(
	 *   path="/get/{id}",
	 *   @SWG\Operation(
	 *     method="GET",
	 *     summary="Get Protected Application by ID",
	 *     notes="Returns Protected Application based on ID field.",
	 *     type="array",
	 *     @SWG\Items("ProtectionItem"),
	 *     authorizations={},
	 *     @SWG\Parameter(
	 *       name="id",
	 *       description="ID of Protected Application.",
	 *       required=true,
	 *       type="integer",
	 *       paramType="path"
	 *     ),
	 *     @SWG\ResponseMessage(code=200, message="Successfull Listed."),
	 *     @SWG\ResponseMessage(code=400, message="Invalid ID supplied."),
	 *     @SWG\ResponseMessage(code=404, message="Item not found."),
	 *     @SWG\ResponseMessage(code=405, message="Invalid Input.")
	 *   )
	 * )
	 */
	public function get($id){
		$this->load->model("protection_model","lProtectionModel");
		$lProtections = $this->lProtectionModel->get($id,Item::BY_ID);
		if($lProtections != NULL){
			echo protection_item::get($lProtections);
		}
	}
/**
	 * @SWG\Api(
	 *   path="/update",
	 *   @SWG\Operation(
	 *     method="POST",
	 *     summary="Update Protected Application by ID",
	 *     notes="",
	 *     type="void",
	 *     authorizations={},
	 *     @SWG\Parameter(
	 *       name="id",
	 *       description="ID of Protected Application.",
	 *       required=true,
	 *       type="integer",
	 *       paramType="form"
	 *     ),
	 *     @SWG\Parameter(
	 *       name="name",
	 *       description="Application Name.",
	 *       required=false,
	 *       type="string",
	 *       paramType="form"
	 *     ),
	 *     @SWG\Parameter(
	 *       name="enabled",
	 *       description="Is this Application Enabled?",
	 *       required=true,
	 *       type="boolean",
	 *       paramType="form"
	 *     ),
	 *     @SWG\ResponseMessage(code=200, message="Successfull Updated."),
	 *     @SWG\ResponseMessage(code=400, message="Invalid ID supplied."),
	 *     @SWG\ResponseMessage(code=404, message="Item not found."),
	 *     @SWG\ResponseMessage(code=405, message="Invalid Input.")
	 *   )
	 * )
	 */
	public function update(){
		$this->load->model("protection_model","lProtectionModel");
		$lId = $this->input->post('id');
		$lName = $this->input->post('name');
		if ( $lName == ""){
			$lProtections = $this->lProtectionModel->get($lId,Item::BY_ID);
			foreach ( $lProtections as $lProtection ) {
				$lName = $lProtection [database_fields::COLUMN_NAME];
			}
		}
		$lProtections = protection_item::update($lId, $lName, 
			$this->input->post('enabled') == Protection::BOOL_TRUE ? 1 : 0 );
		if(count( $lProtections ) == 0 ){
			http_response_code ( ResponseCodes::InvalidInput );
		}else{
			$this->lProtectionModel->update($lProtections);
			echo protection_item::get($lProtections);
		}
	}
	/**
	 * @SWG\Api(
	 *   path="/remove/{id}",
	 *   @SWG\Operation(
	 *     method="DELETE",
	 *     summary="Remove Protected Application by ID",
	 *     notes="",
	 *     type="void",
	 *     authorizations={},
	 *     @SWG\Parameter(
	 *       name="id",
	 *       description="ID of Protected Application.",
	 *       required=true,
	 *       type="integer",
	 *       paramType="path"
	 *     ),
	 *     @SWG\ResponseMessage(code=200, message="Successfull Removed."),
	 *     @SWG\ResponseMessage(code=400, message="Invalid ID supplied."),
	 *     @SWG\ResponseMessage(code=404, message="Item not found."),
	 *     @SWG\ResponseMessage(code=405, message="Invalid Input.")
	 *   )
	 * )
	 */
	public function remove($id){
		$this->load->model("protection_model","lProtectionModel");
		$lProtections = $this->lProtectionModel->remove($id);
		if($lProtections != NULL){
			echo protection_item::get($lProtections);
		}
	}
}
?>