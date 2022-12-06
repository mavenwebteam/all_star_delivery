<div class="table-responsive">
  <table class="table table-bordered table-hover">
     <thead>
        <tr>
           <th>Minimum Distance (KM)</th>
           <th>Maximum Distance (KM)</th>
           <th>Fee</th>
           <th>Fee Per KM</th>
           <th>Created</th>
           <th style="text-align:center;">Action</th>
        </tr>
     </thead>
     <tbody>
        <tr>
           <td>{{$customerFee->min_distance}}</td>
           <td>{{$customerFee->max_distance}}</td>
           <td>{{$customerFee->fee}}</td>
           <td>{{$customerFee->delivery_fee_per_km}}</td>
           <td>{{ $customerFee->created_at}}</td>
           <td class="res-dropdown" style="" align="center">
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Fee" onclick="edit_record('{{base64_encode($customerFee->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
           </td>
        </tr>
        
     </tbody>
  </table>
 
</div>
