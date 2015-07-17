<?php
/**
 * @author Milos Milanovic
 */
abstract class main_controller extends CI_Controller {
	// Return Whole Post Body
	protected function getBody() {
		return file_get_contents ( 'php://input' );
	}
}
?>
