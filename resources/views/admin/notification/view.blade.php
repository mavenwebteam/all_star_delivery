<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
    });
</script>

<table class="table table-bordered table-hover">
<tbody>
    <tr> <th>User Name</th><td>{{$noti_data->username}}</td></tr>
    <tr> <th>Notification Type</th><td>{{$noti_data->noti_type}}</td></tr>
    <tr> <th>Notification</th><td>{{$noti_data->notification}}</td></tr>
    <tr> <th>Created</th><td>{{$noti_data->created_at}}</td></tr>
</tbody>
</table>