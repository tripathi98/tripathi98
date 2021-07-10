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
 * Leaves Controller
 *
 * @property \App\Model\Table\LeavesTable $Leaves
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LeavesController extends AppController
{
    public function initialize()
    {
        parent::initialize(); 
    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }
	 
	/**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
		
		$dataList = TableRegistry::get('Leaves');
		$filters['Leaves.date_from >='] = date("Y-m-d");
		$order = array('Leaves.id'=>'asc');
		
		$leaves = $this->paginate($dataList, [
            'limit' => Configure::read('pageRecord'),
            'contain' => ['Users'],  
            'conditions' => [$filters],  
            'order'=>$order
        ]);
		 
        $this->set(compact('leaves'));
    }

     

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->autoRender = false;
        $leave = $this->Leaves->newEntity();
        if ($this->request->is('ajax')) {
			$from_date =  $this->request->getData()['date_from'];
			$error = true;
			foreach($from_date as $key=>$datefrom) {
				
				if(isset($this->request->getData()['date_from'][$key]) && $this->request->getData()['date_from'][$key] != ''){
					$date_to = $this->request->getData()['date_to'][$key];
				}else{
					$date_to = $this->request->getData()['date_from'][$key];
				}
				
				$records = array(
					'user_id' => $this->request->getData()['user_id'],
					'date_from' =>  $datefrom,
					'date_to' => 	$date_to,
					'created_by' => $this->Auth->user('id')
				);
				
				$leave = $this->Leaves->newEntity($records);
				
				if ($this->Leaves->save($leave)) {
					$error = false;
				} 
			}
			 
			if(!$error) {
				$this->Flash->success(__('The leave saved successfully.'));
				$response = array(
					'status' => '200',
					'msg' => 'leave added successfully'
				); 
			}else{  
				$response = array(
					'status' => '500',
					'msg' => 'The user has been saved. But activation link could not be sent'
				);
			}
			echo json_encode($response);die;
        }
		
		/* $userTable = TableRegistry::get('Users');
		$users = $userTable->find('list')
						->where(['Users.status' => '1','Users.id !=' => '1']);
        $this->set(compact('leave','users')); */
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;
		 
        if ($this->request->is(['ajax'])) {
			 
			$leave = $this->Leaves->get($this->request->getData()['id'], [
				'contain' => [],
			]);
			 
            $leave = $this->Leaves->patchEntity($leave, $this->request->getData());
			$leave->updated_by = $this->Auth->user('id');
			
			if(!$leave->getErrors()) {
				if ($this->Leaves->save($leave)) {
					$this->Flash->success(__('The leave updated successfully.'));
					$response = array(
						'status' => '200',
						'msg' => 'User added successfully'
					);
						 
				}else{
					$response = array(
						'status' => '500',
						'msg' => 'The leave could not be saved. Please, try again'
					); 
				}
			}else{
				$response = array(
					'status' => '500',
					'msg' => $this->errorMessage($leave->getErrors())
				);
			}
        }
        
		echo json_encode($response);die;
    }
	
	
	
	public function editdata(){
		$this->viewBuilder()->setLayout(''); 
		$userTable= TableRegistry::get('Leaves');
		 
        if ($this->request->is('ajax')) {
			$id = $this->request->getData()['id'];
			 
			$leave = $this->Leaves->get($id, [
				'contain' => [],
			]);  
		}
		
		$userTable = TableRegistry::get('Users');
		$usersList = $userTable->find('list')
						->where(['Users.status' => '1','Users.id !=' => '1']);
        $this->set(compact('leave','usersList'));
	} 


    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
       
        $leave = $this->Leaves->get($id);
        if ($this->Leaves->delete($leave)) {
            $this->Flash->success(__('The leave has been deleted.'));
        } else {
            $this->Flash->error(__('The leave could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Users','action' => 'index']);
    }
	
	
	 
}
