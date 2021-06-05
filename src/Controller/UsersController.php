<?php
namespace App\Controller;
 
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
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

        $this->Auth->allow(['logout','register']); 

    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }
	
	public function login(){
        $userTable = TableRegistry::get('Users');
        $user = $userTable->newEntity();

        if($this->request->is(['post','put'])){
            $record = $userTable->find()->where(['email'=>$this->request->getData()['email_or_phone']])
			->orWhere(['phone'=>$this->request->getData()['email_or_phone']])
			->count();
            if($record > 0){
                $user = $this->Auth->identify();
                if ($user) { 
                    $status = $user['status'];
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
        return $this->redirect($this->Auth->logout());
    }
	
	
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
                    if ($userTable->save($user)) {
                        $username = ucfirst($user->first_name);
                        $activation_link = Router::url('/', true).'users/activate_account/'.$token.'/'.base64_encode($user->id);
                        $EmailTemplates= TableRegistry::get('EmailTemplates');
                        $query = $EmailTemplates->find('all')
                                ->where(['EmailTemplates.slug' => 'user_registration']);
                        $template = $query->first();
                        $userEmail = $user->email;
                        try {

                            $mailMessage = str_replace(array('{{username}}', '{{activation_link}}','{{email}}','{{password}}'),
                            array(ucfirst($username),$activation_link,$userEmail,$password),$template->description);
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

         
    }
	
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    { 
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
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
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
			
			$user->created_by = $this->Auth->user('id');
			
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
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
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
			$user->updated_by = $this->Auth->user('id');
			
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
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
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
