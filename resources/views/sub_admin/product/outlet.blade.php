{{ Form::select(
				'outlet_id',
				[null => 'Please Select Outlet'] + $outletList,
				'',
				['id' => 'outlet_id','class'=>'form-control']
				) 
			}}