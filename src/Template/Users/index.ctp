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
		<?= $this->Form->create('',['id'=>'navbar-search','class'=>'navbar-search']) ?> 
			<label class="search-gap">View duration:</label>
			<?php
				echo $this->Form->control('date_from', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'id' => 'from',
					'autocomplete' => 'off',
					'class' => 'form-control search-gap datepicker',
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
					'autocomplete' => 'off',
					'class' => 'form-control search-gap datepicker',
					'placeholder' => 'Date To',
					'required'=>false
				]);
			?>  
		<?= $this->Form->end() ?>
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

<div class="container-fluid">
	<div class="row"> 
        <div class="w-20 bg-white p-3 sideBarSticky sidenav" id="mySidenav">
			<?= $this->element('sidebar');?>
		</div> 
		 
        <div class="w-80 pt-3">
			<div class="bg-white p-3 rounded">
				<div class="section-title">
					<h3>Employee allocation</h3>
				</div>
				
				<?= $this->Flash->render() ?>
				
				<!-- Nav tabs -->
				<div class="">
					<div class="nav nav-pills tabStyle" id="v-pills-tab" role="tablist">
						<a class="nav-link active" id="v-pills-user-tab" data-bs-toggle="pill" href="#TabOne" role="tab" aria-controls="v-pills-user" aria-selected="true">Employee list</a>
						
						<?php if($authUser['User']['id'] == 1){ ?>
							<a class="nav-link" id="v-pills-pooblastila-tab" data-bs-toggle="pill" href="#TabTwo" role="tab" aria-controls="v-pills-pooblastila" aria-selected="false">Manage Leave</a>
						<?php } ?>
					</div>
					<div class="tab-content flex-grow-1" id="v-pills-tabContent">
						<div class="tab-pane fade show active " id="TabOne" role="tabpanel" aria-labelledby="v-pills-user-tab">
					  
							<div class="row mt-2 mb-2 align-items-center">
								<div class="col-sm-6">
									<h5 class="fw-bold">FILTER</h5>
								</div>
								<div class="col-sm-6 text-right">
								
									<?php 
										$moduleaddPermission = $this->General->getModulePermission('Users','can_add',$user_role);
						
										if($authUser['User']['id'] == 1 || $moduleaddPermission > 0){
									 ?>
									 
									<button  type="button" class="btn mes-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add New Employee</button> 
									
									<?php } ?>
								</div>
							</div>
							
							<?= $this->Form->create('',['class'=>"login-form",'id'=>"searchform"]) ?>
							<div class="row">
							
								<?php if(!empty($modules)){?>
								  <div class="col-sm-4">
									<div class="row">
									  <div class="col-sm-12 mb-2 filterTypeTitle">FILTER BY UNIT</div>
									  <?php foreach($modules as $module){?>
									  <div class="col-sm-6">
										<div class="mb-2 form-check">
											  <input type="checkbox" class="searchfilter form-check-input" id="<?= $module;?>" name="module_filer[]" value="<?= $module;?>" <?php if(isset($savesearch['module_filer']) && in_array($module,$savesearch['module_filer'])){echo 'checked';}?>>
											  <label class="form-check-label" for="<?= $module;?>"><?= $module;?></label>
										</div>
									  </div>
									  <?php } ?> 
									</div>
								  </div>
								  <?php } ?>
								  
								  <?php if(!empty($roles)){?>
								  <div class="col-sm-4">
										<div class="row">
										  <div class="col-sm-12 mb-2 filterTypeTitle">FILTER BY ROLE</div>
										  
										  <?php foreach($roles as $role){?>
										  
										  <div class="col-sm-6">
											<div class="mb-2 form-check">
												<input type="checkbox" class="searchfilter form-check-input" id="role_filter_<?= $role->id;?>" value="<?= $role->id;?>" name="role_id_filter[]" <?php if(isset($savesearch['role_id_filter']) && in_array($role->id,$savesearch['role_id_filter'])){echo 'checked';}?>>
												<label class="form-check-label" for="Operator"><?= $role->name;?></label>
											</div>
										  </div>
										  <?php } ?>
										  
										</div>
								  </div>
								  <?php } ?>
								  
								  <div class="col-sm-4">
									<div class="row">
									  <div class="col-sm-12 mb-2 filterTypeTitle">FILTER BY STATUS</div>
									  <div class="col-sm-12">
										<div class="mb-2 form-check">
										  <input type="checkbox" class="searchfilter form-check-input" id="Active" name="status_id[]" value="1" <?php if(isset($savesearch['status_id']) && in_array('1',$savesearch['status_id'])){echo 'checked';}?> >
										  <label class="form-check-label" for="Active">
											<img src="<?= Router::url('/', true); ?>assets/active.svg">  
											Active
										  </label>
										</div>
									  </div>
									  <div class="col-sm-12">
										<div class="mb-2 form-check">
										  <input type="checkbox" class="searchfilter form-check-input" id="OnLeave" name="status_id[]" value="3" <?php if(isset($savesearch['status_id']) && in_array('3',$savesearch['status_id'])){echo 'checked';}?>>
										  <label class="form-check-label" for="OnLeave">
											<img src="<?= Router::url('/', true); ?>assets/OnLeave.svg"> 
											On leave
										  </label>
										</div>
									  </div>
									  <div class="col-sm-12">
										<div class="mb-2 form-check">
										  <input type="checkbox" class="searchfilter form-check-input" id="UpcomingLeave" name="status_id[]" value="4" <?php if(isset($savesearch['status_id']) && in_array('4',$savesearch['status_id'])){echo 'checked';}?>>
										  <label class="form-check-label" for="UpcomingLeave">
											<img src="<?= Router::url('/', true); ?>assets/upcomingLeave.svg"> 
											Upcoming leave
										  </label>
										</div>
									  </div>
									</div>
								  </div>
							</div>
							<?= $this->Form->end() ?>
							
							<div class="row">
							  <div class="col-sm-12">
								<div class="table-responsive">
								  <table class="table table-striped">
									<thead>
									  <tr>
										<th class="width-20"><?= $this->Paginator->sort('name', __('EMPLOYEE NAME')) ?></th>
										<th>STATUS</th>
										<th class="width-8">ASSIGNED ROLES</th>
										<th>ACCESS DATES</th>
										<th>UPCOMING LEAVES</th>
										<!--th>SUPERVISOR NAME</th-->
										<th>EMPLOYEE MOB.</th>
										<th class="text-end"> </th>
									  </tr>
									</thead>
									<tbody>
										<?php 
										if(!empty($users)){
											foreach ($users as $user):
											
											$onleaveToday = $this->General->checkOnleaveToday($user->id,date("Y-m-d"));
										?>							
										<tr>
											<td>
											  <div class="fw-bold"><?php echo $user->name;?></div>
											</td> 
											<td>  
											  <?php 
												$today = date("Y-m-d");
												if($user->status == 1){
													if(!empty($onleaveToday)){
														?>
														<div class="badge rounded-pill statusLeave">On Leave</div>  
													<?php }else if($user->access_from > $today && $user->access_to < $today){ ?>
														<div class="badge rounded-pill statusLeave">On Leave</div>
													<?php }else{ ?>
														<div class="badge rounded-pill statusActive">Active</div>
													<?php }
												}else{ ?> <div class="badge rounded-pill statusLeave">Inactive</div> <?php } 
											 ?>
											</td> 
											<td>
											 <?php  
												if(!empty($user->user_roles)){
											 ?>
												  <div>
													 <?php  
														foreach($user->user_roles as $roleVal){
													 ?>
														<span class="AssignedUserRole"><?= $roleVal->role->name;?></span>
													 <?php } ?>
												  </div>
											  <?php } ?>
											</td> 
											<td> 
											   <?php   
												if(!empty($user->user_accessdates)){ 
													foreach($user->user_accessdates as $accessDate){
												?>
													<div class="badge rounded-pill statusUpcomingLeave"> 
												<?php
														echo date("d-m-Y",strtotime($accessDate->access_from)) . " - " . date("d-m-Y",strtotime($accessDate->access_to));
														echo "<br>";
												?>	
													 </div>
												<?php }  
													} else{
														echo "-";
													} 
												?> 
											</td>
											<td> 
											   <?php   
													if(!empty($user->leaves)){
											   ?>
												 
													<?php
													foreach($user->leaves as $leaves){
														//if(strtotime($leaves->date_to) >= strtotime(date("Y-m-d"))){
													?>
													<div class="badge rounded-pill statusUpcomingLeave">
													<?php
															echo date("d-m-Y",strtotime($leaves->date_from)) . " - " . date("d-m-Y",strtotime($leaves->date_to));
															echo "<br>";
													?>
													</div>
													<?php
														//}
													}
											   ?> 
											   <?php
													} else{
														echo "-";
													} 
												?> 
											</td>
											
											<!--td><?php echo $user->supervisor_name;?></td-->
											<td><?php echo $user->phone;?></td>
											<td class="text-end">
											  <div class="table-actions">
												  
												<?php 
													$moduleeditPermission = $this->General->getModulePermission('Users','can_edit',$user_role);
									
													if($authUser['User']['id'] == 1 || $moduleeditPermission > 0){
												 ?>   
													<a href="javascript:;" class="editemp" data-id="<?= $user->id;?>">
														<img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg" alt="Edit Rolde">
													</a>
												<?php } ?>
												
												<?php 
													$moduledeletePermission = $this->General->getModulePermission('Users','can_delete',$user_role);
									
													if($authUser['User']['id'] == 1 || $moduledeletePermission > 0){
												 ?> 
												<?= 
													$this->Html->link(
														$this->Html->image(Router::url('/', true).'assets/delete-light-icon.svg'),
														array(
															'controller' => 'Users', 
															'action' => 'delete',$user->id
														), array('escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $user->id))
													);
												?> 
												<?php } ?>
												
											  </div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php } ?>	 
									</tbody>
								</table>
								 <div class="paginator">
									<ul class="pagination">
										<?= $this->Paginator->first('<< ' . __('First')) ?>
										<?= $this->Paginator->prev('< ' . __('Previous')) ?>
										<?= $this->Paginator->numbers() ?>
										<?= $this->Paginator->next(__('Next') . ' >') ?>
										<?= $this->Paginator->last(__('Last') . ' >>') ?>
									</ul>
									<p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
								 </div>
								
								</div>
							  </div>
							</div>
						</div>
						<div class="tab-pane fade" id="TabTwo" role="tabpanel" aria-labelledby="v-pills-pooblastila-tab">
							
							<div class="row mt-2 mb-2 align-items-center">
								<div class="col-sm-6">
									<h5 class="fw-bold">Leaves</h5>
								</div>
								<div class="col-sm-6 text-right"> 
									<button  type="button" class="btn mes-btn" data-bs-toggle="modal" data-bs-target="#staticBackdropLeave">Add New</button>  
								</div>
							</div>
							 
							<div class="row">
							  <div class="col-sm-12">
								<div class="table-responsive">
								  <table class="table table-striped">
									<thead>
									  <tr>
										<th class="width-20">USER NAME</th>
										<th>STATUS</th> 
										<th>DATE FROM</th>
										<th>DATE TO</th> 
										<th class="text-end"> </th>
									  </tr>
									</thead>
									<tbody>
										<?php 
										if(!empty($leavesArr)){
											$i=1;
										foreach ($leavesArr as $leave): 
										?>							
										<tr>
											<td>
											  <div class="fw-bold"><?= h($leave->user->name) ?></div>
											</td> 
											<td> 
												<?php 
												$today = date("Y-m-d");
													if($today >= $leave->date_from && $today <= $leave->date_to){ ?>
													<div class="badge rounded-pill bg-secondary">On Leave</div>
												<?php }else{?>
													<div class="badge rounded-pill bg-success">Active</div>
												<?php } ?>
												
											</td> 
											<td><?= h($leave->date_from) ?></td>
											<td><?= h($leave->date_to) ?></td>
											<td class="text-end">
											  <div class="table-actions"> 
												 <?php if($authUser['User']['id'] == 1){ ?>
													
													<a href="javascript:;" class="editleave" data-id="<?= $leave->id;?>">
														<img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg" alt="Edit Rolde">
													</a> 
												
												 
													<?= 
														$this->Html->link(
															$this->Html->image(Router::url('/', true).'assets/delete-light-icon.svg'),
															array(
																'controller' => 'Leaves', 
																'action' => 'delete',$leave->id
															), array('escape' => false,'confirm' => __('Are you sure you want to delete this record'))
														);
													?>  
													
												  <?php } ?> 
											  </div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php } ?>	 
									</tbody>
								</table>
								 <div class="paginator">
									<ul class="pagination">
										<?= $this->Paginator->first('<< ' . __('First')) ?>
										<?= $this->Paginator->prev('< ' . __('Previous')) ?>
										<?= $this->Paginator->numbers() ?>
										<?= $this->Paginator->next(__('Next') . ' >') ?>
										<?= $this->Paginator->last(__('Last') . ' >>') ?>
									</ul>
									<p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
								 </div>
								
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
				<!-- Nav tab End -->
			</div>
		</div>
	</div>
</div>

<!-- Start Add New Employee popup -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog customWidth">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Add New Employee Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= $this->Form->create('',['class'=>"login-form",'id'=>"empform"]) ?>
			<div class="modal-body">
				<div class="modalBodyContent">
					<div class="successmsg"></div>
					<div class="row innerGap borderForSection"> 
						<div class="col-sm-6">
							<label>Employee Name</label>
							<input type="text" name="name" class="custom-control" placeholder="Enter Name" required>
						</div>
						<!--div class="col-sm-6">
							<label>Supervisor Name</label>
							<input type="text" class="custom-control" placeholder="Enter Supervisor Name" name="supervisor_name" required>
						</div-->
						<div class="col-sm-6">
							<label>Email</label>
							<input type="text" class="custom-control" placeholder="Enter Email" name="email" required>
						</div>
						<div class="col-sm-6">
							<label>Phone Number</label>
							<input type="text" class="custom-control" placeholder="#" name="phone" required>
						</div>
						<div class="col-sm-6">
							<label>Address</label>
							<textarea class="custom-control" placeholder="address" name="address" required></textarea>
						</div>
						<div class="col-sm-6">
							<label>User Status</label> 
							<?php $status = array('1'=>'Active','2'=>'Inactive');?>
							<?= 
								$this->Form->select(
									"status",
									$status,
									['empty' => 'Select status','required'=>true,"class"=>"form-select search-gap"]
								);
							?>
							
						</div>
						
						<div class="row mt-3">
							<h5 class="modal-title">Access Dates</h5>
						</div>
						 
						<div class="row mt-3 clonedataacess">
							<div class="form-group col-md-5"> 
								<?php
									echo $this->Form->control('access_from[]', [
										'templates' => [
											'inputContainer' => '{{content}}'
										],
										'label' => false, 
										'type' => 'text',
										'id' => 'access_from[0]',
										'autocomplete' => 'off',
										'class' => 'custom-control search-gap datepicker',
										'placeholder' => 'Access Date From',
										'required'=>false
									]);
								?>
							</div>
							<div class="form-group col-md-5"> 
								<?php
									echo $this->Form->control('access_to[]', [
										'templates' => [
											'inputContainer' => '{{content}}'
										],
										'label' => false, 
										'type' => 'text',
										'id' => 'access_to[0]',
										'class' => 'custom-control search-gap datepicker',
										'placeholder' => 'Access Date To',
										'autocomplete' => 'off',
										'required'=>false
									]);
								?>
							</div>
							<div class="form-group col-md-2 tn-buttonsaccess">
								<?= $this->Form->button(__('Add'),['type'=>"button",'class'=>"btn btn-dark rounded-pill addmoreaccess"]) ?>
							</div>
						</div> 
						
						<div class="row">
							<div id="accessmore"> </div>
						</div>
		
					</div>
					
					<div class="row innerGap align-items-center clonedata">
						<div class="col-sm-6 "> 
							<select class="form-select search-gap" aria-label="Default select example" name="role_id[0]">
								<option value="" selected>Select new role</option>
								<?php 
									foreach($roles as $role){
								?>
									<option value="<?= $role->id;?>"><?= $role->name;?></option>
								<?php } ?> 
							</select> 
						</div>
						<div class="col-sm-2 tn-buttons">
							<button type="button" class="pe-3 ps-3 btn btn-dark rounded-pill addmore">Add</button>
						</div>
						<!--div class="col-sm-4 text-right">
						  # roles assigned: <span class="fw-bold">3</span>
						</div-->
					</div>
					
					<div id="clonedataappend" class="innerGap pt-md-0">
						 
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-dark rounded-pill addEmployee">SAVE</button>
			</div>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<!-- End Add New Employee popup -->


<!-- Start Edit New Employee popup -->
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog customWidth">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Edit Employee Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body employeeform">&nbsp;</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-dark rounded-pill editEmployee">SAVE</button>
			</div> 
		</div>
	</div>
</div>
<!-- End Edit New Employee popup -->

<!-- Start Add New Leave popup -->
<div class="modal fade" id="staticBackdropLeave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog customWidth">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Add New Leave</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= $this->Form->create('',['class'=>"login-form",'id'=>"leaveform"]) ?>
			<div class="modal-body">
				<div class="modalBodyContent">
					<div class="successmsgleave"></div>
					<div class="row innerGap borderForSection"> 
						<div class="col-sm-6">
							<label>User</label> 
							<?= 
								$this->Form->select(
									"user_id",
									$usersList,
									['empty' => 'Select User','required'=>true,"class"=>"form-select mt-3"]
								);
							?>  
						</div>
						  
						<div class="row mt-3 clonedataleaves">
							<div class="form-group col-md-5"> 
								<?php
									echo $this->Form->control('date_from[]', [
										'templates' => [
											'inputContainer' => '{{content}}'
										],
										'label' => false, 
										'type' => 'text',
										'id' => 'date_from[0]',
										'autocomplete' => 'off',
										'class' => 'custom-control search-gap datepicker',
										'placeholder' => 'Date From',
										'required'=>false
									]);
								?>
							</div>
							<div class="form-group col-md-5"> 
								<?php
									echo $this->Form->control('date_to[]', [
										'templates' => [
											'inputContainer' => '{{content}}'
										],
										'label' => false, 
										'type' => 'text',
										'id' => 'date_to[0]',
										'class' => 'custom-control search-gap datepicker',
										'placeholder' => 'Date To',
										'autocomplete' => 'off',
										'required'=>false
									]);
								?>
							</div>
							<div class="form-group col-md-2 tn-buttonsleaves">
								<?= $this->Form->button(__('Add'),['type'=>"button",'class'=>"btn btn-dark rounded-pill addmoreleaves"]) ?>
							</div>
						</div> 
						
						<div class="row">
							<div id="leavesmore"> </div>
						</div>
		
					</div> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-dark rounded-pill addLeaves">SAVE</button>
			</div>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<!-- End Add New Leave popup -->


<!-- Start Edit New Leave popup -->
<div class="modal fade" id="staticBackdrop12" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog customWidth">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Edit Leave Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body leaveform">&nbsp;</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-dark rounded-pill editleaves">SAVE</button>
			</div> 
		</div>
	</div>
</div>
<!-- End Edit New Leave popup -->


<?= $this->Html->css('jquery-ui.css') ?>
<?= $this->Html->script(['jquery-1.12.4.js','jquery-ui.js']) ?>  
<script> 

var dateToday = new Date();
var dates = $("#from, #to").datepicker({
    defaultDate: "+1w",
	dateFormat: "yy-mm-dd",
    changeMonth: true, 
    onSelect: function(selectedDate) {
        var option = this.id == "from" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates.not(this).datepicker("option", option, date);
			
			$('#navbar-search').submit();
    }
}); 

$(".datepicker").datepicker({ 
	dateFormat: "yy-mm-dd",
	changeMonth: true,
	//minDate: dateToday
});
 
var cloneCount = 1;
$(document).on('click', '.addmore', function (ev) {
	 
	var $clone = $(this).parents('.clonedata').clone(true);
	var $appendDiv = $(this).parents().parents().find('#clonedataappend');
	  
	clear_form_elements($clone);
	
	$clone.removeClass('innerGap').addClass('pb-md-3');
	
	var total_count=$(".clonedata").length;
	 
    $clone.find("select").each(function(){
        $(this).attr("name",$(this).attr("name").replace("[0]","["+total_count+"]"));
    });
	 
	
	var $newbuttons = "<button type='button' class='pe-3 ps-3 btn btn-danger rounded-pill removemore'>Remove</button>";
	$clone.find('.tn-buttons').html($newbuttons).end().appendTo($appendDiv);
	 
});

$(document).on('click', '.removemore', function () {
  
	$(this).parents('.clonedata').remove();
	  
});
 
function clear_form_elements(jquery_obj) {
  jquery_obj.find(':input').each(function() { 
	console.log(this.type);
    switch(this.type){
        case 'password':
        case 'text':
        case 'number':
        case 'textarea':
        case 'file':      
            $(this).attr("value",""); 
        case 'hidden':
			$(this).remove(); 
        case 'select-one':
        case 'select-multiple':
            $(this).find("option").each(function (){ 
                $(this).attr("selected",false);
            }); 
        case 'checkbox':
			this.checked = false;
        case 'radio':
            this.checked = false;
    }
  });
}
 
$(document).on('click', '.addEmployee', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url 	= '<?php echo Router::url('/', true).'users/add';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#empform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsg').html(msg);
				
				$('.addEmployee').text('SAVE');
				$('.addEmployee').prop('disabled', false);
					
			} 
		}
	}); 
	 
});
  
