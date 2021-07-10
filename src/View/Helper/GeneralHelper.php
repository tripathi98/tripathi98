<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Controller\Controller;
use Cake\Controller\Component\CookieComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Network\Email;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;
use Cake\View\Helper\SessionHelper;
use Cake\Network\Session\DatabaseSession;

 
use Cake\Routing\Router;


class GeneralHelper extends Helper {

    /**
     * @getUserById
     * get User Details by Id
     */
    public function getUserById($user_id = null) {

        $usersTable = TableRegistry::get('Users');
        $users_data = $usersTable->get($user_id);

        return $users_data;
    }




	/**
     * @getRoleById
     * get User Role by Id
     */
    public function getUserName($id=null) {
        $users = TableRegistry::get('users');
        $usersData = $users->find('all')->where([
			'id' => $id,
        ])->first();

		if(!empty($usersData)){
			return $usersData->name ;
		}else{
			return '<span class="label label-danger ">Guest User</span>';
		}
    }
	 

    public function getStatus($status = null){
        switch($status) {

            case 0:
                return '<span class="label label-danger">Deleted</span>';
                break;

            case 2:
                return '<span class="label label-warning">Inactive</span>';
                break;

            case 1:
                return '<span class="label label-success">Active</span>';
                break;

            default:
                return null;
          }
    }

 
	public function getUserImage($id = null){ 
		 
		$Table = TableRegistry::get('Users');
		if(!empty($id) && $id !=0){
			$data = $Table->find('all')->select(['id','avatar'])->where(['id'=>$id])->first();
			if(!empty($data)){ 
				$val = $data->avatar; 
			}else{
				$val = '';
			}
		}else{
			$val = '';
		}
		return $val;
		 
	} 
	  
	public function getPermission($module = null,$role_id=null){ 
		 
		$Table = TableRegistry::get('Permissions');
		$response = array();
		if($module != '' &&  $role_id != ''){
			/* $data = $Table->find('all')->
			where([
				'FIND_IN_SET(\''. $role_id .'\',Permissions.role_id)',
				'Permissions.module' => $module
			])->first(); */
			
			$data = $Table->find('all')->
			where([
				'Permissions.role_id' => $role_id,
				'Permissions.module' => $module
			])->first();
			
			if(!empty($data)){ 
				$response = $data; 
			} 
		} 
		return $response;
		 
	}

  
	
	/**
     * @getRoleById
     * get User Role by Id
     */
    public function getUserRole($id=null) {
        $table = TableRegistry::get('UserRoles');
        $usersData = $table->find('all')->select(['id','role_id'])->where([
			'user_id' => $id
		])->first();

		if(!empty($usersData)){
			return $usersData->role_id ;
		}else{
			return '';
		}
    }
	/**
     * @checkOnleaveToday
     * get Leave by User id
     */
    public function checkOnleaveToday($id=null,$date=null) {
        $table = TableRegistry::get('Leaves');
		
		$res = array();
		if($id && $date){
			$Data = $table->find('all')->select(['id','date_from','date_to'])->where([
				'user_id' => $id,
				'date_from <=' => $date,
				'date_to >=' => $date 
			])->first();

			if(!empty($Data)){
				return $Data ;
			}else{
				return $res;
			}
		}else{
			return $res;
		}
    }
	
	
	/**
     * @getRoleById
     * get User Role by Id
     */
    public function getUserCountByRole($id=null) {
        $table = TableRegistry::get('UserRoles');
        $usersData = $table->find('all')->where([
			'role_id' => $id
		])
		->group( 'user_id' )
		->count();

		if(!empty($usersData)){
			return $usersData ;
		}else{
			return '0';
		}
    }
	
	public function getModulePermission($module = null,$action = null,$role_id=null){ 
		 
		$Table = TableRegistry::get('Permissions');
		$response = '0';
		if($module != '' &&  !empty($role_id) &&  $action != ''){
			 
			$module = rtrim($module, "s");
			 
			foreach($role_id as $role){
				$data = $Table->find('all')->
					where([
						'Permissions.role_id' => $role,
						'Permissions.module' => $module,
						'Permissions.'.$action => 1
					])->count();
				
				if(!empty($data)){ 
					$response += $data; 
				} 
			}   
		} 
		return $response;
		 
	}
	
	
 }
