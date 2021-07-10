<?php
use Cake\Routing\Router;
?> 
<?= $this->element('navbar');?>
<div class="container-fluid">
	<div class="row"> 
        <div class="w-20 bg-white p-3 sideBarSticky sidenav" id="mySidenav">
			<?= $this->element('sidebar');?>
		</div> 
        <div class="w-80">
			<div>
				<h3>Change Password</h3>
			</div>
			<div class="bg-white p-3 rounded"> 
			<?= $this->Form->create($user,['class'=>"login-form"]) ?>
				   
				<?= $this->Flash->render() ?>
				<?php
					echo $this->Form->hidden('roleId',['value'=>$roleId]); 
				?>
				
				<?php
				echo $this->Form->control('password', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'type' => 'password',
					'class' => 'login-input-control mt-3',
					'placeholder' => 'Password',
					'required' => true
				]);
				?>
				
				<?php
				echo $this->Form->control('password2', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'type' => 'password',
					'class' => 'login-input-control mt-3',
					'placeholder' => 'Confirm Password',
					'required' => true
				]);
				?>
						
				<?= $this->Form->button(__('Submit'),['class'=>"btn btn-dark mt-3"]) ?>
			<?= $this->Form->end() ?>
			</div>
        </div>
         
	</div>
</div> 
 
