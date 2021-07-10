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
			echo $this->Form->control('email_or_phone', [
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'label' => false,
				'class' => 'login-input-control',
				'placeholder' => 'Mobile number / email',
				'required'=>true
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
			 
			 
			<div class="CTA-block">
				<a href="<?= Router::url('/', true).'forgot'; ?>" class="forgot-link">Forgot Password?</a>
				<div class="CTA-btn">
					<a href="<?= Router::url(
						array('controller' => 'users','action' => 'register')
					 ); ?>"> 
						<?= $this->Form->button(
							'SIGN UP', 
							[
								'class'=>'btn-bordered','type' => 'button'
							]); 
						?>
					 </a>
					<?= $this->Form->button(__('SIGN IN'),['class'=>"btn-solid"]); ?>
				</div>
			</div>
        <?= $this->Form->end() ?>
	</div> 
</div>
<script>
var $div = $('div.message.error');

if ($div.length > 1) {
   $div.not(':last').remove()
}
</script>