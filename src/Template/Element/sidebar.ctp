<?php
use Cake\Routing\Router;
$session = $this->request->getSession(); 
$authUser = $session->read('Auth');  
$action		=	$this->request->params['action'];
$controller	=	$this->request->params['controller'];
?>
<a href="javascript:void(0)" class="closebtn smallDevice" onclick="closeNav()">
	<img src="<?= Router::url('/', true); ?>assets/close_big.svg" alt="close menu">
</a>
<div class="innerStyle">
	<div class="dashboard">
		<a href="<?= Router::url('/', true); ?>users/dashboard">Performance Dashboard</a>
	</div>
	<div class="accordion sideBarScroll" id="accordionExample">
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingOne">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
					Unit Based Performance
				</button>
			</h2>
			<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<ul>
						<?php 
							$modulePermissions = $this->General->getModulePermission('Boilers','can_view',$user_role);
			
							if($authUser['User']['id'] == 1 || $modulePermissions > 0){
						?>
							<li><a href="#" class="">Boiler</a></li>
						<?php } ?>
						
						<?php 
							$modulePermissions1 = $this->General->getModulePermission('Expellers','can_view',$user_role);
			
							if($authUser['User']['id'] == 1 || $modulePermissions1 > 0){
						?>
							<li><a href="#" class="">Expeller</a></li>
						<?php } ?>
						
						<?php 
							$modulePermissions2 = $this->General->getModulePermission('Oil Tank Systems','can_view',$user_role);
			
							if($authUser['User']['id'] == 1 || $modulePermissions2 > 0){
						?>
							<li><a href="#" class="">Boiler</a></li>
						<?php } ?>
							
						<li><a href="#"></a></li>
						<li><a href="#">Oil Tank System</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingTwo">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					Way Bridge
				</button>
			</h2>
			<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<ul>
						<li><a href="">Unit 1</a></li>
						<li><a href="">Unit 2</a></li>
						<li><a href="">Unit 3</a></li> 
					</ul>
				</div>
			</div>
		</div>
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingTwo">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
					User Management
				</button>
			</h2>
			<div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<ul>  
						<?php 
							$modulePermission = $this->General->getModulePermission('Users','can_view',$user_role);
			
							if($authUser['User']['id'] == 1 || $modulePermission > 0){
						 ?>
							<li><a href="<?= Router::url('/', true); ?>users" class="active">Employee Allocation</a></li>
						<?php } ?>
						
						<?php  
							if($authUser['User']['id'] == 1){
						 ?> 
							<li><a href="<?= Router::url('/', true); ?>permissions" class="">User Roles Permissions</a></li>
						<?php } ?>
						
						<?php 
							/* $modulePermission1 = $this->General->getModulePermission('Roles','can_view',$user_role);
			
							if($authUser['User']['id'] == 1 || $modulePermission1 > 0){
						 ?>
							
						<li><a href="<?= Router::url('/', true); ?>roles" class="">Manage Role</a></li>
						
						<?php } */ ?>
						
						<?php 
							if($authUser['User']['id'] == 1){
						 ?>
						 
							<!--li><a href="<?= Router::url('/', true); ?>leaves" class="">Manage leaves</a></li-->
						
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div> 