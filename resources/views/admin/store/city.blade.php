{{ Form::select(
				'city_id',
				[null => 'Please Select City'] + $cityList,
				'',
				['id' => 'city_id','class'=>'form-control']
				) 
			}}