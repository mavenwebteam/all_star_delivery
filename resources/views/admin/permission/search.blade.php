
<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <th>Permission</th>
          <th>Route</th>
          <th style="text-align:center;">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($permissions) > 0)
       @foreach($permissions as $value)
       {{-- check user permission --}}
       @php
         $user_id = object_get($value, 'userHasPermission.user_id', NULL);
       @endphp
           <tr>
              <td>{{ $value->title }}</td>
              <td>{{ $value->name }}</td>
              <td align="center">
               <input type="checkbox" value="{{ $value->id }}" class="permission custom-control-input" id="managerCustomCheck{{ $value->id }}"
                  @if($user_id) checked @endif>
               <label class="custom-control-label" for="managerCustomCheck{{ $value->id }}"></label></div>
              </td>
           </tr>
        @endforeach
        @else
           <tr>
              <td colspan="2" style="text-align:center;">No Data Found</td>
           </tr>
        @endif    
    </tbody>
 </table>
</div>

	{!! $permissions->links() !!} 

	Records {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }} of {{ $permissions->total() }} (for page {{ $permissions->currentPage() }} )