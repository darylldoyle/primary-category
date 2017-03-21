<?php

/**
 * The shortcode output for the plugin
 *
 *
 * This template can be overriden by copying this file to your-theme/primary-category/shortcode.php
 *
 * @link       https://enshrined.co.uk
 * @since      1.0.0
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/public/templates
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	// Post thumbnail.
	the_post_thumbnail();
	?>

    <header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		endif;
		?>
    </header><!-- .entry-header -->

    <div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(
			__( 'Continue reading %s', 'enshrined-primary-category' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'enshrined-primary-category' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'enshrined-primary-category' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
    </div><!-- .entry-content -->

	<?php
	// Author bio.
	if ( is_single() && get_the_author_meta( 'description' ) ) :
		get_template_part( 'author-bio' );
	endif;
	?>

    <footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'enshrined-primary-category' ), '<span class="edit-link">', '</span>' ); ?>
    </footer><!-- .entry-footer -->

</article><!-- #post-## -->