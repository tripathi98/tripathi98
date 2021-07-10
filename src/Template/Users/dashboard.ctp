<?php
use Cake\Routing\Router;

$session = $this->request->getSession(); 
$authUser = $session->read('Auth');  
?> 
 

<?= $this->element('navbar');?>
<div class="container-fluid">
	<div class="row"> 
        <div class="w-20 bg-white p-3 sideBarSticky sidenav" id="mySidenav">
			<?= $this->element('sidebar');?>
		</div>
        <div class="w-80">
			<div class="bg-white p-3 rounded">Center Section</div>
        </div> 
	</div>
</div> 