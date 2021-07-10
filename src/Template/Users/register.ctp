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
			echo $this->Form->control('name', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'class' => 'login-input-control',
				'placeholder' => 'Name',
				'required'=>true
			]);
			?>
			 
			<?php
			echo $this->Form->control('email', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'text',
				'class' => 'login-input-control',
				'placeholder' => 'Email'
			]);
			?>
			
			<?php
			echo $this->Form->control('password', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'password',
				'class' => 'login-input-control',
				'placeholder' => 'Password'
			]);
			?>
			
			<?php
			echo $this->Form->control('confirm_password', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'password',
				'class' => 'login-input-control',
				'placeholder' => 'Confirm Password'
			]);
			?>
			
			<?php
			echo $this->Form->control('phone', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'text',
				'class' => 'login-input-control',
				'placeholder' => 'Phone'
			]);
			?>
			
			<?php
			echo $this->Form->control('address', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'type' => 'textarea',
				'class' => 'login-input-control',
				'placeholder' => 'Address'
			]);
			?>
			 
			 
			<div class="CTA-block"> 
				<div class="CTA-btn">
					<a href="<?= Router::url('/', true).'login'; ?>"> 
						<?= $this->Form->button(
							'SIGN IN', 
							[
								'class'=>'btn-bordered','type' => 'button'
							]); 
						?>
					 </a>
					<?= $this->Form->button(__('SIGN UP'),['class'=>"btn-solid"]); ?>
				</div>
			</div>
        <?= $this->Form->end() ?>
	</div> 
</div>