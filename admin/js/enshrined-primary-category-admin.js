(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(window).load(function () {

        /**
         * Fire our update event to update the category select
         */
        $('.categorydiv input[type="checkbox"]').on('change', function (event) {

            var name = $(this).parent('label').text().trim();
            var val = $(this).val();
            var checked = $(this).is(':checked');

            if (!(val in enshrined_primary_categories) && checked === true) {
                enshrined_primary_categories[val] = {
                    id: val,
                    name: name
                };
            } else if ((val in enshrined_primary_categories) && checked === false) {
                delete enshrined_primary_categories[val];
            }

            enshrined_populate_primary_categories_meta_box(enshrined_primary_categories);
        });
    });

    /**
     * Update the select box to contain our selected categories
     *
     * @param enshrined_primary_categories
     */
    function enshrined_populate_primary_categories_meta_box(enshrined_primary_categories) {
        var select = $('.enshrined_primary_category_select');
        var selected = select.val();
        
        // Clear the select
        select.html('');

        $.each(enshrined_primary_categories, function (id, cat, wut) {
            var option = $('<option class="option_' + cat.id + '" value="' + cat.id + '">' + cat.name + '</option>');

            if (cat.id == selected) {
                option.attr('selected', 'selected');
            }

            select.append(option);
        });
    }

})(jQuery);