$(document).on('click', '.editemp', function () {
	 
	var id = $(this).attr('data-id');
	var url 	= '<?php echo Router::url('/', true).'users/editdata';?>';
	var csrfToken = $("[name='_csrfToken']").val(); 
	if(id != ''){
		$.ajax({
			type:'POST',
			dataType: 'html',
			data: {id:id,_csrfToken:csrfToken},
			url:url,
			success:function(res) {
				$('.employeeform').html(res);
				
				$('#staticBackdrop1').modal('show');
			}
		}); 
	} 
	 
});
  
$(document).on('click', '.editEmployee', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url = '<?php echo Router::url('/', true).'users/edit';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#editempform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsg').html(msg);
				
				$('.editEmployee').text('SAVE');
				$('.editEmployee').prop('disabled', false);
					
			} 
		}
	}); 
	 
});

$(document).on('click', '.addmoreaccess', function (ev) {
	var $clone = $(this).parent().parent().clone(true);
	  
	$clone.find('input.datepicker')
		.attr("id", "")
		.val(" ")
		.removeClass('hasDatepicker')
		.removeData('datepicker')
		.unbind()
		.datepicker({ 
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			//minDate: dateToday
		});
		
	var total_count=$(".clonedataaccess").length;
  
    $clone.find("input").each(function(){
        $(this).attr("name",$(this).attr("name").replace("[0]","["+total_count+"]"));
        $(this).attr("id",$(this).attr("id").replace("[0]","["+total_count+"]"));
    });
		 
	var $newbuttons = "<button type='button' class='btn btn-danger rounded-pill removemore1'>Remove</button>";
	$clone.find('.tn-buttonsaccess').html($newbuttons).end().appendTo($('#accessmore'));
	 
});
 
