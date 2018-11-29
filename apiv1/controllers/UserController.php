<?php
namespace app\apiv1\controllers;

use mozzler\apiauth\controllers\UserController as BaseController;

class UserController extends BaseController {
	
	public $modelClass = "app\models\User";
	
}