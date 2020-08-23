<?php


namespace Sentral\Challenge;

use Exception;

class CategoryController extends Model
{

	/**
	 * Get all available categories
	 * @return array
	 */
	public function getCategories(){
		$categories = array();

		$query = "SELECT 
						category_id, 
						category_name 
					FROM category 
					WHERE 
						current = 1";
		$res = $this->db->getAll($query);

		if( !empty($res) ){
			foreach( $res as $category ){
				$categories[] = [
					'category_id' => $category['category_id'],
					'category_name' => $category['category_name']
				];
			}
		}

		return ['categories' => $categories];
	}

	/**
	 * Method responsible for adding a new category
	 * @return array
	 * @throws Exception
	 */
	public function saveCategory(){
		$new_category = array();
		$new_category['category_name'] 	= trim(isset($_REQUEST['new_category_name']) ? $_REQUEST['new_category_name'] : '');

		//double check mandatory fields
		if( !$new_category['category_name'] ){
			throw new Exception("Please, insert a valid category name");
		}

		$category_id = $this->db->insert('category', $new_category);

		if( $category_id == 0 ){
			throw new Exception("Failed to create an category");
		}


		return ['category' => ['category_id' => $category_id, 'category_name' => $new_category['category_name']]];
	}

	
}