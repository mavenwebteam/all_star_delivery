



 
<!doctype html>
				<html>
					<head>
						<title>Business Finance</title>
						<link href="http://fonts.googleapis.com/css?family=Cabin:400,500,700" rel="stylesheet" type="text/css">
					</head>
					<body style="margin:0; padding:0px 0px 0px 0px;font-family: sans-serif; background:#ccc; color:#010101;"> 
						<table cellpadding="0" cellspacing="0" style="margin:0 auto; background:#fff; border-top: 10px solid #333; box-shadow: 0 3px 10px rgba(0,0,0,0.15); width:100%">
							<tbody>
							<tr>
								<td><p style="color:#fff; line-height:5px;">transparent </p></td>
							</tr>
								<tr>
									<td style="text-align: left; padding: 50px 7px 50px 7px;line-height: 5px; width:80%; vertical-align:top;">
										<h3 style="font-weight: 600;font-size: 40px; margin:0px 0px 15px; text-align:left; line-height: 4px;">Invoice</h3>
										<table cellpadding="0" cellspacing="0" width="80%" style="vertical-align:top;">
											<tbody>
												<tr>
													<td style="vertical-align: top;">
														<span style="display: block; width: 100%; line-height:4px; color: #454545; font-size: 45px;">From</span>
														<h4 style="color: #454545; font-size: 50px;line-height:3px; color:#333;">Bringo Ltd</h4>
														<p style="margin:0px; font-size: 35px;line-height: 4px;">
														lee@Bringo.com 
															<br>
															5 Iapetou street
															<br>
															Limassol
															<br>
															4101
															<br>
															<strong style="line-height: 0px; ">Phone:</strong>+35799990013<br>
															<strong style="line-height: 8px;">Business Number:</strong>HE392038
														</p>
													</td>
													<td style="vertical-align: top;">
														<span style="display: block; width: 100%; line-height:4px; color: #454545; font-size: 45px;">To</span>
														<h4 style="color: #454545; font-size: 50px;line-height:3px;  color:#333;">{{$orderdata->username}}</h4>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td style="padding: 50px 7px 50px 7px;line-height: 14px; width:20%;">
										<img style="height:100px; width:100px;" src="http://192.168.1.124/bringoo/public/media/bringooappicon.png" alt="">
									</td>
								</tr>
								<tr>
									<td colspan="" style="width: 100%; font-family: Arial; line-height: 3px; padding: 20px 0px 0px 0px;border-top:1px solid #efefef; ">
										
									</td>
								</tr>
								<tr>
									<td style="margin:0px; font-size: 35px;line-height: 4px; width:100px;in-width:100px;"> Order Id #:
									</td>
									<td style="margin:0px; font-size: 35px;line-height: 4px;"> {{$orderdata->id}}
									</td>
								</tr>
								<tr>
									<td style="margin:0px; font-size: 35px;line-height: 4px; width:100px;in-width:100px;"> Date:
									</td>
									<td style="margin:0px; font-size: 35px;line-height: 4px;"> <?php echo  date('Y-m-d', strtotime($orderdata->created_at)); ?>
									</td>
								</tr>
								<tr>
									<td style="margin:0px; font-size: 35px;line-height: 4px; width:100px;in-width:100px;"> Terms:
									</td>
									<td style="margin:0px; font-size: 35px;line-height: 4px;"> Due on receipt
									</td>
								</tr>
								<tr>
									<td style="height:20px;"> </td>
								</tr>
								<tr>
									<td style="width: 50%;line-height: 8px; background-color: #333333; color: #fff; font-size: 40px; font-weight: 600; padding: 10px 8px;"><strong>    Description</strong></td>
									<td style="width: 50%;line-height: 8px; background-color: #333333; color: #fff; font-size: 40px; font-weight: 600; padding: 10px 8px; text-align:right;"><strong>Amount   </strong> </td>
								</tr>
								<?php $helper=new App\Helpers;
			if(!empty($helper->SelectProductItem($orderdata->id))){ 
					$productdata = $helper->SelectProductItem($orderdata->id); ?>
						@foreach($productdata as $product)
								<tr>
									<td style="width: 50%;line-height: 8px; background-color: #efefef; color: #333; font-size: 40px; font-weight: 600; padding: 10px 8px;"><strong>    {{$product->productname}}({{$product->quantity}})</strong></td>
									<td style="width: 50%;line-height: 8px; background-color: #efefef; color: #333; font-size: 40px; font-weight: 600; padding: 10px 8px; text-align:right;"><strong> $ {{$product->subtotal}}   </strong> </td>
								</tr>
									
							@endforeach
			<?php } ?>
								<tr>
									<td style="width: 50%;line-height: 8px; color: #333; font-size: 40px; font-weight: 600; padding: 10px 8px;"></td>
									<td style="width: 50%;line-height: 8px;color: #333; font-size: 40px; font-weight: 600; padding: 10px 8px; text-align:right;">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td style="margin:0px; font-size: 35px;line-height: 5px;"> Subtotal </td>
												<td style="margin:0px; font-size: 35px;line-height: 5px; text-align:right;"> $ {{$orderdata->net_amount}} </td>
											</tr>
											<tr>
												<td style="margin:0px; font-size: 35px;line-height: 5px;"> Taxable </td>
												<td style="margin:0px; font-size: 35px;line-height: 5px; text-align:right;"> $ </td>
											</tr>
											
											<tr>
												<td style="margin:0px; font-size: 35px;line-height: 7px;"> Delivery Charges </td>
												<td style="margin:0px; font-size: 35px;line-height: 7px; text-align:right;">  $ {{$orderdata->total_shipping_amount}} </td>
											</tr>
											<tr>
												<td style="margin:0px; font-size: 35px;line-height: 7px; border-bottom: 1px solid #000;"> Discount </td>
												<td style="margin:0px; font-size: 35px;line-height: 7px; text-align:right; border-bottom: 1px solid #000;">  $ {{$orderdata->coupon_discount_amount}} </td>
											</tr>
											<tr>
												<td style="margin:0px; font-size: 35px;line-height: 9px;"> Total </td>
												<td style="margin:0px; font-size: 35px;line-height: 9px; text-align:right;"> $ {{$orderdata->total_amount}} </td>
											</tr>
											
										</table>
									</td>
								</tr>
								
							</tbody>
						</table> 
					</body>
				</html>
				