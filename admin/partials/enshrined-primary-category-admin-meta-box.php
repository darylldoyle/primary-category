<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://enshrined.co.uk
 * @since      1.0.0
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/admin/partials
 */
?>

<p>
    <select name="enshrined_primary_category" class="enshrined_primary_category_select">
		<?php foreach ( $enshrined_chosen_categories as $chosen_category ): ?>
            <option class="option_<?php echo $chosen_category['id'] ?>" value="<?php echo $chosen_category['id'] ?>">
				<?php echo $chosen_category['name'] ?>
            </option>
		<?php endforeach; ?>
    </select>
</p>
<p class="howto" id="new-tag-post_tag-desc">
	<?php echo __( 'Choose the primary category', 'enshrined-primary-category' ) ?>
</p>