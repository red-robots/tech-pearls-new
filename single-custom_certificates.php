<?php

$template_dir = get_template_directory();
$learndash_plugin_path = ABSPATH . 'wp-content/plugins/pearls/';
require_once $learndash_plugin_path . 'public/dompdf/lib/html5lib/Parser.php';
require_once $learndash_plugin_path . 'public/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once $learndash_plugin_path . 'public/dompdf/lib/php-svg-lib/src/autoload.php';
require_once $learndash_plugin_path . 'public/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

//$certificateTemplate = get_template_directory_uri().'/images/certificateTemplate.jpg';
$post_id = get_the_ID();
$certificateImg = get_field('background',$post_id);
$certificateTemplate = ($certificateImg) ? $certificateImg['url'] : '';

$create_pdf = false;

if ( ( isset( $_GET['course_id'] ) ) && ( ! empty( $_GET['course_id'] ) ) ) {
	$course_id = intval( $_GET['course_id'] );
	if ( ( ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( ( isset( $_GET['user'] ) ) && ( ! empty( $_GET['user'] ) ) ) ) {
		$cert_user_id = intval( $_GET['user'] );
	} else {
		$cert_user_id = get_current_user_id();
	}

	$view_user_id = get_current_user_id();

	if ( ( isset( $_GET['cert-nonce'] ) ) && ( ! empty( $_GET['cert-nonce'] ) ) ) {
		if ( wp_verify_nonce( esc_attr( $_GET['cert-nonce'] ), $course_id . $cert_user_id . $view_user_id ) ) {
			$create_pdf = true;			
		}
	}

}
if($create_pdf) {
ob_start();?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<style type="text/css">
body{
	font-family: "Franklin Gothic Medium", "Franklin Gothic", "ITC Franklin Gothic", Arial, sans-serif;
	font-size: 18px;
	line-height: 1.3;
	color: black;
}
p {
	margin-top: 0;
	margin-bottom: 10px;
}
.bgimg {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%; 
	<?php if($certificateTemplate) { ?>
	background:url('<?php echo $certificateTemplate;?>');
	<?php } ?>
}
img.cert {
	position: fixed;
	top: -12px;
	left: -8px;
	width: 102%;
	height: auto;
}
.text-content {
	position: relative;
	top: 0;
	left: 0;
	z-index: 20;
	width: 80%;
	margin: 0 auto;
	padding: 28% 0 0;
	text-align: center;
}
.subject {
	font-size: 30px;
	font-weight: bold;
	line-height: 1.3;
}
.fullname {
	font-size: 30px;
	font-weight: bold;
	line-height: 1.3;
}
.datecompleted {
	font-size: 20px;
	font-weight: bold;
	line-height: 1.3;
}
</style>
<?php $course = get_post( $course_id );  $page_title = ($course) ? $course->post_title : ''; ?>
<title>Certificate of Completion - <?php echo $page_title; ?></title>
</head>
<body>
<img class="cert" src="<?php echo $certificateTemplate;?>" alt="">
<div class="text-content">
	<?php while ( have_posts() ) : the_post(); ?>
	<?php  
		$course_status = learndash_course_status( $course_id, $cert_user_id );
		$course_title = ($page_title) ? '<p class="subject">'.$page_title.'</p>' : '';

		$completed_on = get_user_meta( $cert_user_id, 'course_completed_' . $course_id, true );
		$date_completed = date('F d, Y',$completed_on);
		$date_completed = '<p class="datecompleted">'.$date_completed.'</p>';

		/* possible shortcodes - depends on comma used. */
		$rep_course_info1 = "[courseinfo show='course_title']";
		$rep_course_info2 = '[courseinfo show="course_title"]';
		$rep_first_name1 = '[usermeta field="first_name"]';
		$rep_first_name2 = "[usermeta field='first_name']";
		$rep_last_name1 = '[usermeta field="last_name"]';
		$rep_last_name2 = "[usermeta field='last_name']";
		$rep_completed_on1 = "[courseinfo show='completed_on']";
		$rep_completed_on2 = '[courseinfo show="completed_on"]';

		$userdata = new WP_User( $cert_user_id );
		$first_name = $userdata->first_name;
		$last_name = $userdata->last_name;
		$fname = array($first_name,$last_name); 
		$fname = ($fname && array_filter($fname)) ? array_filter($fname) : '';
		$the_name = ($fname) ? implode(" ",$fname) : '';
		$full_name = '';
		if($the_name){
			$full_name = '<p class="fullname">'.$the_name.'</p>';
		}

		$content = get_the_content();
		$content = preg_replace( '|\[pdf[^\]]*?\].*?\[/pdf\]|i', '', $content );
		$content = str_replace($rep_course_info1, $course_title, $content);
		$content = str_replace($rep_course_info2, $course_title, $content);
		$content = str_replace($rep_first_name1, $first_name, $content);
		$content = str_replace($rep_first_name2, $first_name, $content);
		$content = str_replace($rep_last_name1, $last_name, $content);
		$content = str_replace($rep_last_name2, $last_name, $content);
		$content = str_replace($rep_completed_on1, $date_completed, $content);
		$content = str_replace($rep_completed_on2, $date_completed, $content);

		/* Full Name */
		//$the_content = str_replace($first_name, $full_name, $content);
		//$content = str_replace($first_name, $full_name, $content);
		$content = apply_filters('the_content',$content);
		echo $content;
	?>
	<?php endwhile; ?>
</div>

</body>
</html>
<?php
	if ( is_user_logged_in() ) {
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);

		$dompdf->set_option('defaultMediaType', 'all');
		$dompdf->set_option('isFontSubsettingEnabled', true);

		$html = ob_get_clean();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream("certificate_of_completion.pdf", array("Attachment" => 0)); 
		$pdf_gen = $dompdf->output();
	}
} else {
	echo "<h2>Access to certificate page is disallowed.</h2>";
}
?>