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
			<div>
				<h3>Roles</h3>
			</div> 
			<div class="bg-white p-3 rounded">
				<?= $this->Flash->render() ?> 
			</div>
			<?php 
				$moduleasdPermission = $this->General->getModulePermission('Roles','can_add',$user_role);

				if($authUser['User']['id'] == 1 || $moduleasdPermission > 0){
			?>
			<div class="bg-white p-3 rounded">
				
				<div class="form-btn">
					<a href="<?= Router::url('/', true); ?>roles/add">
						<button type="button" class="btn btn-dark">Add Role</button>
					</a>
				</div> 
			</div> 
			<?php } ?>
			
			<div class="bg-white p-3 rounded mt-3">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?= $this->Paginator->sort('id') ?></th>
								<th><?= $this->Paginator->sort('name') ?></th> 
								<th><?= $this->Paginator->sort('status') ?></th> 
								<th><?= $this->Paginator->sort('created') ?></th>
								<th><?= $this->Paginator->sort('modified') ?></th> 
								<th class="text-end"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1;
							if(!empty($roles)){
							foreach ($roles as $role): 
							?>
							<tr>
								<td><?= $i ?></td>
								<td><?= h($role->name) ?></td> 
								 
								<td>
									<?php if($role->status == 1){ ?>
										<div class="badge rounded-pill bg-success">Active</div>
									<?php }else{?>
										<div class="badge rounded-pill bg-danger">Inactive</div>
									<?php } ?>
								</td> 
								<td><?= date("d-m-Y",strtotime($role->created)) ?></td>
								<td><?= date("d-m-Y",strtotime($role->modified)) ?></td>
								 
								<td class="text-end">
								  <div class="table-actions">
									<?php 
										$moduleasdsPermission = $this->General->getModulePermission('Roles','can_edit',$user_role);

										if($authUser['User']['id'] == 1 || $moduleasdsPermission > 0){
									?>  
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/edit-light-icon.svg'),
											array(
												'controller' => 'Roles', 
												'action' => 'edit',$role->id
											), array('escape' => false)
										);
									?>
									
									<?php } ?>
									
									<?php 
										$moduleadelPermission = $this->General->getModulePermission('Roles','can_delete',$user_role);

										if($authUser['User']['id'] == 1 || $moduleadelPermission > 0){
									?>
									
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/delete-light-icon.svg'),
											array(
												'controller' => 'Roles', 
												'action' => 'delete',$role->id
											), array('escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $role->id))
										);
									?> 
									<?php } ?>
								  </div>
								</td>
							</tr>
							<?php 
							$i++;
							endforeach; ?>
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