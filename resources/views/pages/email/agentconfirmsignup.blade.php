<table width="400" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
	
 <tr>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">Company Name</td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['company_name']}}</td>
  </tr>
  <tr>
 
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">Name</td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['name']}}</td>
  </tr>
  <tr>
 
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">Mobile</td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['mobile']}}</td>
  </tr>
    <tr>
 
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">GST No</td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['gst_no']}}</td>
  </tr>
 
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">Email </td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['email']}}</td>
  </tr>
  <tr>
 
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">PAN No </td>
    <td style="border:1px solid #000; padding:5px; font-family:Arial, Helvetica, sans-serif; font-size:12;">{{ $data['pan_no']}}</td>
  </tr>
</table><div>Please Click to <a href="{{ $data['activate_url']}}">activate</a> or <a href="{{ $data['deactivate_url']}}">deactivate </a> this account</div>