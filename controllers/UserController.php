<?php
namespace app\controllers;

use mozzler\webauth\controllers\UserController as BaseController;

class UserController extends BaseController {
	
	public $modelClass = 'app\models\User';
	
}

?>