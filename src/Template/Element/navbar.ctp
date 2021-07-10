<?php
use Cake\Routing\Router;

$session = $this->request->getSession(); 
$authUser = $session->read('Auth');  
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
	<div class="container-fluid custom-nav">
		<div class="w-20"><a class="navbar-brand" href="<?= Router::url('/', true); ?>">Logo</a></div>
		<button class="btn smallDevice">
			<span style="cursor:pointer" onclick="openNav()">
				<img src="<?= Router::url('/', true); ?>assets/hamburger.svg" alt="Menu" >
			</span>
		</button>
		<div class="w-80 custom-nav-right collapse navbar-collapse" id="navbarSupportedContent">
			<div class="left-side">
				<form class="navbar-search">
				   
				</form>
			</div>
	  
			<div class="right-side-info">
				<div class="dropdown">
					<button  style="display: flex; align-items: center;" class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
						<div class="user-avtar">
							<a href="#">
								<span><?php echo $authUser['User']['name'];?></span>
								<img src="<?= Router::url('/', true).'assets/default-user-image.png'; ?>" alt="user">
							</a>
						</div>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="right:0 !important;left: unset;"> 
						<li><a class="dropdown-item" href="<?= Router::url('/', true); ?>users/profile">Profile</a></li>
						<li><a class="dropdown-item" href="<?= Router::url('/', true); ?>logout">Sign out</a></li>
					</ul>
				</div>
				<div class="logout">
					<a href="<?= Router::url('/', true); ?>logout"></a>
				</div>
			</div> 
		</div>
	</div>
</nav>