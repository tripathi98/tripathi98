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
use Cake\Network\Email\Email;

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
				'action' => 'index'
			],
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
		
		$this->Auth->allow(['display', 'login', 'register']);
    }

     
	
	public function isAuthorized($user) {   
	  
		// Only for ACL setup
		 //return true;
		
		
        if (isset($user['role_id']) && ( $user['role_id'] === 1 || $user['role_id'] === 2 || $user['role_id'] === 3)) {
			return true;
		}
		else{
			return false;
		}

		// Default deny 
    }
	
	 
	
	public function sendMailTo($to, $subject, $message){
		$email = new Email();
		$email->transport('default'); 
		$result = $email->from(['mes@mailinator.com' => $subject])
		->to($to)
		->emailFormat('html')
		->subject($subject)
		->send($message);
		return $result;
	}
	
}
