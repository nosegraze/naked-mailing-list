<?php
/**
 * Email
 *
 * @package   naked-mailing-list
 * @copyright Copyright (c) 2017, Ashley Gibson
 * @license   GPL2+
 * @since     1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$body               = "
	background-color: #f6f6f6;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
";
$wrapper            = "
	width:100%;
	-webkit-text-size-adjust:none !important;
	margin:0;
	padding: 70px 0 70px 0;
";
$template_container = "
	box-shadow:0 0 0 1px #f3f3f3 !important;
	border-radius:3px !important;
	background-color: #ffffff;
	border: 1px solid #e9e9e9;
	border-radius:3px !important;
	padding: 20px;
";
$template_header    = "
	color: #00000;
	border-top-left-radius:3px !important;
	border-top-right-radius:3px !important;
	border-bottom: 0;
	font-weight:bold;
	line-height:100%;
	text-align: center;
	vertical-align:middle;
";
$body_content       = "
	border-radius:3px !important;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
";
$body_content_inner = "
	color: #000000;
	font-size:14px;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
	line-height:150%;
	text-align:left;
";
$header_content_h1  = "
	color: #000000;
	margin:0;
	padding: 28px 24px;
	display:block;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
	font-size:32px;
	font-weight: 500;
	line-height: 1.2;
";
$header_img         = nml_get_option( 'email_header_img' );

$template_footer = "
	border-top:0;
	-webkit-border-radius:3px;
";

$credit = "
	border:0;
	color: #000000;
	font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
	font-size:12px;
	line-height:125%;
	text-align:center;
";

$footer_text = nml_get_option( 'email_footer' );
$footer_text = str_replace( '{year}', date( 'Y' ), $footer_text );
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php echo get_bloginfo( 'name' ); ?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="<?php echo $body; ?>">
<div style="<?php echo $wrapper; ?>">
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
		<tr>
			<td align="center" valign="top">
				<?php if ( ! empty( $header_img ) ) : ?>
					<div id="template_header_image">
						<?php echo '<p style="margin-top:0;"><img src="' . esc_url( $header_img ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" /></p>'; ?>
					</div>
				<?php endif; ?>
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="<?php echo $template_container; ?>">
					<tr>
						<td align="center" valign="top">
							<!-- Body -->
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
								<tr>
									<td valign="top" style="<?php echo $body_content; ?>">
										<!-- Content -->
										<table border="0" cellpadding="20" cellspacing="0" width="100%">
											<tr>
												<td valign="top">
													<div style="<?php echo $body_content_inner; ?>">
														<?php
														/**
														 * Inserts content before the email body.
														 *
														 * @since 1.0
														 */
														do_action( 'nml_email_body_before' );
														?>
														{email}
														<?php
														/**
														 * Inserts content after the email body.
														 *
														 * @since 1.0
														 */
														do_action( 'nml_email_body_after' );
														?>
													</div>
												</td>
											</tr>
										</table>
										<!-- End Content -->
									</td>
								</tr>
							</table>
							<!-- End Body -->
						</td>
					</tr>
					<?php if ( ! empty( $footer_text ) ) : ?>
						<tr>
							<td align="center" valign="top">
								<!-- Footer -->
								<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer" style="<?php echo $template_footer; ?>">
									<tr>
										<td valign="top">
											<table border="0" cellpadding="10" cellspacing="0" width="100%">
												<tr>
													<td colspan="2" valign="middle" id="credit" style="<?php echo $credit; ?>">
														<?php
														/**
														 * Inserts content before the footer text.
														 *
														 * @since 1.0
														 */
														do_action( 'nml_email_before_footer_text' );

														echo wpautop( wp_kses_post( wptexturize( $footer_text ) ) );

														/**
														 * Inserts content after the footer text.
														 *
														 * @since 1.0
														 */
														do_action( 'nml_email_after_footer_text' );
														?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!-- End Footer -->
							</td>
						</tr>
					<?php endif; ?>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
</html>