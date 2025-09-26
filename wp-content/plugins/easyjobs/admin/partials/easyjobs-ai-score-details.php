<?php if ( ! empty( $scores ) ) : ?>
<div class="ai-score-details">
	<ul>
		<?php foreach ( $scores as $name => $score ) : ?>
			<?php if ( $score !== null ) : ?>
				<li>
					<?php echo esc_html( ucfirst( str_replace( '_', ' ', $name ) ) ); ?>: <strong style="color: <?php echo esc_html( Easyjobs_Helper::get_ai_score_color( $name ) ); ?>"><?php echo esc_html( $score ); ?>%</strong>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
