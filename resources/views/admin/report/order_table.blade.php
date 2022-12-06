<div class="table-responsive">
  <table class="table table-bordered table-hover">
     <thead>
        <tr>
           <th>Order Id</th>
           <th>Store Name</th>
           <th>Store Email</th>
           <th>Store Mobile</th>
           <th>Store Address</th>
           <th>Order Amount</th>
           <th>Customer Name</th>
           <th>Customer Email</th>
           <th>Customer Mobile</th>
           <th>Order Date</th>
        </tr>
     </thead>
     <tbody>
         @if(count($orders) > 0)
            @foreach($orders as $data)
               <tr>
                  <td>{{$data->order_id}} </td>
                  <td>{{object_get($data,'store.name', '')}}</td>
                  <td>{{object_get($data,'store.email', '')}}</td>
                  <td>{{object_get($data,'store.country_code', '')}}-{{object_get($data,'store.mobile', '')}}</td>
                  <td>{{object_get($data,'store.address', '')}}</td>
                  <td>{{$data->grand_total}}</td>
                  <td>{{object_get($data,'user.fullName', '')}}</td>
                  <td>{{object_get($data,'user.email', '')}}</td>
                  <td>{{'+'.object_get($data,'user.country_code', '')}}-{{object_get($data,'user.mobile', '')}}</td>
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
{!! $orders->links() !!}
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts"> Records {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} (for page {{ $orders->currentPage() }} ) </span>