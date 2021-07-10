<?php
use Cake\Routing\Router;
?> 
<?= $this->element('navbar');?>
<div class="container-fluid">
	<div class="row mt-3">
        <div class="w-20">
			<div class="bg-white p-3 rounded">
				<?= $this->element('sidebar');?>
			</div>
        </div>
        <div class="w-80">
			<div>
				<h3>Edit Role</h3>
			</div>
			<div class="bg-white p-3 rounded"> 
			<?= $this->Form->create($role,['class'=>"login-form"]) ?>
				   
				<?= $this->Flash->render() ?>
				 
				<?php
				echo $this->Form->control('name', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'class' => 'login-input-control mt-3',
					'placeholder' => 'Name',
					'required'=>true
				]);
				?>
				
				 
				
				<?php $status = array('1'=>'Active','2'=>'Inactive');?>
				<?= 
					$this->Form->select(
						"status",
						$status,
						['empty' => 'Select status','required'=>true,"class"=>"form-select mt-3"]
					);
				?>
						
				<?= $this->Form->button(__('Submit'),['class'=>"btn btn-dark mt-3"]) ?>
			<?= $this->Form->end() ?>
			</div>
        </div>
         
	</div>
</div> 
 
