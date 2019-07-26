<?php
$img = get_field('main_banner_image');
$title1 = get_field('banner_image_main_title');
$title2 = get_field('banner_image_secondary_title');
$button_label = get_field('banner_image_button_text');
$button_link = get_field('banner_image_button_link');
$has_button = ($button_label && $button_link) ? true:false;
if($img) { ?>
<div class="banner clear" style="background-image:url('<?php echo $img['url']?>');">
	<img src="<?php echo $img['url']?>" alt="<?php echo $img['title']?>" />
	<?php if($title1 || $title2) { ?>
	<div class="banner-overlay"></div>
	<div class="banner-caption animated zoomIn">
		<div class="inside clear">
			<div class="caption clear<?php echo ($has_button) ? ' has-button':'';?>">
				<?php if($title1) { ?>
				<h2 class="title1"><?php echo $title1; ?></h2>
				<?php } ?>
				<?php if($title2) { ?>
				<p class="title2"><?php echo $title2; ?></p>
				<?php } ?>
			</div>
			<?php if($has_button) { ?>
			<div class="button">
				<a href="<?php echo $button_link; ?>"><?php echo $button_label; ?></a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
