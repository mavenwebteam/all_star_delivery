{{ Form::select(
				'cat_id',
				[null => 'Please Select Category'] + $categoryList,
				'',
				['id' => 'cat_id','class'=>'form-control']
				) 
			}}