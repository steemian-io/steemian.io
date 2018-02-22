<?php
/**
 * @package WEN Associate
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

		<div class="entry-meta">
			<?php wen_associate_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
    <?php
    /**
     * wen_associate_single_image hook
     *
     * @hooked wen_associate_add_image_in_single_display -  10
     *
     */
    do_action( 'wen_associate_single_image' );
    ?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'wen-associate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php wen_associate_entry_footer(); ?>
	</footer><!-- .entry-footer -->

  <?php
  /**
   * wen_associate_author_bio hook
   *
   * @hooked wen_associate_add_author_bio_in_single -  10
   *
   */
  do_action( 'wen_associate_author_bio' );
  ?>

</article><!-- #post-## -->
