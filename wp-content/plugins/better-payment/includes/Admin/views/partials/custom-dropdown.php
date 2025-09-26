<?php
$inputFieldName = !empty($inputFieldName) ? $inputFieldName : 'status'; //demo_name
$inputFieldLabel = !empty($inputFieldLabel) ? $inputFieldLabel : 'Status'; //Demo Name
$dropdownDefaultValue = !empty($dropdownDefaultValue) ? $dropdownDefaultValue : ''; //demo-name
$dropdownItems = !empty($dropdownItems) ?   $dropdownItems :
    array(
        //'value' => 'Label',
        'all' => 'All',
        'active' => 'Active',
        'inactive' => 'Inactive',
    );

?>
<div class="bp-select-custom-button-wrap">
    <button class="button fix-style fix-common bp-select-custom-button" data-target=".<?php esc_attr_e($inputFieldName, 'better-payment'); ?>-dropdown">
        <span class="mr-2 <?php esc_attr_e($inputFieldName, 'better-payment'); ?>-button-text"><?php esc_html_e($inputFieldLabel, 'better-payment'); ?></span>
        <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.99896 0L4.63636 3.41372L1.27377 0L0 1.29314L4.63636 6L9.27273 1.29314L7.99896 0Z" fill="#9095A2" />
        </svg>
    </button>

    <div class="box bp-select-custom-button-dropdown is-hidden <?php esc_attr_e($inputFieldName, 'better-payment'); ?>-dropdown" <?php if(!empty($dropdownDefaultValue)): ?>data-defaultvalue='<?php esc_attr_e($dropdownDefaultValue, 'better-payment') ?>' <?php endif; ?> >
        <?php if (count($dropdownItems)) : ?>
            <?php foreach ($dropdownItems as $inputValue => $inputLabel) : ?>
                <p class="mb-3">
                    <label class="checkbox">
                            <input class="<?php esc_attr_e($inputFieldName, 'better-payment'); ?>-<?php esc_attr_e($inputValue, 'better-payment'); ?>" <?php if($dropdownDefaultValue === $inputValue) : ?> checked <?php endif; ?> type="checkbox" name="<?php esc_attr_e($inputFieldName, 'better-payment'); ?>[]" value="<?php esc_attr_e($inputValue, 'better-payment'); ?>">
                            <?php esc_html_e($inputLabel, 'better-payment'); ?>
                    </label>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>