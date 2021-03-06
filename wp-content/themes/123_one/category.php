<?php 

if( !get_field('blog-toggle', 'option') ){
	header( "Location: " . site_url() . "/404.php" );
}

get_header(); ?>

<main class="blog">
	<section class="blog-hero hero" style="background-image: url('<?php echo get_field('general-blog-bg', 'option'); ?>');">
		<div class="blog-hero-text hero-text">
			<h1 class="blog-hero-text-header hero-text-header"><?php single_term_title('', true); ?></h1>
		</div>
		<div class="blog-hero-tint hero-tint"></div>
	</section>
	<section class="blog-blog">
		<?php 
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$the_query = new WP_Query(array(
			'post_type' => 'post',
			'posts_per_page' => get_field('posts-per-page', 'option'),
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'name',
					'terms' => single_term_title('', false),
				),
			),
		));
		if($the_query->have_posts()) : ?>
		<div class="blog-blog-grid">
			<?php while($the_query->have_posts()): $the_query->the_post();  ?>
			<div class="blog-blog-grid-item">
				<div class="blog-blog-grid-item-textcontainer">
					<a href="<?php echo get_permalink(); ?>" class="blog-blog-grid-item-textcontainer-header"><?php echo $post->post_title; ?></a>
					<div class="blog-blog-grid-item-textcontainer-date"><?php echo 'Posted on: ' . date('n/j/Y', strtotime($post->post_date)) . ' at ' . date('g:i A', strtotime($post->post_date)); ?></div>
					<div class="blog-blog-grid-item-socialcontainer"><?php render_post_social_links($post->ID, 'blog-blog-grid-item-socialcontainer-link', 'blog-blog-grid-item-socialcontainer-link-icon'); ?></div>
					<div class="blog-blog-grid-item-textcontainer-description"><?php echo $post->post_content; ?></div>
				</div>
			</div>
			<?php endwhile; ?>
			<div class="blog-blog-pagination">
				<?php 
				global $wp_query;
				$original_global = $wp_query;
				$wp_query = null;
				$wp_query = $the_query;

				echo paginate_links(array(
					'prev_text' => '<< Prev',
				));

				wp_reset_postdata();

				$wp_query = $original_global;

				?>
			</div>
		</div>
		<?php get_template_part('partials/navigation/blog', 'sidebar'); ?>
		
		<?php endif; ?>
	</section>
	<?php get_template_part('partials/global', 'contact');?>
</main>

<?php get_footer(); ?>