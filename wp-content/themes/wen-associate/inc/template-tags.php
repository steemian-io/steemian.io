<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WEN Associate
 */

if ( ! function_exists( 'wen_associate_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function wen_associate_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		'%s',
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		'%s',
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'wen_associate_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function wen_associate_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'wen-associate' ) );
		if ( $categories_list && wen_associate_categorized_blog() ) {
      printf( '<span class="cat-links"><i class="fa fa-sitemap"></i>%1$s</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'wen-associate' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><i class="fa fa-tag"></i>%1$s</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link"><i class="fa fa-comment"></i>';
		comments_popup_link( __( 'Leave a comment', 'wen-associate' ), __( '1 Comment', 'wen-associate' ), __( '% Comments', 'wen-associate' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'wen-associate' ), '<span class="edit-link">', '</span>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function wen_associate_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'wen_associate_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'wen_associate_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wen_associate_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wen_associate_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in wen_associate_categorized_blog.
 */
function wen_associate_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'wen_associate_categories' );
}
add_action( 'edit_category', 'wen_associate_category_transient_flusher' );
add_action( 'save_post',     'wen_associate_category_transient_flusher' );
