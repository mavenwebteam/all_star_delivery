<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php //echo Config::get("Site.title"); ?></title>
	</head>
	<body style="margin:0; padding:0;">
		<table cellspacing="0" cellpadding="0" align="center" style="width: 650px; margin: 0 auto; font-family: arial;">
	<tbody>
		<tr>
			<td style="border: 1px solid #d82626; text-align: center; padding: 15px;border-radius: 5px 5px 0px 0px;">
				<a href="#"><img width="100" height="100" src="{{asset('/assets/img/logo.png') }}" alt="#"></a>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border: 1px solid #d82626; border-top:0px; border-bottom: 0px; padding: 15px;">
				<table cellspacing="0" cellpadding="0" width="650px">
					<!--<tr>
						<td style="font-weight: bold; font-size: 20px;">Hi Narendra babu,</td>
					</tr>-->
					<tr>
						<td style="font-size: 15px; line-height: 22px; padding: 15px 0px 15px 0px;">
						 <?= $messageBody ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" style="font-family: sans-serif;font-weight:normal;color:#fff;font-size:14px;line-height:20px; padding: 20px 10px 5px 10px; text-align: center; background: #d82626;">
				<table cellspacing="0" cellpadding="0" align="center" style="width: 650px; margin: 0 auto; font-family: arial;">
					
					<tr>
						<td style="padding: 20px 0px 10px 0px;text-align: center;font-family: sans-serif;">
							<span style="display: inline-block; padding: 5px 10px 0px 10px;"><a href=""><img alt="Download Android App" src="{{asset('/assets/img/ios.png') }}" style="width:134px;outline:0" /> </a></span>
							<span style="display: inline-block; padding: 5px 10px 0px 10px;"><a download="" href=""><img alt="Download iOS App" src="{{asset('/assets/img/android.png') }}" style="width:134px;outline:0" /> </a></span>
							
						</td>
					</tr>
					<tr>
						<td style="padding: 25px 0px 10px 0px;font-family: sans-serif;font-size:12px;color:#fff;font-weight:normal;line-height:1;text-align: center;">
							<a href="{{ URL::to('/contact-us') }}" style="padding:5px 10px;color:#fff; font-weight: 600;" target="_blank">Contact Us</a>| 
							<a href="{{ URL::to('/pages/about-us') }}" style="padding:5px 10px;color:#fff; font-weight: 600;" target="_blank">About us</a>| 
							<a href="{{ URL::to('/pages/faq') }}" style="padding:5px 10px;color:#fff; font-weight: 600;" target="_blank">FAQ</a>
						</td>
					</tr>
					<tr>
						<td style="text-align:center;padding:20px 20px 5px 20px;font-family: sans-serif;font-size:14px;color:#fff;font-weight:normal;line-height:16px">All Star Delivery</td>
					</tr>
					<tr>
						<td style="text-align:center;padding:5px 20px 20px 20px; font-family: sans-serif; font-size:14px;color:#fff;font-weight:normal;line-height:16px">{{Config::get("Site.copy_right")}}</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>

	</body>
</html>