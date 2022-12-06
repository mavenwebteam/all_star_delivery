<div class="table-responsive">
  <table class="table table-bordered table-hover">
     <thead>
        <tr>
           <th>Store Name</th>
           <th>Store Email</th>
           <th>Store Mobile</th>
           <th>Store Address</th>
           <th>Register Date</th>
        </tr>
     </thead>
     <tbody>
         @if(count($stores) > 0)
            @foreach($stores as $data)
               <tr>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->email }}</td>
                  <td>{{ $data->country_code }} - {{ $data->mobile }}</td>
                  <td>{{ $data->address }}</td>
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
{!! $stores->links() !!}
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts"> Records {{ $stores->firstItem() }} - {{ $stores->lastItem() }} of {{ $stores->total() }} (for page {{ $stores->currentPage() }} ) </span>