{{ Form::select(
				'state_id',
				[null => 'Please Select State'] + $stateList,
				'',
				['id' => 'state_id','class'=>'form-control']
				) 
			}}