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
				<h3>Leaves</h3>
			</div>
			 
			<div class="bg-white p-3 rounded">
				<?= $this->Flash->render() ?>  
				  <div class="form-btn">
					<a href="<?= Router::url('/', true);?>leaves/add">
						<button type="button" class="btn btn-dark">Add New</button>
					</a>
				  </div> 
			</div>
			 
			
			<div class="bg-white p-3 rounded mt-3">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?= 'SNo.' ?></th>
								<th>User Name</th> 
								<th>Status</th> 
								<th><?= $this->Paginator->sort('date_from') ?></th>
								<th><?= $this->Paginator->sort('date_to') ?></th> 
								<th class="text-end"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if(!empty($leaves)){
								$i=1;
							foreach ($leaves as $leave): 
							?>
							<tr>
								<td><?= $i ?></td>
								<td><?= h($leave->user->name) ?></td> 
								 
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
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/edit-light-icon.svg'),
											array(
												'controller' => 'Leaves', 
												'action' => 'edit',$leave->id
											), array('escape' => false)
										);
									?>
									 
									
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/delete-light-icon.svg'),
											array(
												'controller' => 'Leaves', 
												'action' => 'delete',$leave->id
											), array('escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $leave->id))
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