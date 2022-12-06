<div class="table-responsive">
  <table class="table table-bordered table-hover">
     <thead>
        <tr>
           <th>Order Id</th>
           <th>Store Name</th>
           <th>Order Amount</th>
           <th>Earning</th>
           <th>Order Date</th>
        </tr>
     </thead>
     <tbody>
         @if(count($earning) > 0)
            @foreach($earning as $data)
               <tr>
                  <td>{{$data->order_id}} </td>
                  <td>{{object_get($data,'store.name', '')}}</td>
                  <td>{{$data->grand_total}}</td>
                  <td>{{$data->amount_payable_to_store}}</td>
                  <td>{{$data->created_at}}</td>
               </tr>
            @endforeach
         @else
            <tr>
               <td colspan="8" style="text-align:center;">No Data Found</td>
            </tr>
         @endif    
     </tbody>
  </table>
</div>
{!! $earning->links() !!}
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts"> Records {{ $earning->firstItem() }} - {{ $earning->lastItem() }} of {{ $earning->total() }} (for page {{ $earning->currentPage() }} ) </span>