<table class="table table-bordered table-hover">

                <thead>

                <tr>

                <th>SR.NO.</th>

                  <th>Vendor</th>
				   <th>Name</th>

                  <th>Days</th>
                  <th>Amount</th>
                  <th>Created</th>

                  <th style="text-align:center;">Action</th>

                </tr>

                </thead>

                <tbody>

                <?php $i = 1;?>

                  @foreach($package_data as $data)

                <tr>

                  <td>{{$i}}</td>

                  <td>{{$data->vendoremail}}</td>
				   <td>{{$data->name}}</td>
                  <td>{{$data->days}}</td>

                  <td>{{$data->amount}}</td>

                  <td>{{$data->created_at}}</td>

                  <td class="res-dropdown" style="" align="center">

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Package" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>

                                     

                </td>

                </tr>  

                <?php $i++;?>

                @endforeach



                @if($i<2)

                <tr>

                <td>No Package Data</td>

                </tr>

                @endif    

                </tbody> 

                

    </table>

    {!! $package_data->links() !!}   Records {{ $package_data->firstItem() }} - {{ $package_data->lastItem() }} of {{ $package_data->total() }} (for page {{ $package_data->currentPage() }} )   