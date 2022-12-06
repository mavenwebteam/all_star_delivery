<div class="table-responsive"><table class="table table-bordered table-hover">
                <thead>
                <tr>
                  {{-- <th>SR.NO.</th> --}}
                  <th>Title</th>
                  <th>Subject</th>
                  <th>Created</th>
                  <th style="text-align:center;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;?>
                  @foreach($email_templates_data as $data)
                <tr>
                  {{-- <td>{{$i}}</td> --}}
                  <td>{{$data->title}}</td>
                  <td>{{$data->subject}}</td>
                  <td>{{$data->created_at}}</td>
                  <td class="res-dropdown" style="" align="center">
                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Email Template" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
                  <!-- <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Content" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a></a> -->
                
                </td>
                </tr>  
                <?php $i++;?>
                @endforeach

                @if($i<2)
                <tr>
                <td>No Email Templates Data</td>
                </tr>
                @endif    
                </tbody> 
                
    </table></div>{!! $email_templates_data->links() !!} <style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>	<span class="pagecounts">  Records {{ $email_templates_data->firstItem() }} - {{ $email_templates_data->lastItem() }} of {{ $email_templates_data->total() }} (for page {{ $email_templates_data->currentPage() }} ) </span>