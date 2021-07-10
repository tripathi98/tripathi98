<?php
use Cake\Routing\Router;
?> 
<?= $this->element('navbar');?>
<div class="container-fluid">
	<div class="row"> 
        <div class="w-20 bg-white p-3 sideBarSticky sidenav" id="mySidenav">
			<?= $this->element('sidebar');?>
		</div> 
        <div class="w-80 pt-3">
          <div class="bg-white p-3 rounded">
            <div class=""> 	 
              <div class="section-title">
					<h3>Profile</h3>
					<button type="button" class="btn btn-dark">Update</button>
              </div>
              <div class="user-profile-details">
                <div class="leftCol">
                  <div class="user-image">
					<?php
						if (!empty($user->avatar)) {
							$original = WWW_ROOT . 'uploads/user' . DS . 'thumb' . DS . $user->avatar;
							if (file_exists($original)) {
								echo $this->Html->image('../uploads/user/thumb/' . $user->avatar, array('class' => '','width'=>'200'));
							}else{
								echo $this->Html->image('user-img.jpg', array('class' => '','width'=>'200'));
							}
						}else{
							echo $this->Html->image('user-img.jpg', array('class' => '','width'=>'200'));
						}
					?>    
					<?php
						echo $this->Form->control('avatar', [
							'templates' => [
								'inputContainer' => '{{content}}'
							],
							'label' => false,
							'type' => 'file',
							'class' => 'custom-file-input', 
							'required' => true
						]);
					?>
						
                  </div>
                </div>
                <div class="rightCol"> 
				  <?= $this->Form->create($user,['class'=>"userDetailsForm"]) ?>
                    <div class="w-50">
						<label>Name</label>
                       
						<?php
							echo $this->Form->control('name', [
								'templates' => [
									'inputContainer' => '{{content}}'
								],
								'label' => false,
								'class' => 'custom-control',
								'placeholder' => 'Enter Name',
								'required'=>true
							]);
						?>
                    </div>
                     
                    <div class="w-50">
						<label>Mobile Number</label>
						<?php
							echo $this->Form->control('phone', [
								'templates' => [
									'inputContainer' => '{{content}}'
								],
								'label' => false,
								'class' => 'custom-control',
								'placeholder' => '#',
								'required'=>true
							]);
						?>
                    </div>
                    <div class="w-50">
						<label>Email Address</label>
						<?php
							echo $this->Form->control('email', [
								'templates' => [
									'inputContainer' => '{{content}}'
								],
								'label' => false,
								'class' => 'custom-control',
								'placeholder' => 'Enter Email',
								'readonly'=>true
							]);
						?>
                    </div>
                    <div class="w-50">
						<label>Address</label>
						<textarea class="custom-control" placeholder="address" name="address" required>
							<?= $user->address; ?>
						</textarea>
                    </div>
					
					<div class="w-100 changePasswordLabel">
						<h5>Change password</h5>
                    </div>
                    <div class="w-50">
						<label>New Password</label>
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
                    </div>
                    <div class="w-50">
						<label>Confirm Password</label>
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
                    </div>
                  <?= $this->Form->end() ?>
                </div>
              </div> 
            </div>
          </div>
        </div> <!-- End main body tag -->  
	</div>
</div> 
 
