<?php
/**
 * Render job list for shortcode
 *
 * @since 1.0.0
 */
// echo Easyjobs_Helper::generate_block_style($atts);
?>
<div class="ej-job-body easyjobs-blocks easyjobs-blocks-job-list">
    <div class="easyjobs-shortcode-wrapper ej-template-default" id="easyjobs-list">
        <div class="ej-section">
            <?php printf( "<div class='elej-error-msg'>%s</div>", "Whoops! It seems you didn't connect the EasyJobs Account. You can easily connect your account from <b>WordPress Dashboard > EasyJobs > Get Started > Sign In / Connect via API</b>" ); ?>
        </div>
    </div>
</div>