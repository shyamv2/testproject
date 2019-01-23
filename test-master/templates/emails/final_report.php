<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$d = $data;
 ?>
	<head>
		<title>Final Report</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<style type="text/css">
			* {
				-ms-text-size-adjust:100%;
				-webkit-text-size-adjust:none;
				-webkit-text-resize:100%;
				text-resize:100%;
				font-family: -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;;
			}
			a{
				outline:none;
				color:#40aceb;
				text-decoration:underline;
			}
			a:hover{text-decoration:none !important;}
			.nav a:hover{text-decoration:underline !important;}
			.title a:hover{text-decoration:underline !important;}
			.title-2 a:hover{text-decoration:underline !important;}
			.btn:hover{opacity:0.8;}
			.btn a:hover{text-decoration:none !important;}
			.btn{
				-webkit-transition:all 0.3s ease;
				-moz-transition:all 0.3s ease;
				-ms-transition:all 0.3s ease;
				transition:all 0.3s ease;
			}
			table td {border-collapse: collapse !important;}
			.ExternalClass, .ExternalClass a, .ExternalClass span, .ExternalClass b, .ExternalClass br, .ExternalClass p, .ExternalClass div{line-height:inherit;}
			@media only screen and (max-width:500px) {
				table[class="flexible"]{width:100% !important;}
				table[class="center"]{
					float:none !important;
					margin:0 auto !important;
				}
				*[class="hide"]{
					display:none !important;
					width:0 !important;
					height:0 !important;
					padding:0 !important;
					font-size:0 !important;
					line-height:0 !important;
				}
				td[class="img-flex"] img{
					width:100% !important;
					height:auto !important;
				}
				td[class="aligncenter"]{text-align:center !important;}
				th[class="flex"]{
					display:block !important;
					width:100% !important;
				}
				td[class="wrapper"]{padding:0 !important;}
				td[class="holder"]{padding:30px 15px 20px !important;}
				td[class="nav"]{
					padding:20px 0 0 !important;
					text-align:center !important;
				}
				td[class="h-auto"]{height:auto !important;}
				td[class="description"]{padding:30px 20px !important;}
				td[class="i-120"] img{
					width:120px !important;
					height:auto !important;
				}
				td[class="footer"]{padding:5px 20px 20px !important;}
				td[class="footer"] td[class="aligncenter"]{
					line-height:25px !important;
					padding:20px 0 0 !important;
				}
				tr[class="table-holder"]{
					display:table !important;
					width:100% !important;
				}
				th[class="thead"]{display:table-header-group !important; width:100% !important;}
				th[class="tfoot"]{display:table-footer-group !important; width:100% !important;}
			}
			hr{
				height: 0px;
				border: 2px solid #ddd;
				color: #eee;
			}
		</style>
	</head>
	<body style="margin:0; padding:0;" bgcolor="#eaeced">
		<br>
		<table style="min-width:320px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eaeced">
			<!-- fix for gmail -->
			<tr>
				<td class="hide">
					<table width="600" cellpadding="0" cellspacing="0" style="width:600px !important;">
						<tr>
							<td style="min-width:600px; font-size:0; line-height:0;">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="wrapper" style="padding:0 10px;">
					<!-- module 2 -->
					<table data-module="module-2" data-thumb="thumbnails/02.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>										<td class="img-flex" style="height: 30px"></td>									</tr>
									<tr>										<td data-bgcolor="bg-block" class="holder" style="padding:58px 60px 52px;" bgcolor="white">											<table width="100%" cellpadding="0" cellspacing="0">

												<tr>
													<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:normal; text-decoration:underline; color:#333;" style="font: 15px/25px Arial, Helvetica, sans-serif; color:#333; padding:0 0 23px;">
													 Dear <?php echo $d['member_name'] ?>, <br><br>
													 We are pleased to inform that your grades for the class <b><?php echo $d['class_name'] ?></b> have been released as shown below.<br><br>

													 <?php echo $d['assigns_grades'] ?>
													 <span style="padding: 10px 15px; border: 1px solid #eee; box-shadow: 1px 2px 6px rgba(0,0,0,.1); border-radius: 5px; background-color: #fafafa; margin-top: 10px; display: block">Final Grade: <b><?php echo $d['f_grade'] ?></b></span>
													 <br>
													 Do not hesitate to contact your instructors if you have any questions!
													 <br><br>
													 <b>- <?php echo $d['teacher_name'] ?></b><br>
													 Contact: <?php echo $d['teacher_email'] ?>

													</td>
												</tr>
											</table>										</td>									</tr>
									<tr><td height="10"></td></tr>
								</table>
								<br>
								<center style="font-size: 11px;">
									<div style="width: 320px">
								  This is an automatic email, which means that if you reply to it, you will likely not get a response... </div>
									<br><a href="http://istudyplatform.com"><img src="http://istudyplatform.com/images/logo-min.png" style="width: 100px;"></a>
								</center>
								<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- fix for gmail -->
			<tr>
				<td style="line-height:0;"><div style="display:none; white-space:nowrap; font:15px/1px courier;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div></td>
			</tr>
		</table>
	</body>
</html>
