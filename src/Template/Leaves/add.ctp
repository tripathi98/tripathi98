<?php
use Cake\Routing\Router;
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
				<h3>User Leave</h3>
			</div>
			<?= $this->Form->create($leave, ['type' => 'file']) ?>
			<?= $this->Flash->render() ?> 
			<div class="bg-white p-3 rounded mt-3">
			 
				<div class="row">
					<div class="form-group col-md-12">
					<?= 
						$this->Form->select(
							"user_id",
							$users,
							['empty' => 'Select User','required'=>true,"class"=>"form-select mt-3"]
						);
					?> 
					</div>
				</div>
				<div class="row mt-3 clonedata">
					<div class="form-group col-md-5"> 
						<?php
							echo $this->Form->control('date_from[]', [
								'templates' => [
									'inputContainer' => '{{content}}'
								],
								'label' => false, 
								'type' => 'text',
								'id' => 'date_from',
								'autocomplete' => 'off',
								'class' => 'form-control search-gap datepicker',
								'placeholder' => 'Date From',
								'required'=>true
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
								'id' => 'date_to',
								'class' => 'form-control search-gap datepicker',
								'placeholder' => 'Date To',
								'autocomplete' => 'off',
								'required'=>true
							]);
						?>
					</div>
					<div class="form-group col-md-2 tn-buttons">
						<?= $this->Form->button(__('Add'),['type'=>"button",'class'=>"btn btn-dark addmore"]) ?>
					</div>
				</div>
				
				<div id="packagingappendhere">
				</div>
			  
				<div class="section-title"> 
					<?= $this->Form->button(__('Submit'),['class'=>"btn btn-dark mt-3"]) ?>
				</div>
			</div>
			<?= $this->Form->end() ?> 
		</div> 
	</div>
</div> 
<?= $this->Html->css('jquery-ui.css') ?>
<?= $this->Html->script(['jquery-1.12.4.js','jquery-ui.js']) ?> 
<script> 
var dateToday = new Date();
$(document).on('ready', function () { 
	$(".datepicker").datepicker({ 
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		//minDate: dateToday
	});
});


//jquery
var cloneCount = 1;
$(document).on('click', '.addmore', function (ev) {
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
		 
	var $newbuttons = "<button type='button' class='btn btn-danger removemore'>Remove</button>";
	$clone.find('.tn-buttons').html($newbuttons).end().appendTo($('#packagingappendhere'));
	 
});

$(document).on('click', '.removemore', function () {
	$(this).parent().parent().remove();
});
 
</script>  
