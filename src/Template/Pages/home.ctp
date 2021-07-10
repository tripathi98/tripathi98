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
		 
        <form class="login-form">
			<?= 'Thankyou for visiting here.' ?> 
			<div class="CTA-block">
				<a href="<?= Router::url('/', true).'logout'; ?>" class="forgot-link">Logout</a> 
			</div>
        </form>
	</div> 
</div>