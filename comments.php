<?php
/**
 * comments.php — commenti in stile editoriale.
 * (notturno_comment è definita in functions.php: i template possono essere
 *  inclusi più volte e una function qui causerebbe un fatal "cannot redeclare")
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