$(document).on('click', '.removemore1', function () {
	$(this).parent().parent().remove();
});

$(document).on('click', '.addmoreleaves', function (ev) {
	var $clone = $(this).parent().parent().clone(true);
	  
	$clone.find('input.datepicker')
		.attr("id", "")
		.val(" ")
		.removeClass('hasDatepicker')
		.removeData('datepicker')
		.unbind()
		.datepicker({ 
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			//minDate: dateToday
		});
		
	var total_count=$(".clonedataleaves").length;
  
    $clone.find("input").each(function(){
        $(this).attr("name",$(this).attr("name").replace("[0]","["+total_count+"]"));
        $(this).attr("id",$(this).attr("id").replace("[0]","["+total_count+"]"));
    });
		 
	var $newbuttons = "<button type='button' class='btn btn-danger rounded-pill removemore2'>Remove</button>";
	$clone.find('.tn-buttonsleaves').html($newbuttons).end().appendTo($('#leavesmore'));
	 
});

$(document).on('click', '.removemore2', function () {
	$(this).parent().parent().remove();
}); 


$(document).on('click', '.addLeaves', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url 	= '<?php echo Router::url('/', true).'leaves/add';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#leaveform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsgleave').html(msg);
				
				$('.addLeaves').text('SAVE');
				$('.addLeaves').prop('disabled', false);
					
			} 
		}
	}); 
	 
});

$(document).on('click', '.editleave', function () {
	 
	var id = $(this).attr('data-id');
	var url 	= '<?php echo Router::url('/', true).'leaves/editdata';?>';
	var csrfToken = $("[name='_csrfToken']").val(); 
	if(id != ''){
		$.ajax({
			type:'POST',
			dataType: 'html',
			data: {id:id,_csrfToken:csrfToken},
			url:url,
			success:function(res) {
				$('.leaveform').html(res);
				
				$('#staticBackdrop12').modal('show');
			}
		}); 
	} 
	 
});
 
$(document).on('click', '.editleaves', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url = '<?php echo Router::url('/', true).'leaves/edit';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#editleaveform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsgsd').html(msg);
				
				$('.editleaves').text('SAVE');
				$('.editleaves').prop('disabled', false);
					
			} 
		}
	}); 
	 
});


$('.searchfilter').click(function() {
	
	$('#searchform').submit();
	 
});
</script>