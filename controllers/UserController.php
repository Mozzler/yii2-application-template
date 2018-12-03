<?php
namespace app\controllers;

use mozzler\auth\controllers\UserWebController as BaseController;

class UserController extends BaseController {
	
	public $modelClass = 'app\models\User';
	
}

?>