<?php
use Cake\Routing\Router;
?> 
<div class="login-container">

	<div class="flex-1 leftSide">
        <h2>Welcome to Performance Dashboard</h2>
        <p>View metrics to inform teams about<br>your system’s overall performance</p>
        <div class="divider"></div>
        <p>Add real time unit data for<br>performance records</p>
        <div class="divider"></div>
        <p>Identify areas where units are<br>underperforming to optimize results</p>
	</div>
	<div class="flex-1 rightSide">
		 
        <?= $this->Form->create($user,['class'=>"login-form"]) ?>
			 
			   
			<?= $this->Flash->render() ?>
	 
			<?php
			echo $this->Form->control('password', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'password',
				'class' => 'login-input-control',
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
				'class' => 'login-input-control',
				'placeholder' => 'Confirm Password',
				'required' => true
			]);
			?>
			  
			<div class="CTA-block">
				<a href="<?= Router::url('/', true).'login'; ?>" class="forgot-link">Sign in</a>
				<div class="CTA-btn"> 
					<?= $this->Form->button(__('SUBMIT'),['class'=>"btn-solid"]); ?>
				</div>
			</div>
        <?= $this->Form->end() ?>
	</div> 
</div>