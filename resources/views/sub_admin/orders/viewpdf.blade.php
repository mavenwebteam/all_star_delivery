
<!doctype html>
				<html>
					<head>
						<title>Order Invoice</title>
						<link href="http://fonts.googleapis.com/css?family=Cabin:400,500,700" rel="stylesheet" type="text/css">
					</head>
					<body style="margin:0; padding:0px 0px 0px 0px;font-family: sans-serif; color:#010101;"> 
						<table cellpadding="0" cellspacing="0" style="margin:0 auto; background:#fff; border-top: 10px solid #333; box-shadow: 0 3px 10px rgba(0,0,0,0.15); width:100%">
							<tbody>
							<tr>
								<td style="vertical-align: top; font-weight: normal; color: #000; line-height: 20px; padding: 15px 10px 5px 0px; width: 80%;">
									<h3 style="font-weight: normal;font-size: 25px; padding:10px 0px 0px 0px; margin:0px 0px 15px;">Invoice</h3>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<tr>
												<td style="padding: 15px 0px 5px 0px; vertical-align: top;">
													<span style="display: block; width: 100%; color: #454545; font-size: 18px;">From</span>

													<h4 style="color: #454545; font-size: 17px; margin:3px 0px;">{{$orderdata->storename}}</h4>
													<p style="margin:0px; font-size: 13px;line-height: 18px;">{{$orderdata->vendoremail}} <span style="display: block;">{{$orderdata->storesddress}}</span></p>
													<p style="margin:2px 0px;">
														<span style="display: block; font-size: 13px;"><strong>Phone:</strong> {{$orderdata->country_code}} - {{$orderdata->mobile}}</span>
														<span style="display: block; font-size: 13px;"><strong>Business Number:</strong> HE392038</span>
													</p>
												</td>
												<td style="padding: 15px 0px 5px 0px; vertical-align: top;">
													<span style="display: block; width: 100%; color: #454545; font-size: 18px;">To</span>

													<h4 style="color: #454545; font-size: 17px; margin:3px 0px;">{{$orderdata->username}}</h4>
													
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="vertical-align: top; padding: 10px 0px 0px 0px; text-align: right;">
									<img style="height:100px; width:100px;" src="https://72.octallabs.com/bringoo/public/media/bringooappicon.png" alt="">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<hr style="border-top:1px solid #efefef;">
								</td>
							</tr>
							
							<tr>
								<td colspan="2" style="padding: 5px 0px;">
									<p style="margin:0px 0px 2px; font-size: 13px;"><span style="display: inline-block; width: 100px;"><strong>Invoice #:</strong></span> INV0021</p>
									<p style="margin:0px 0px 2px; font-size: 13px;"><span style="display: inline-block; width: 100px;"><strong>Date:</strong></span> <?php echo  date('M d, Y', strtotime($orderdata->created_at)); ?></p>
									<p style="margin:0px 0px 2px; font-size: 13px;"><span style="display: inline-block; width: 100px;"><strong>Terms:</strong></span> Due on receipt</p>
								</td>
							</tr>
							<tr>
								<td style=" width: 50%; background: #333333; color: #fff; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									Description
								</td>
								<td style=" width: 50%; background: #333333; text-align: right; color: #fff; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									Amount
								</td>
							</tr>
							@foreach($productdata as $data)
							<tr>
								<td style=" width: 50%; background: #efefef; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
								{{$data->productname}}({{$data->quantity}})
								</td>
								<td style=" width: 50%; background: #efefef; text-align: right; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									${{$data->subtotal}}
								</td>
							</tr>
							@endforeach
							<!--<tr>
								<td style=" width: 50%; background: #efefef; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									Beefeater Pink 1L
								</td>
								<td style=" width: 50%; background: #efefef; text-align: right; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									€16.99
								</td>
							</tr>
							<tr>
								<td style=" width: 50%; background: #efefef; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									Beefeater Pink 1L
								</td>
								<td style=" width: 50%; background: #efefef; text-align: right; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									€16.99
								</td>
							</tr>
							<tr>
								<td style=" width: 50%; background: #efefef; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									Beefeater Pink 1L
								</td>
								<td style=" width: 50%; background: #efefef; text-align: right; color: #000; font-size: 14px; font-weight: 600; padding: 10px 8px;">
									€16.99
								</td>
							</tr>-->
							<tr>
								<td colspan="2" style="padding: 15px 0px 15px 0px">
									<table cellspacing="0" cellpadding="0px" width="100%">
										<tbody>
											<tr>
												<td></td>
												<td style="width: 50%; text-align: right;">
													<table cellpadding="0" cellspacing="0" width="100%" >
														<tbody>
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Subtotal</td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">${{$orderdata->total_amount}}</td>	
															</tr>
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Vat Tax</td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">${{$orderdata->vat_tax}}</td>	
															</tr>
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Shipping Charge </td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">${{$orderdata->total_shipping_amount + $orderdata->picker_amount}}</td>	
															</tr>
															<!--<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Coupon code Amount </td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">${{$orderdata->coupon_discount_amount}}</td>	
															</tr>
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Bonus Amount </td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">${{$orderdata->bonus_amount}}</td>	
															</tr>-->
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px; color:#ff0000;">Wallet Amount </td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px; color:#ff0000;"> - ${{$orderdata->wallet_amount}}</td>	
															</tr>
															<tr>
																<td colspan="2" style="border-bottom: 1px solid #333; height: 15px;"></td>
															</tr>
															<tr>
																<td style="text-align: right; font-size: 13px; padding: 10px 0px 5px 0px;">Grand Total </td>
																<td style="text-align: right; font-size: 13px; padding: 10px 0px 5px 0px;">${{$orderdata->net_amount}}</td>	
															</tr>
															<!--<tr>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">Paid (Aug 2, 2019) </td>
																<td style="text-align: right; font-size: 13px; padding: 0px 0px 5px 0px;">- $16.99</td>	
															</tr>
															<tr>
																<td style="text-align: right; font-size: 16px; font-weight: 600; padding: 0px 0px 5px 0px;">Balance Due </td>
																<td style="text-align: right; font-size: 16px; font-weight: 600; padding: 0px 0px 5px 0px;">$0.00</td>	
															</tr>-->
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>	
							</tbody>
						</table> 
					</body>
				</html>
<?php //$my_pdf_path_for_example = public_path().'/media/pdf/'. 'myPdf.pdf';
		//PDF::loadHTML('admin.orders.viewpdf')->save($my_pdf_path_for_example );
		//return response()->download($my_pdf_path_for_example );?>