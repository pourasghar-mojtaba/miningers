<?php

class RecipesController extends AppController {
	
	
public $components = array('RequestHandler');

/**
* 
* 
*/
	public function index() {
		$recipes = $this->Recipe->find('all');
		$this->set(array(
		'recipes' => $recipes,
		'_serialize' => array('recipes')
		));
	}
	/**
	* 
	* @param undefined $id
	* 
*/
	public function view($id) {
		$recipe = $this->Recipe->findById($id);
		$this->set(array(
		'recipe' => $recipe,
		'_serialize' => array('recipe')
		));
	}
	/**
	* 
	* @param undefined $id
	* 
*/
	public function edit($id) {
		$this->Recipe->id = $id;
		if ($this->Recipe->save($this->request->data)) {
		$message = 'Saved';
		} else {
		$message = 'Error';
	}
	$this->set(array(
		'message' => $message,
		'_serialize' => array('message')
		));
	}
	/**
	* 
	* @param undefined $id
	* 
*/
	public function delete($id) {
		if ($this->Recipe->delete($id)) {
		$message = 'Deleted';
		} else {
		$message = 'Error';
		}
		$this->set(array(
		'message' => $message,
		'_serialize' => array('message')
		));
	}
	
	
}
?>