<?php
use Cake\Routing\Router;

$session = $this->request->getSession(); 
$authUser = $session->read('Auth');  
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
			<div class="bg-white p-3 rounded">
				<div class="alert alert-danger"><p>User Access Denied. Please contact system administrator</p></div>
				 
				<a href="<?= Router::url('/', true); ?>users/dashboard" class="btn btn-primary clear"><i class="icon-reply"></i> Back</a> 
			</div>
        </div> 
	</div>
</div> 