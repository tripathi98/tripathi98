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
					 
					$isAcsessToday = $this->checkHaveAccessToday($uid,$today);
 
					if(empty($isAcsessToday) && $uid != '1'){
						$this->Flash->error(__('Your time up. Please contact to administrator.'));
						return $this->redirect(['action' => 'login']); 
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
		$leaveTable= TableRegistry::get('Leaves');
		
        $savesearch = array();
        $filters = array();
        $order = array('Users.id'=>'desc');

        $filters['Users.id !='] = 1;
		if ($this->request->is(['post', 'put', 'ajax'])) {
			 
			$date_from = $this->request->getData()['date_from'];
			$date_to = $this->request->getData()['date_to'];
			
			$role_id_filter = $this->request->getData()['role_id_filter'];
			$module_filer = $this->request->getData()['module_filer'];
			$status_id = $this->request->getData()['status_id'];
			
			$savesearch['date_from'] = $date_from;
			$savesearch['date_to'] = $date_to;
			$savesearch['role_id_filter'] = $role_id_filter;
			$savesearch['module_filer'] = $module_filer;
			$savesearch['status_id'] = $status_id;
			
			$user_roles = TableRegistry::get('UserRoles');
			
			if(!empty($role_id_filter)){  
				$role_id_filter = array_unique($role_id_filter);
				$userIds = array();
				foreach($role_id_filter as $vals){ 
				 
					$data = $user_roles->find()
						->where([
							'role_id' => $vals
						])
						->select(['user_id'])
						->group(['user_id'])
						->toArray();
						 
					if(!empty($data)){
						foreach($data as $resl){  
							$userIds[] = $resl->user_id; 
						}
					}
				}
				
				if(!empty($userIds)){ 
					$userIds = array_unique($userIds);
					$filters['Users.id IN'] = $userIds; 
				} 
			}
			
			$today = date("Y-m-d");
			$userIdss = array();
			
			if(!empty($status_id)){ 
				foreach($status_id as $vals){ 
					if($vals == '1'){
						$filters['Users.status'] = $vals; 
					}
					if($vals == '3'){ 
						$usersData = $leaveTable->find('all')
							->where(['Leaves.date_from <=' => $today,'Leaves.date_to >=' => $today])
							->select(['Leaves.user_id'])
							->toArray();
							
						if(!empty($usersData)){
							foreach($usersData as $resl){  
								$userIdss[] = $resl->user_id; 
							}
						}

						if(!empty($userIdss)){ 
							$userIdss = array_unique($userIdss);
							$filters['Users.id IN'] = $userIdss; 
						}  
					}
					if($vals == '4'){
						$usersData = $leaveTable->find('all')
							->where(['Leaves.date_to >=' => $today])
							->select(['Leaves.user_id'])
							->toArray();
							
						if(!empty($usersData)){
							foreach($usersData as $resl){  
								$userIdss[] = $resl->user_id; 
							}
						}

						if(!empty($userIdss)){ 
							$userIdss = array_unique($userIdss);
							$filters['Users.id IN'] = $userIdss; 
						} 
					}
				}   
			}
			 
			if(!empty($module_filer)){ 
				$permissions = TableRegistry::get('Permissions');
				
				$roleIds = array();
				
				foreach($module_filer as $val){ 
					$query = $permissions->find()
						->where([
							'module' => $val,
							'OR' => [['can_view' => 1], ['can_add' => 1], ['can_edit' => 1], ['can_delete' => 1]],
						])
						->select(['role_id'])->toArray();
					 
					if(!empty($query)){
						foreach($query as $res){ 
							$roleId = explode(',',$res->role_id);
							
							$roleIds = array_merge($roleIds, $roleId); 
							
						}
					}
				} 
				
				$roleIds = array_unique($roleIds);
				 
				if(!empty($roleIds)){ 
					$userIdsa = array();
					foreach($roleIds as $vals){ 
					 
						$data = $user_roles->find()
							->where([
								'role_id' => $vals
							])
							->select(['user_id'])
							->group(['user_id'])
							->toArray();
							 
						if(!empty($data)){
							foreach($data as $resl){  
								$userIdsa[] = $resl->user_id; 
							}
						}
					}
					
					if(!empty($userIdsa)){ 
						$userIdsa = array_unique($userIdsa);
						$filters['Users.id IN'] = $userIdsa; 
					}
					
				} 
			}
			
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
            'contain' => ['UserAccessdates','Leaves','UserRoles'=>['Roles']],
            'recursive' => 2,
            'order'=>$order
        ]);
		 
		$RoleTable= TableRegistry::get('Roles');
		 
		$roles = $RoleTable->find('all')
							->where(['Roles.status' => '1','Roles.id !=' => '1'])->toArray();
		 			
		$modules = array('User'=>'User','Role'=>'Role','Boiler'=>'Boiler','Expeller'=>'Expeller','Oil Tank System'=>'Oil Tank System','Way Bridge'=>'Way Bridge');
		
		
		/* Leave data */
		 
		//$leave_filters['Leaves.date_from >='] = date("Y-m-d");
		$leave_filters  = array();
		$orders = array('Leaves.id'=>'asc');
		
		$leavesArr = $this->paginate($leaveTable, [
            'limit' => Configure::read('pageRecord'),
            'contain' => ['Users' => function($q) {
				return $q
					->select([
							'Users.id','Users.name'
				]);
			}],  
            'conditions' => [$leave_filters],  
            'order'=>$orders
        ]);
		
		$usersList = $dataList->find('list')
						->where(['Users.status' => '1','Users.id !=' => '1']);
		 
		$this->set(compact('users','savesearch','roles','modules','leavesArr','usersList')); 
    }

    
	/**
     * profile method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function profile()
    {
        
		$userTable= TableRegistry::get('Users');
		$user = $userTable->get($this->Auth->user('id'), [
            'contain' => ['UserRoles'],
        ]);
		 
		$leaveTable= TableRegistry::get('Leaves');
		
		if($this->request->is(['patch', 'post', 'put'])){

            $user = $userTable->patchEntity($user, $this->request->getData(),['validate'=>'myaccount']);

            if(empty($this->request->getData()['avatar']['name']))
			{
				unset($user->avatar);
			}
			else
			{
				$imageData = $this->request->getData()['avatar'];
				$img = $this->My->uploadfile($imageData, 'user');
				$user->avatar = $img;
			}
			unset($user->status);
			unset($user->password);
			 
            if ($userTable->save($user)) {
                 
				$this->Flash->success(__('Profile updated successfully')); 

                return $this->redirect(['action'=>'profile']);
				
            }else{  
                $this->Flash->error(__('Something went wrong.Please try again.'));

                return $this->redirect(['action'=>'profile']);
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
		$this->autoRender = false;
		$userTable= TableRegistry::get('Users');
		
		$userAccessTable= TableRegistry::get('UserAccessdates');
	 
        if ($this->request->is('ajax')) { 	
			$user =	$userTable->newEntity($this->request->getData(),['validate' => 'adduser']);
			   
			$user->created_by = $this->Auth->user('id');
			$user->status = ACTIVE;
			
			$token = $user->token =  bin2hex(openssl_random_pseudo_bytes(10));
			$password = $user->password = $this->generateRandomString(8);
		   
			if(!$user->getErrors()) {
			
				if ($result = $userTable->save($user)) {
					
					$role_ids = $this->request->getData()['role_id'];
			 	 
					if(!empty($role_ids)){
						foreach($role_ids as $rlid){
							$rolesData = array(
								'user_id' => $result->id,
								'role_id' => $rlid,
								'created_by' => $this->Auth->user('id'),
								'status' => ACTIVE
							);
							
							$userRoleTable= TableRegistry::get('UserRoles');
							
							$user_roles = $userRoleTable->newEntity($rolesData);
							
							$userRoleTable->save($user_roles);
						}
					}
					
					//save access date of user 
					 
					if(!empty($this->request->getData()['access_from'])){
						$access_from =  $this->request->getData()['access_from'];
						
						 
						foreach($access_from as $key=>$datefrom) {
							
							if(isset($this->request->getData()['access_from'][$key]) && $this->request->getData()['access_from'][$key] != ''){
								$access_to = $this->request->getData()['access_to'][$key];
							}else{
								$access_to = $this->request->getData()['access_from'][$key];
							}
							
							$record = array(
								'user_id' => $result->id,
								'access_from' =>  $datefrom,
								'access_to' => 	$access_to,
								'created_by' => $this->Auth->user('id') 
							);
							 
							$userAccess = $userAccessTable->newEntity($record);
							$userAccessTable->save($userAccess);
							 
						}
					}
					
					$username = ucfirst($user->name);
					$activation_link = Router::url('/', true).'users/activateaccount/'.$token.'/'.base64_encode($user->id);
					$EmailTemplates= TableRegistry::get('EmailTemplates');
					$query = $EmailTemplates->find('all')
							->where(['EmailTemplates.slug' => 'user_add']);
					$template = $query->first();
					$userEmail = $user->email;
					  
					$mailMessage = str_replace(array('{{username}}', '{{activation_link}}','{{email}}','{{password}}'),
					array(ucfirst($username),$activation_link,$userEmail,$password),$template->description);
					$to = $userEmail;
					$subject = $template->subject;
					$message = $mailMessage; 
					
					try {
						parent::sendMailTo($to, $subject, $message);
						
						$response = array(
							'status' => '200',
							'msg' => 'User added successfully'
						);
						
						$this->Flash->success(__('User added successfully'));
						
					}catch (Exception $e) { 
						$response = array(
							'status' => '500',
							'msg' => 'The user has been saved. But activation link could not be sent'
						);
					} 
				}else{
					$response = array(
						'status' => '500',
						'msg' => 'The user could not be saved. Please, try again'
					);
				}
			}else{
				$response = array(
					'status' => '500',
					'msg' => $this->errorMessage($user->getErrors())
				); 
			}

			echo json_encode($response);die;
        }  
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
		$this->autoRender = false;
		 
		$leaveTable= TableRegistry::get('Leaves');
		
		$userAccessTable= TableRegistry::get('UserAccessdates');
		 
        if ($this->request->is(['patch', 'post', 'put','ajax'])) {
			 
			$user = $this->Users->get($this->request->getData()['id'], [
				'contain' => ['UserRoles'],
			]);
			  
            $user = $this->Users->patchEntity($user, $this->request->getData(),['validate' => 'adduser']);
			$user->updated_by = $this->Auth->user('id');
			
			if(!$user->getErrors()) {
			
				if ($this->Users->save($user)) {
					
					// update user role
					
					$role_ids = $this->request->getData()['role_id'];
					
					$userRoleTable= TableRegistry::get('UserRoles');
			 	 
					if(!empty($role_ids)){
						$userRoleTable->deleteAll(['UserRoles.user_id'=>$this->request->getData()['id']],false);
						
						foreach($role_ids as $rlid){
							$rolesData = array(
								'user_id' => $this->request->getData()['id'],
								'role_id' => $rlid,
								'created_by' => $this->Auth->user('id'),
								'updated_by' => $this->Auth->user('id'),
								'status' => ACTIVE
							);
							 
							$user_roles = $userRoleTable->newEntity($rolesData);
							
							$userRoleTable->save($user_roles);
						}
					}
					  
					//save access date of user 
					 
					if(!empty($this->request->getData()['access_from'])){
						$access_from =  $this->request->getData()['access_from'];
						
						$userAccessTable->deleteAll(['UserAccessdates.user_id'=>$this->request->getData()['id']],false); 
						
						foreach($access_from as $key=>$datefrom) {
							
							if(isset($this->request->getData()['access_from'][$key]) && $this->request->getData()['access_from'][$key] != ''){
								$access_to = $this->request->getData()['access_to'][$key];
							}else{
								$access_to = $this->request->getData()['access_from'][$key];
							}
							
							$record = array(
								'user_id' => $this->request->getData()['id'],
								'access_from' =>  $datefrom,
								'access_to' => 	$access_to,
								'created_by' => $this->Auth->user('id'),
								'updated_by' => $this->Auth->user('id')
							);
							 
							$userAccess = $userAccessTable->newEntity($record);
							$userAccessTable->save($userAccess);
							 
						}
					} 
					 
					$response = array(
						'status' => '200',
						'msg' => 'User added successfully'
					);
						 
					$this->Flash->success(__('The user has been saved.')); 
				}
			}else{
				$response = array(
					'status' => '500',
					'msg' => $this->errorMessage($user->getErrors())
				); 
			}
             
        }
		
		echo json_encode($response);die;
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
		 			
		$savesearch = array();
		 
		if($this->request->is(['post','put'])) {
			$module_filer = $this->request->getData()['module_filter'];
			$status_id = $this->request->getData()['status_id'];
			
			$savesearch['module_filer'] = $module_filer;
			$savesearch['status_id'] = $status_id;
			 
			$filters = array();
			
			if(!empty($module_filer)){ 
				foreach($module_filer as $val){ 
					//$filters['OR'][]['Permissions.module'] = $val; 
					$filters['Permissions.module'] = $val; 
					$filters['OR'][]['Permissions.can_view'] = 1; 
					$filters['OR'][]['Permissions.can_edit'] = 1;
					$filters['OR'][]['Permissions.can_delete'] = 1;
					$filters['OR'][]['Permissions.can_add'] = 1; 
				}	
			}else{
			
				if(!empty($status_id)){ 
					foreach($status_id as $vals){ 
						if($vals == '1'){
							$filters['OR'][]['Permissions.can_view'] = 1; 
						}
						else if($vals == '2'){
							$filters['OR'][]['Permissions.can_edit'] = 1;
						}
						else if($vals == '3'){
							$filters['OR'][]['Permissions.can_delete'] = 1;
						}
						else if($vals == '4'){
							$filters['OR'][]['Permissions.can_add'] = 1;
						}
					}   
				}
			}
			 
			$permissionsData = $rolesTable->find('all')
				->where(['Roles.status' => '1','Roles.id !=' => '1'])
				->matching('Permissions', function(\Cake\ORM\Query $q) use($filters) {
					$res =  $q 
						->where($filters);
					return $res;
				}) 
				->contain('Permissions') 
				->group('Roles.id') 
				->toArray();			
			  
		}else{
			$permissionsData = $rolesTable->find('all')
				->where(['Roles.status' => '1','Roles.id !=' => '1'])
				->matching('Permissions') 
				->contain('Permissions') 
				->group('Roles.id') 
				->toArray();
				
		}  
		
		$this->set(compact('rolelist','modules','permissionsData','savesearch'));
    }
	
	
	public function denied(){
		 
    }
	
	public function deletepermission($id = null){
		$this->autoRender = false;
		if($id) {  
			$permissionsTable = TableRegistry::get('Permissions');
			  
			$permissionsTable->deleteAll(['Permissions.role_id'=>$id],false);
			$this->Flash->success(__('Permission deleted successfully')); 
			return $this->redirect(['controller'=>'users', 'action' => 'permissions']);
			 
		}
    }
	
	
	public function editdata(){
		$this->viewBuilder()->setLayout(''); 
		$userTable= TableRegistry::get('Users');
		
		$leaveTable= TableRegistry::get('Leaves');
		
		$rolesTable = TableRegistry::get('Roles');
		
		$userAccessTable= TableRegistry::get('UserAccessdates');
	 
        if ($this->request->is('ajax')) {
			$id = $this->request->getData()['id'];
			 
			$user = $this->Users->get($id, [
				'contain' => ['UserRoles'],
			]); 
			  
			$usersAccessData = $userAccessTable->find('all')->where([
				'user_id' => $id
			])->toArray(); 
			
			$roleIds = parent::getUserRole($id);
			
			$permissionsData = $rolesTable->find('all')
				->where(['Roles.id IN' => $roleIds])
				->matching('Permissions') 
				->contain('Permissions') 
				->group('Roles.id') 
				->toArray();  
		}
		
		$RoleTable= TableRegistry::get('Roles');
		 
		$roles = $RoleTable->find('all')
							->where(['Roles.status' => '1','Roles.id !=' => '1'])->toArray();
		 			
		$modules = array('User'=>'User','Role'=>'Role','Boiler'=>'Boiler','Expeller'=>'Expeller','Oil Tank System'=>'Oil Tank System','Way Bridge'=>'Way Bridge');
		 
		$this->set(compact('user','id','roles','modules','usersAccessData','usersLeaveData','permissionsData'));
	} 

	  
	public function permissionadd(){
		$this->autoRender = false;
		
		$permissionsTable = TableRegistry::get('Permissions');
		$rolesTable = TableRegistry::get('Roles');
	 
        if ($this->request->is('ajax')) { 	
			 
			$role = $this->request->getData()['role'];
			$permissions = $this->request->getData()['permission'];
			
			$error = true;
			 
			if(isset($this->request->getData()['added_role'])){
				$roleId = $this->request->getData()['added_role'];
				$rolesTable->updateAll(['name' => $role], ['id' => $roleId]);
			}else{
				$roleData = $rolesTable->find('all')
					->where([
						'name' => $role
					])
					->first();
					 
				if(!empty($roleData)){
					$roleId = $roleData->id;
				}else{
					$record = array(
						'name' => $role,
						'status' =>  1, 
						'created_by' => $this->Auth->user('id') 
					);
					 
					$roledatas = $rolesTable->newEntity($record);
					$res = $rolesTable->save($roledatas);
					$roleId = $res->id;
				}
			}
			
			$permissionsTable->deleteAll(['Permissions.role_id'=>$roleId],false);
			 
			foreach($permissions as $permission) {
				$added = isset($permission['can_add']) ? 1 : 0;
				
				$edited = isset($permission['can_edit']) ? 1 : 0;
				
				$deleted = isset($permission['can_delete']) ? 1 : 0;
				
				$can_view = isset($permission['can_view']) ? 1 : 0;
				 
				$records = array(
					'role_id' => $roleId,
					'module' =>  $permission['module'] ,
					'can_view' => $can_view,
					'can_add' => $added,
					'can_edit' => $edited,
					'can_delete' => $deleted
				);
				   
				$permissionEntity = $permissionsTable->newEntity($records);
				
				if($permissionsTable->save($permissionEntity)){
					$error = true;
				}
				 
			}
		   
			if($error) { 
				$response = array(
					'status' => '200',
					'msg' => 'Permission added successfully'
				);
			}else{
				$response = array(
					'status' => '500',
					'msg' => "Permission not added. please try again."
				); 
			}

			echo json_encode($response);die;  
        }  
    }
	
	
	public function editpermission(){
		$this->viewBuilder()->setLayout(''); 
		
		$rolesTable = TableRegistry::get('Roles');
		
		$permissionsTable = TableRegistry::get('Permissions');
		 
		$rolelist = $rolesTable->find('all')
							->where(['Roles.status' => '1','Roles.id !=' => '1']);
		
		$modules = array('User','Role','Boiler','Expeller','Oil Tank System','Way Bridge');
		 
        if ($this->request->is('ajax')) {
			$id = $this->request->getData()['id'];
			 
			$permissionsData = $rolesTable->find('all')
				->where(['Roles.id' => $id])
				->matching('Permissions') 
				->contain('Permissions') 
				->group('Roles.id') 
				->first(); 
		} 
		 
		$this->set(compact('permissionsData','rolelist','modules'));
	}
	 

}
