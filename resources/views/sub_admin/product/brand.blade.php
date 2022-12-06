{{ Form::select(
				'brand_id',
				[null => 'Please Select Brand'] + $brandList,
				'',
				['id' => 'brand_id','class'=>'form-control']
				) 
			}}