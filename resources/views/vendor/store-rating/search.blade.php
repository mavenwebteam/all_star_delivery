<table class="table table-bordered table-hover">
    <thead>
      <tr>
      <th>SR.NO.</th>
      <th>Store</th>
      <th>Rate</th>
      <th>Created</th>
      </tr>
      </thead>
      <tbody>
      <?php $i = 1;?>
        @foreach($review_data as $data)
      <tr>
        <td>{{$i}}</td>
        <td>{{$data->name}}</td>
        <?php  
        $rating =new App\Models\Rating;
        $nodes = $rating->select(DB::raw( 'count( rating.id ) as count'),'total_rating as average')->where('store_id',$data->store_id)->groupBy('total_rating')->get();
						// dd($nodes);
						 $rating_count=0;
						 $rating_total=0;
						 foreach ($nodes as $node) {
							$rating_count += $node->count;
							$rating_total += $node->average * $node->count;
						}
					
						if ($rating_total == 0) {
							$store_rating = 0;
						} else {
							 $store_rating = $rating_total / $rating_count;
            }
            //echo $store_rating;exit;
            ?>
        <td>{{round($store_rating,1)}}</td>
        <td>{{$data->created_at}}</td>
      </tr>  
      <?php $i++;?>
      @endforeach
      @if($i<2)
      <tr>
      <td>No Vendor Review Data</td>
      </tr>
      @endif    
      </tbody> 
    </table>{!! $review_data->links() !!} 