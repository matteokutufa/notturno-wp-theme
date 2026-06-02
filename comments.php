<?php
/**
 * comments.php — commenti in stile editoriale.
 * @package notturno
 */
if ( post_password_required() ) return;
?>
<section class="comments">
	<?php if ( have_comments() ) : ?>
		<div class="eyebrow" style="margin-bottom:24px;">
			<?php printf( esc_html( _n( '%s note', '%s notes', get_comments_number(), 'notturno' ) ), esc_html( number_format_i18n( get_comments_number() ) ) ); ?>
		</div>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'avatar_size' => 0,
				'callback'    => 'notturno_comment',
			) );
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'title_reply'         => __( 'Lasciatemi una nota', 'notturno' ),
		'class_form'          => 'comment-form',
		'label_submit'        => __( 'Envoyer', 'notturno' ),
	) );
	?>
</section>
<?php
/** Render singolo commento. */
function notturno_comment( $comment, $args, $depth ) {
	?>
	<li <?php comment_class( 'comment-body' ); ?> id="comment-<?php comment_ID(); ?>">
		<div style="display:flex; gap:14px; align-items:baseline; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap;">
			<span class="serif-italic" style="font-size:20px; color:var(--fg-0);"><?php comment_author(); ?></span>
			<span class="entry-num"><?php echo esc_html( get_comment_date( 'j M Y' ) ); ?></span>
		</div>
		<div style="color:var(--fg-1); line-height:1.75; font-size:15px; max-width:760px;"><?php comment_text(); ?></div>
		<div style="margin-top:14px;"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
	</li>
	<?php
}
