<?php
require_once 'coba.php';
class porn_filtering_controller{
	public function list_user(){
		$model = new porn_filtering_model();
		$user = $model->get_user();
		return $user;
	}
	
	public function list_website(){
		$model = new porn_filtering_model();
		$website = $model->get_website_id();
		return $website;
	}
	
	public function data_url($id_user){
		$chandra = new porn_filtering_model();
		$total_url_chandra = $chandra->total_url("BLB-15");
		$filtered_url_chandra = $chandra->total_url_filtered("BLB-15");
		$filtered_url_chandra = $filtered_url_chandra['jumlah'];
		
		$return_array = array(
			'total' => $total_url_chandra,
			'filtered' => $filtered_url_chandra,	
		);
		return $return_array;
	}
	
	
}
?>