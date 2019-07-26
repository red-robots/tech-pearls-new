<?php  
$menuName='Main';
$sortbyMenu=false;
$links = generate_sitemap($menuName,$sortbyMenu);
if($links) { ?>
    <div class="page-link-list clear">
        <ul class="linklist">
        <?php foreach($links as $a) {
            $parent_id = $a['id'];
            $parent_title = $a['title'];
            $parent_url = $a['url'];
            $external_link = ( isset($a['external_link']) && $a['external_link'] ) ? true:false;
            $target_link = ($external_link) ? ' target="_blank"':'';
            $children  = ( isset($a['children']) ) ? $a['children'] : '';
            $categories  = ( isset($a['categories']) ) ? $a['categories'] : '';
            if($parent_title!=='Sitemap') {  ?>
            <li>
                <a class="parentlink"  href="<?php echo $parent_url; ?>"<?php echo $target_link;?>><?php echo $parent_title;?></a>
                <?php if($children) { ?>
                <ul class="children-links">
                    <?php foreach($children as $c) { ?>
                    <li>
                        <a class="childlink" href="<?php echo $c['url']; ?>"<?php echo $target_link;?>><?php echo $c['title']; ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
                <?php if($categories) { ?>
                <ul class="children-links">
                    <?php foreach($categories as $cat) { 
                    $term_link = get_term_link($cat->term_id); ?>
                    <li>
                        <a class="childlink cat" href="<?php echo $term_link; ?>"><?php echo $cat->name; ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
        <?php } ?>
        </ul>    
    </div>
<?php } ?>