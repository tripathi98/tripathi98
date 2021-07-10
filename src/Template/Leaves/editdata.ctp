<?php
use Cake\Routing\Router; 
?> 
<?= $this->Form->create($leave,['class'=>"login-form",'id'=>"editleaveform"]) ?>

<?php
	echo $this->Form->hidden('id',['value'=>$leave->id]); 
?>

<div class="modalBodyContent">
	<div class="successmsg"></div>
	<div class="row innerGap borderForSection"> 
		<div class="col-sm-6">
			<label>User</label> 
			<?= 
				$this->Form->select(
					"user_id",
					$usersList,
					['empty' => 'Select User','required'=>true,"class"=>"form-select mt-3","value"=>$leave->user_id]
				);
			?>
		</div>
		  
		<div class="row mt-3">
			<div class="form-group col-md-5"> 
				<?php
					echo $this->Form->control('date_from', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false, 
						'type' => 'text',
						'id' => 'date_from',
						'autocomplete' => 'off',
						'class' => 'custom-control search-gap datepicker1',
						'placeholder' => 'Date From',
						'required'=>false
					]);
				?>
			</div>
			<div class="form-group col-md-5"> 
				<?php
					echo $this->Form->control('date_to', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false, 
						'type' => 'text',
						'id' => 'date_to',
						'class' => 'custom-control search-gap datepicker1',
						'placeholder' => 'Date To',
						'autocomplete' => 'off',
						'required'=>false
					]);
				?>
			</div> 
		</div>  
	</div>
	  
	   
</div>
<?= $this->Form->end() ?>
<script>
$(".datepicker1").datepicker({ 
	dateFormat: "yy-mm-dd",
	changeMonth: true,
	//minDate: dateToday
});
</script>   
