<?php 
$current_user = wp_get_current_user();
$user_id = $current_user->ID; 
?>

<form method="post">
    <input type="submit" value="download pdf">
    <?php wp_nonce_field('pdf_button_clicked'); ?>
    <input type="hidden" name="my_courses_pdf" value="submitted">
</form>


<div id="learndash_profile">
	<div class="learndash_profile_heading">
		<span><?php esc_html_e( 'Profile', 'learndash' ); ?></span>
	</div>

	<div class="profile_info clear_both">
		<div class="profile_avatar">
			<?php echo get_avatar( $current_user->user_email, 96 ); ?>
			<?php
			
			if ( ( current_user_can( 'read' ) ) ) {
				//$edit_user_link = get_edit_user_link();
				$edit_user_link = get_site_url() . '/my-account/edit-account/';
				?>
				<div class="profile_edit_profile" align="center">
					<a href='<?php echo $edit_user_link; ?>'><?php esc_html_e( 'Edit profile', 'learndash' ); ?></a>
				</div>
				<?php
			}
			?>
		</div>

		<div class="learndash_profile_details">
			<?php if ( ( ! empty( $current_user->user_lastname) ) || ( ! empty( $current_user->user_firstname ) ) ): ?>
				<div><strong><?php esc_html_e( 'Name', 'learndash' ); ?>:</strong> <?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?></div>
			<?php endif; ?>
			<div><strong><?php esc_html_e( 'Username', 'learndash' ); ?>:</strong> <?php echo $current_user->user_login; ?></div>
			<div><strong><?php esc_html_e( 'Email', 'learndash' ); ?>:</strong> <?php echo $current_user->user_email; ?></div>
			<div><strong>Total credit hours:</strong> <?php echo $user_couse_points = learndash_get_user_course_points( $user_id ); ?></div>
		</div>
	</div>

<?php

$atts = array(
	'return' => true, // Set to true to return the array data nstead of calling the template for output. 
	// This function essentially produces the output of three sections. Registered Courses, 
	// Course Progress and Quiz Attempts. This parameters lets us control which section to 
	// return or all.  
	// 'type' => array('registered','course','quiz' ), 
	'type' => array('course'), 

	// Defaults
	'num' => (int)0,
	'orderby' => 'title',
	'order' => 'ASC',
	//'course_ids' => null,
	//'quiz_ids' => null,
	'group_id' => null,
	
	// Registered Courses 
	'registered_num' => 0, 
	'registered_show_thumbnail' => 'true',
	'registered_orderby' => 'title',
	'registered_order' => 'ASC',

	// Course Progress
	'progress_num' => 0, 
	'progress_orderby' => 'title',
	'progress_order' => 'ASC', 

	// Quizzes
	'quiz_num' => false, 
	'quiz_orderby' => 'taken',
	'quiz_order' => 'DESC', 
);

$course_info_list = SFWD_LMS::get_course_info( $user_id, $atts );

// courses they have either completed or are registered for
$courses_array = array_keys($course_info_list['course_progress']);

// split $courses_array into $completed_courses and $available_courses in case you want them later
$completed_courses = array();
$available_courses = array();

foreach($courses_array as $course_id){
	// see if they've completed the course
	$muh_metakey = 'course_completed_' . $course_id;
	$is_completed = get_user_meta($user_id, $muh_metakey, true);
	
	if($is_completed){
		// add to completed array
		$completed_courses[] = $course_id;
	}else{
		$available_courses[] = $course_id;
	}
}
?>

	<table class="wide">
		<thead>
			<th class="ld_profile_status">Course Status</th>
			<th class="ld_profile_certificate">Certificate</th>
		</thead>
		<tbody>

<?php if ( ! empty( $courses_array ) ) : ?>
	<?php foreach ( $courses_array as $course_id ) : ?>
		<?php
            $course = get_post( $course_id );
            $certificateLink = learndash_get_course_certificate_link( $course_id, $user_id );
            $certificateLink = str_replace('course_id','courseId',$certificateLink);
            $course_link = get_permalink( $course_id );
            //$certificateLink = get_site_url() . '/certificates/certificate-of-completion/?couseId=' . $course_id; 

            $progress = learndash_course_progress( array(
                'user_id'   => $user_id,
                'course_id' => $course_id,
                'array'     => true
            ) );

            $completed_on = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
            $status = ( !empty($completed_on) ) ? 'completed' : 'notcompleted';

        ?>

			<tr>
				<td>
					<?php if(!$completed_on): ?>
						<a class="<?php echo esc_attr( $status ); ?>" href="<?php echo esc_attr( $course_link ); ?>">
							<?php echo $course_id; ?> <?php echo $course->post_title; ?>
						</a>
					<?php else: ?>
						<a class="<?php echo esc_attr( $status ); ?>">
							<?php echo $course_id; ?> <?php echo $course->post_title; ?>
						</a>
					<?php endif; ?>
				</td>
				<td>
					<?php if(($completed_on) && ($certificateLink)): ?><a target="_blank" href="<?php echo esc_attr( $certificateLink ); ?>"><div class="certificate_icon_large"><?php endif; ?>
				</td>
			</tr>
	<?php endforeach; ?>
<?php endif; ?>
		</tbody>
	</table>
</div>