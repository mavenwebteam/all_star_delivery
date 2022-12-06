<table class="table table-bordered table-hover">

                <thead>

                <tr>

                <th>SR.NO.</th>

                  <th>Customer </th>

                  <th>Vendor</th>
				   <th>Category Name</th>
				   <th>Transaction Id</th>
				   <th>Net Amount </th>
				    <th>Total Amount  </th>
					<th>Delivery Charges  </th>
					<th>Delivery Address </th>
					<th> Payment Mode</th>
                  <th>Payment Status</th>
                  <th>Created</th>


                </tr>

                </thead>

                <tbody>

                <?php $i = 1;?>

                  @foreach($payment_data as $data)

                <tr>

                  <td>{{$i}}</td>

                  <td><a  href="javascript:"onclick="view_user_record('{{base64_encode($data->user_id)}}')" > {{$data->username}} </a></td>

                  <td><a  href="javascript:"onclick="view_user_record('{{base64_encode($data->vendor_id)}}')" >{{$data->vendorname}}</a></td>

                  <td>{{$data->catname}}</td>

                  <td>{{$data->tranction_id}}</td>

                  <td>{{$data->amount}}</td>
				   <td>{{$data->amount}}</td>
				    <td>{{$data->delivery_charge}}</td>
					<td>{{$data->delivery_address}}</td>
					<td>@if($data->payment_mode == 1) COD @elseif($data->payment_mode == 2) Card @elseif($data->payment_mode == 3) Online @endif</td>
					<td>{{$data->payment_status}}</td>

                 

                  <td>{{$data->created_at}}</td>


                  

                </tr>  

                <?php $i++;?>

                @endforeach



                @if($i<2)

                <tr>

                <td>No Payment Data</td>

                </tr>

                @endif    

                </tbody> 
    </table>{!! $payment_data->links() !!}  