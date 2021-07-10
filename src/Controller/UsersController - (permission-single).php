<?php
namespace App\Controller;
 
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\View\ViewBuilder;
use Cake\Validation\Validator;
use Cake\View\Helper\SessionHelper;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['login', 'register','activateaccount','forgot','resetpassword','logout']); 

    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }
	
	/**
     * @Login for users
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	
	public function login(){
		  
        $userTable = TableRegistry::get('Users');
        $userRoleTable = TableRegistry::get('UserRoles');
        $user = $userTable->newEntity();

        if($this->request->is(['post','put'])){
            $record = $userTable->find()->where(['OR' => [
				'email'=>$this->request->getData()['email_or_phone'],
				'phone'=>$this->request->getData()['email_or_phone']
			]])->count();
			
            if($record > 0){
				
                $user = $this->Auth->identify();
                if ($user) { 
					 
                    $access_from = $user['access_from'];
                    $access_to = $user['access_to'];
					
                    $status = $user['status'];
                    $uid = $user['id'];
					
					$today = date("Y-m-d");
					
					$isonLeaveToday = $this->checkOnleaveToday($uid,$today);
					if(!empty($isonLeaveToday) && $uid != '1'){
						$this->Flash->error(__('You have marked as on leave today. Please try again later.'));
						return $this->redirect(['action' => 'login']);
					}
					 
					
					if($access_from <= $today && $access_to <= $today){}else{ 
						if($uid != '1'){
							$this->Flash->error(__('Your time up. Please contact to administrator.'));
							return $this->redirect(['action' => 'login']); 
						} 
					}
					//get user role
					if($uid != '' && $uid != '1'){
						
						$usersData = $userRoleTable->find('all')->where([
							'user_id' => $uid
						])->count();
						
						if($usersData < 1){
							$this->Flash->error(__('You have not asigned any role. Please contact to administrator.'));
							return $this->redirect(['action' => 'login']);
						}
					}
					
					if($status == 1){
						$this->Auth->setUser($user); 
						 
						return $this->redirect($this->Auth->redirectUrl());
						  
					}else{ 
						$this->Flash->error(__('You are not authorize. Please activate your account.'));
						return $this->redirect(['action' => 'login']);
					}
					 
					
                }else{ 
					$this->Flash->error(__('Your password is incorrect'));
                    return $this->redirect(['action' => 'login']);
                }
            }else{ 
				$this->Flash->error(__('The email address is incorrect. Please enter correct email address or create a new account with this email address'));
				
                return $this->redirect(['action' => 'login']);
            }
        }
 
        $this->set('user', $user);
    }

    public function logout()
    {
		$this->autoRender = false;
        return $this->redirect($this->Auth->logout());
    }
	
	/**
     * @Register for users
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	public function register(){ 
        $userTable = TableRegistry::get('Users');
        $user = $userTable->newEntity();
        if($this->request->is(['post','put'])){
            $record=$userTable->find()->where(['email'=>$this->request->getData()['email']])->count();
            if($record == 0){
                $password = $this->request->getData()['password'];
                $user = $userTable->patchEntity($user, $this->request->getData());
                $user->status = 2;
                
                if(!$user->getErrors()) {
                     
                    $token = $user->token =  bin2hex(openssl_random_pseudo_bytes(10));
                    if ($result = $userTable->save($user)) {
						
						$roles = array(
							'user_id' => $result->id,
							'role_id' => 3,
							'created_by' => 1,
							'status' => ACTIVE
						);
						
						$userRoleTable= TableRegistry::get('UserRoles');
						
						$user_roles = $userRoleTable->newEntity($roles);
						
						$userRoleTable->save($user_roles);
						
                        $username = ucfirst($user->name);
                         
                        $EmailTemplates= TableRegistry::get('EmailTemplates');
                        $query = $EmailTemplates->find('all')
                                ->where(['EmailTemplates.slug' => 'user_registration']);
                        $template = $query->first();
                        $userEmail = $user->email;
                        try {

                            $mailMessage = str_replace(array('{{username}}','{{email}}','{{password}}'),
                            array(ucfirst($username),$userEmail,$password),$template->description);
                            $to = $userEmail;
                            $subject = $template->subject;
                            $message = $mailMessage; 

                            if(parent::sendMailTo($to, $subject, $message)){
                                
								$this->Flash->success(__('Thank you for registering with us. Please check your email with your email and password reminder and to activate you account'));
								
                                return $this->redirect(['action' => 'login']);
                            }
                        }catch (Exception $e) {
                            $this->Flash->error(__('Activation Link could not be sent. Please contact administrator to activate your account'));
                            $this->redirect(array('controller'=>'users','action'=>'login'));
                        }
                    } else { 
						$this->Flash->error(__('The user could not be saved. Please, try again'));
                    }
                }else{  
					$this->Flash->error(__($this->errorMessage($user->getErrors())));
                }
            }else{ 
				$this->Flash->error(__('You are already registered with this email address.'));
            }

        }

        $this->set('user', $user); 
    }
	
	 /**
     * @activateAccount for users
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	public function activateaccount($token = null ,$id = null){
        $this->autoRender = false;
        $usersTable = TableRegistry::get('Users');
        $query = $usersTable->query();
        $id =   base64_decode($id);
         
		$record = $usersTable->find()->where(['token'=>$token,'id'=>$id])->count();
         
        if($record > 0){ 
			$query->update()
				->set(['token' => '',
					'status'=>'1'
					])
				->where(['id' => $id])
				->execute();
             
			$this->Flash->success(__('Account has been activated successfully, you can now login'));
								

        }else{ 
			$this->Flash->error(__('Invalid token please check your mail')); 
        }
        return $this->redirect(array('controller'=>'users','action'=>'login'));
    }
	
	/**
     * @forgot password for users
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	
	public function forgot()
    {  
        $user = TableRegistry::get('Users');
		$title = "Forgot Password";

        if($this->request->is(['post', 'put'])) {
            $emailId = $this->request->getData()["email"] ;
            $results = $user->find('all')->where(['email'=>$emailId])->first();

            if(!empty($results)){

                $username = ucfirst($results->name);
                $activation_link = Router::url('/', true).'resetpassword/'.base64_encode($results->id);
                $EmailTemplates= TableRegistry::get('EmailTemplates');
                $query = $EmailTemplates->find('all')
                        ->where(['EmailTemplates.slug' => 'forgot_password']);
                $template = $query->first();
                $userEmail = $results->email;
                try {
                    $mailMessage = str_replace(array('{{username}}', '{{activation_link}}', '{{email}}','{{password}}'), array($username,$activation_link,$userEmail),$template->description);
                    $to = $userEmail;
                    $subject = $template->subject;
                    $message = $mailMessage;

                    if(parent::sendMailTo($to, $subject, $message)){ 
						$this->Flash->success(__('Please check your email for reset Password!')); 
                    }

                    $this->redirect(array('controller'=>'users','action'=>'login'));

                }catch (Exception $e) { 
					$this->Flash->error(__('Enter correct email')); 
					
                    $this->redirect(array('controller'=>'users','action'=>'login'));
                }
            }else{ 
				$this->Flash->error(__('Email does not exists')); 
                $this->redirect(array('controller'=>'users','action'=>'login'));
            }
        }
        $this->set(compact('user','title'));
    }
	
	
	/**
     * @reset password for users
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	 
    public function resetpassword($id = null)
    {
        $initial_id = $id;
		
		$user = TableRegistry::get('Users');
        
        if ($this->request->is(['post'])) {
            $id = base64_decode($id);

            if($this->request->getData()['password'] == $this->request->getData()['password2']){
                $hasher = new DefaultPasswordHasher();

                $password = $hasher->hash($this->request->getData()['password']);

                
                $query = $user->query();
                $result = $query->update()
                    ->set(['password' => $password])
                    ->where(['id' => $id])
                    ->execute();
 
				$this->Flash->success(__('Password Updated successfully')); 
                return $this->redirect(['controller'=>'users', 'action' => 'login']);
            }else{ 
				$this->Flash->error(__('Password and Confirm Password not matched!'));  
            }
        }
        $this->set(compact('initial_id','user'));
    }
	
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        $dataList = TableRegistry::get('Users');
        $savesearch = array();
        $filters = array();
        $order = array('Users.id'=>'desc');

        $filters['Users.id !='] = 1;
		if ($this->request->is(['post', 'put'])) {
			$date_from = $this->request->getData()['date_from'];
			$date_to = $this->request->getData()['date_to'];
			
			$savesearch['date_from'] = $date_from;
			$savesearch['date_to'] = $date_to;
			
			if($date_from != '' && $date_to != ''){ 
				$filters['Users.created >='] = $date_from;
				
				$filters['Users.created <='] = $date_to;
			}else if($date_from != '' && $date_to == ''){
				$filters['Users.created >='] = $date_from;
			}else if($date_from == '' && $date_to != ''){
				$filters['Users.created <='] = $date_to;
			}  
		} 
		
        $users = $this->paginate($dataList, [
            'limit' => Configure::read('pageRecord'),
            'conditions' => [$filters],
            'contain' => ['UserRoles'=>['Roles']],
            'recursive' => 2,
            'order'=>$order
        ]);
		 
		$RoleTable= TableRegistry::get('Roles');
		$roles = $RoleTable->find('list')
						->where(['Roles.status' => '1']);
		 
		$this->set(compact('users','savesearch','roles')); 
    }

    /**
     * dashboard method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function dashboard($id = null)
    {
		 
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['UserRoles'],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    { 
		$userTable= TableRegistry::get('Users');
	 
        if ($this->request->is('post')) {
			 
			$user =	$userTable->newEntity($this->request->getData(),['validate' => 'adduser']);
			   
			$user->created_by = $this->Auth->user('id');
			$user->status = ACTIVE;
			
			$token = $user->token =  bin2hex(openssl_random_pseudo_bytes(10));
			$password = $user->password = $this->generateRandomString(8);
		  
			if(!$user->getErrors()) {
			
				if ($result = $userTable->save($user)) {
					
					$rolesData = array(
						'user_id' => $result->id,
						'role_id' => $this->request->getData()['role_id'],
						'created_by' => $this->Auth->user('id'),
						'status' => ACTIVE
					);
					
					$userRoleTable= TableRegistry::get('UserRoles');
					
					$user_roles = $userRoleTable->newEntity($rolesData);
					
					$userRoleTable->save($user_roles);
					
					$username = ucfirst($user->name);
					$activation_link = Router::url('/', true).'users/activateaccount/'.$token.'/'.base64_encode($user->id);
					$EmailTemplates= TableRegistry::get('EmailTemplates');
					$query = $EmailTemplates->find('all')
							->where(['EmailTemplates.slug' => 'user_add']);
					$template = $query->first();
					$userEmail = $user->email;
					try {

						$mailMessage = str_replace(array('{{username}}', '{{activation_link}}','{{email}}','{{password}}'),
						array(ucfirst($username),$activation_link,$userEmail,$password),$template->description);
						$to = $userEmail;
						$subject = $template->subject;
						$message = $mailMessage; 

						if(parent::sendMailTo($to, $subject, $message)){
							
							$this->Flash->success(__('The user has been saved.'));
							return $this->redirect(['action' => 'index']);
						}
					}catch (Exception $e) {
						$this->Flash->error(__('The user has been saved. But activation link could not be sent.')); 
					}  
				}else{
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			}else{
				$this->Flash->error(__($this->errorMessage($user->getErrors())));
			}
            
			return $this->redirect(['action' => 'index']);
        } 
		
		$RoleTable= TableRegistry::get('Roles');
		
		$roles = $RoleTable->find('list')
							->where(['Roles.status' => '1']);
		
		$this->set(compact('user','roles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['UserRoles'],
        ]);
		
		$role_id = 0;		
		$roleId = '';		
		if(!empty($user->user_role)){
			$role_id = $user->user_role->role_id;
			$roleId = $user->user_role->id;
		} 
		
		$leaveTable= TableRegistry::get('Leaves');
		 
        if ($this->request->is(['patch', 'post', 'put'])) {
			 
            $user = $this->Users->patchEntity($user, $this->request->getData(),['validate' => 'adduser']);
			$user->updated_by = $this->Auth->user('id');
			
			if(!$user->getErrors()) {
			
				if ($this->Users->save($user)) {
					
					// update user role
					$roleID = $this->request->getData()['roleId'];
					
					$userRoleTable= TableRegistry::get('UserRoles');
					
					if($roleID != ''){
						$userRole = $userRoleTable->get($roleID);
						
						$roles = array(
							'user_id' => $id,
							'role_id' => $this->request->getData()['role_id'], 
							'updated_by' => $this->Auth->user('id'),
							'status' => ACTIVE
						); 
						$user_roles = $userRoleTable->patchEntity($userRole,$roles);
					}else{
						$roles = array(
							'user_id' => $id,
							'role_id' => $this->request->getData()['role_id'],
							'created_by' => $this->Auth->user('id'), 
							'status' => ACTIVE
						); 
						$user_roles = $userRoleTable->newEntity($roles);
					}
					
					
					$userRoleTable->save($user_roles);
					
					//save leaves of user 
					
					
					
					if(!empty($this->request->getData()['date_from'])){
						$from_date =  $this->request->getData()['date_from'];
						 
						$leaveTable->deleteAll(['Leaves.user_id'=>$id],false); 
						
						foreach($from_date as $key=>$datefrom) {
							
							if(isset($this->request->getData()['date_from'][$key]) && $this->request->getData()['date_from'][$key] != ''){
								$date_to = $this->request->getData()['date_to'][$key];
							}else{
								$date_to = $this->request->getData()['date_from'][$key];
							}
							
							$records = array(
								'user_id' => $id,
								'date_from' =>  $datefrom,
								'date_to' => 	$date_to,
								'created_by' => $this->Auth->user('id'),
								'updated_by' => $this->Auth->user('id')
							);
							
							$leave = $leaveTable->newEntity($records);
							 
							if ($leaveTable->save($leave)) {
								$error = false;
							} 
						}
					}
					
					
					$this->Flash->success(__('The user has been saved.'));

					return $this->redirect(['action' => 'index']);
				}
			}else{
				$this->Flash->error(__($this->errorMessage($user->getErrors())));
			}
             
        }
		
		$RoleTable= TableRegistry::get('Roles');
		
		$roles = $RoleTable->find('list')
							->where(['Roles.status' => '1']);
							
		$usersLeaveData = $leaveTable->find('all')->where([
			'user_id' => $id
		])->toArray();
		 
        $this->set(compact('user','roles','role_id','roleId','usersLeaveData'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
         
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	
	/**
     * @changepassword
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	 
    public function changepassword()
    {
        $initial_id = $this->Auth->user('id');
		
		$user = TableRegistry::get('Users');
        
        if ($this->request->is(['post'])) {
             
            if($this->request->getData()['password'] == $this->request->getData()['password2']){
                $hasher = new DefaultPasswordHasher();

                $password = $hasher->hash($this->request->getData()['password']);

                
                $query = $user->query();
                $result = $query->update()
                    ->set(['password' => $password])
                    ->where(['id' => $this->Auth->user('id')])
                    ->execute();
 
				$this->Flash->success(__('Password Updated successfully')); 
                return $this->redirect(['controller'=>'users', 'action' => 'login']);
            }else{ 
				$this->Flash->error(__('Password and Confirm Password not matched!'));  
            }
        }
        $this->set(compact('initial_id','user'));
    }
	
	
	/**
     * @permission
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
	 
    public function permissions()
    {
        $rolesTable = TableRegistry::get('Roles');
		$permissionsTable = TableRegistry::get('Permissions');
		 
		$rolelist = $rolesTable->find('all')
							->where(['Roles.status' => '1','Roles.id !=' => '1']);
		
		$modules = array('User','Role','Boiler','Expeller','Oil Tank System','Way Bridge');
		
		if($this->request->is(['post','put'])) {
			 
			$permissions = $this->request->getData()['permission'];
			  
			foreach($permissions as $permission) {
				$added = isset($permission['can_add']) ? 1 : 0;
				
				$edited = isset($permission['can_edit']) ? 1 : 0;
				
				$deleted = isset($permission['can_delete']) ? 1 : 0;
				
				$can_view = isset($permission['can_view']) ? 1 : 0;
				
				$records = array(
					'role_id' => implode(',',$permission['role']),
					'module' =>  $permission['module'] ,
					'can_view' => $can_view,
					'can_add' => $added,
					'can_edit' => $edited,
					'can_delete' => $deleted
				);
				
				if(isset($permission['id']) && $permission['id'] != ''){
					
					$permissionRes = $permissionsTable->get($permission['id']);
						 
					$permissionEntity = $permissionsTable->patchEntity($permissionRes,$records);
					
				}else{
					$permissionEntity = $permissionsTable->newEntity($records); 
				} 
				
				$permissionsTable->save($permissionEntity);
				 
			}
			$this->Flash->success(__('Permission Updated successfully')); 
			return $this->redirect(['controller'=>'users', 'action' => 'permissions']);
		} 
		$this->set(compact('rolelist','modules'));
    }
	
	
	public function permissions()
    {
        $rolesTable = TableRegistry::get('Roles');
		$permissionsTable = TableRegistry::get('Permissions');
		
		$permissionsData = $permissionsTable->find('all')->toArray();
		 
		$rolelist = $rolesTable->find('all')
							->where(['Roles.status' => '1','Roles.id !=' => '1']);
		
		$modules = array('User','Role','Boiler','Expeller','Oil Tank System','Way Bridge');
		
		if($this->request->is(['post','put'])) {
			 
			$permissions = $this->request->getData()['permission'];
			  
			foreach($permissions as $permission) {
				$added = isset($permission['can_add']) ? 1 : 0;
				
				$edited = isset($permission['can_edit']) ? 1 : 0;
				
				$deleted = isset($permission['can_delete']) ? 1 : 0;
				
				$can_view = isset($permission['can_view']) ? 1 : 0;
				
				$records = array(
					'role_id' => implode(',',$permission['role']),
					'module' =>  $permission['module'] ,
					'can_view' => $can_view,
					'can_add' => $added,
					'can_edit' => $edited,
					'can_delete' => $deleted
				);
				
				if(isset($permission['id']) && $permission['id'] != ''){
					
					$permissionRes = $permissionsTable->get($permission['id']);
						 
					$permissionEntity = $permissionsTable->patchEntity($permissionRes,$records);
					
				}else{
					$permissionEntity = $permissionsTable->newEntity($records); 
				} 
				
				$permissionsTable->save($permissionEntity);
				 
			}
			$this->Flash->success(__('Permission Updated successfully')); 
			return $this->redirect(['controller'=>'users', 'action' => 'permissionsadd']);
		} 
		$this->set(compact('rolelist','modules','permissionsData'));
    }
	
	
	public function denied(){
		 
    }
	
	
	 
}
