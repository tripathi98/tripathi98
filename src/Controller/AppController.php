<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

use Cake\Core\Configure;
use Cake\Mailer\Email;

use Cake\ORM\TableRegistry;



/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3/en/controllers.html#the-app-controller
 */
 
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    
	
	
	public function initialize()
    {
        $this->loadComponent('Flash');
		$this->loadComponent('RequestHandler');	
		$this->loadComponent('My');
        $this->loadComponent('Auth', [
			'authenticate' => [
				'Form' => [
					'fields' => [
						'username' => 'email_or_phone'
					],
					'finder' => 'auth'
				]
			],
			'loginAction' => [
				'controller' => 'Users',
				'action' => 'login'
			],
			'loginRedirect' => [
				'controller' => 'Users',
				'action' => 'dashboard'
			],
			'storage' => 'Session',
			 //use isAuthorized in Controllers
			'authorize' => ['Controller'],
			 // If unauthorized, return them to page they were just on
			'unauthorizedRedirect' => $this->referer()
		]);
		
		/*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
		
		$this->Auth->allow(['display','home','denied']);
    }

     
	
	public function isAuthorized($user) {

        if(!empty($user))
            return true;

        return false;
    }
	
	public function beforeFilter(Event $event){
		$uId		=	$this->Auth->user('id');
		$action		=	$this->request->params['action'];
		$controller	=	$this->request->params['controller']; 
		
		$user_role = $this->getUserRole($uId);

		$userModulePermission = $roleModulePermission = $boilerModulePermission = $expellerModulePermission = $oileTankModulePermission = $waybridgeModulePermission = array();
		  
		if(!empty($user_role)){
			$userModulePermission = $this->getPermission('User',$user_role);
			$roleModulePermission = $this->getPermission('Role',$user_role);
			$boilerModulePermission = $this->getPermission('Boiler',$user_role);
			$expellerModulePermission = $this->getPermission('Expeller',$user_role);
			$oileTankModulePermission = $this->getPermission('Oil Tank System',$user_role);
			$waybridgeModulePermission = $this->getPermission('Way Bridge',$user_role);
		}
		 
		
		$allowed_action = array('display','home','login', 'register','activateaccount','forgot','resetpassword','logout','dashboard','changepassword','permissions','denied');
		
		 
		if(!in_array($action,$allowed_action) && !in_array('1',$user_role)){
			$modulePermission = $this->getModulePermission($controller,$action,$user_role);
			
			if($modulePermission < 1){ 
				return $this->redirect(array('controller' => 'Users', 'action' => 'denied'));
			}
		} 
		
		$this->set(compact('userModulePermission','roleModulePermission','boilerModulePermission','expellerModulePermission','oileTankModulePermission','waybridgeModulePermission','user_role'));

	} 
	
	/**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {	
		if(!array_key_exists('_serialize', $this->viewVars) &&
			in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
	
	public function sendMailTo($to, $subject, $message){  
		$email = new Email();
        $email->setTransport('default'); 
          
        $result = $email->setFrom([Configure::read('App.EmailFrom') => $subject])
			->setTo($to)
			->setEmailFormat('html')
			->setSubject($subject)
			->send($message);
        
        return $result;
	}
	
	 public function errorMessage($obj){
        $errors = [];
        foreach($obj as $k=>$objValue) {
            foreach ($objValue as $key=>$subObjValue) {
				  //$error .= $subObjValue.'<br>';
                if(is_array($subObjValue)){
                    $res = $this->errorMessage($subObjValue);
                }else{
                    $res = $subObjValue;
                }
                $errors[] .= $k.'-'.$res.", ";
            }
        }
        return implode(' ',array_unique($errors));
    }
	
	
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function getUserRole($id=null) {
        $table = TableRegistry::get('UserRoles');
        $usersData = $table->find('all')->select(['id','role_id'])->where([
			'user_id' => $id
		])->toArray();
		$roleIds = array();
		if(!empty($usersData)){
			
			foreach($usersData as $data){
				$roleIds[] = $data->role_id ;
			}
			return $roleIds;
		}else{
			return $roleIds;
		}
    }
	
	
	public function getPermission($module = null,$role_id=null){ 
		 
		$Table = TableRegistry::get('Permissions');
		$response = array();
		if($module != '' &&  !empty($role_id)){
			
			foreach($role_id as $role){
				$data = $Table->find('all')->
				where([
					'Permissions.role_id' => $role,
					'Permissions.module' => $module
				])->first();
				
				if(!empty($data)){ 
					$response[] = $data; 
				} 
			} 
		} 
		return $response;
		 
	} 
	
	
	public function getModulePermission($module = null,$action = null,$role_id=null){ 
		 
		$Table = TableRegistry::get('Permissions');
		$response = '0';
		if($module != '' &&  !empty($role_id) &&  $action != ''){
			
			$action = strtolower($action);
			
			if($action == 'index'){
				$action = 'can_view';
			}elseif($action == 'add'){
				$action = 'can_add';
			}elseif($action == 'edit'){
				$action = 'can_edit';
			}elseif($action == 'delete'){
				$action = 'can_delete';
			}else{
				$action = 'can_view';
			}
			
			$module = rtrim($module, "s");
			
			/* $data = $Table->find('all')->
			where([
				'FIND_IN_SET(\''. $role_id .'\',Permissions.role_id)',
				'Permissions.module' => $module,
				'Permissions.'.$action => 1
			])->count(); */
			
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
     * @checkOnleaveToday
     * get Leave by User id
     */
    public function checkHaveAccessToday($id=null,$date=null) {
        $table = TableRegistry::get('UserAccessdates');
		
		$res = array();
		if($id && $date){
			$Data = $table->find('all')->select(['id','access_from','access_to'])->where([
				'user_id' => $id,
				'access_from <=' => $date,
				'access_to >=' => $date 
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
	
	
}
