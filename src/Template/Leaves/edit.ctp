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
				<h3>Edit User Leave</h3>
			</div>
			<div class="bg-white p-3 rounded"> 
			<?= $this->Form->create($leave,['class'=>"login-form"]) ?>
				   
				<?= $this->Flash->render() ?>
				 
				<?= 
					$this->Form->select(
						"user_id",
						$users,
						['empty' => 'Select User','required'=>true,"class"=>"form-select mt-3","value"=>$leave->user_id]
					);
				?> 
				
				<?php
					echo $this->Form->control('date_from', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false,
						'id' => 'from',
						'type' => 'text',
						'autocomplete' => 'off',
						'class' => 'form-control search-gap datepicker mt-3',
						'placeholder' => 'Date From',
						'required'=>false
					]);
				?>  
				<?php
					echo $this->Form->control('date_to', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false,
						'id' => 'to',
						'type' => 'text',
						'class' => 'form-control search-gap datepicker mt-3',
						'placeholder' => 'Date To',
						'autocomplete' => 'off',
						'required'=>false
					]);
				?>
						
				<?= $this->Form->button(__('Submit'),['class'=>"btn btn-dark mt-3"]) ?>
			<?= $this->Form->end() ?>
			</div>
        </div>
         
	</div>
</div> 
<?= $this->Html->css('jquery-ui.css') ?>
<?= $this->Html->script(['jquery-1.12.4.js','jquery-ui.js']) ?> 
<script>  
var dates = $("#from, #to").datepicker({
    defaultDate: "+1w",
	dateFormat: "yy-mm-dd",
    changeMonth: true
}); 
</script> 
