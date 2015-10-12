(function ($) {


    function initialize_field($el) {

        var autocomplete = jQuery("input.search-autocomplete");
        var action = 'autocomplete_suggestions';
        var route = window.ajaxurl + '?callback=?&action=' + action;
        var minLength = 3;

        function split(val) {
            return val.split(/—\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }
        function unique(list) {
            var result = [];
            $.each(list, function (i, e) {
                if ($.inArray(e, result) == -1)
                    result.push(e);
            });
            return result;
        }

        function removeItem(array, item) {
            for (var i in array) {
                if (array[i] == item) {
                    array.splice(i, 1);
                }
            }
            return array;
        }

        function duplicate(array, item) {
            for (var i in array) {
                if (array[i] == item) {
                    return true;
                }
            }
            return false;
        }

        function log(message, code) {
            var new_elem = "<h3 class='ui-item-list'> <span class='ui-accordion-header-icon ui-icon ui-icon-triangle-1-e'></span><span class='ui-item-list-content'>" + message + "</span><a class='acf-button-delete ir' href='#' data-item-id='" + code + "' >Remove</a></h3>";
            $(new_elem).appendTo("div.field-items", $el);
        }

        console.log("//_> Inputs");
        autocomplete.each(function () {
            var post_type = $(this).attr("data-post_type");
            $(this).autocomplete({
                source: function (request, response) {
                    $.getJSON(route, {
                        term: request.term,
                        post_type: post_type
                    }, response);
                },
                minLength: minLength,
                select: function (event, ui) {

                    var data_input_id = "input#" + $(this).attr("data-input_id");
                    var input_hidden = jQuery(data_input_id);
                    var container = input_hidden.closest(".field_type-autocomplete");
                    var container_id = container.attr("id");
                    var emptyListMsg = jQuery("p.empty-list-msg", container);
                    var list = jQuery(" div.field-items", container);

                    var input_hidden_val = input_hidden.val();
                    var values = split(input_hidden.val());

                    if (!duplicate(values, ui.item.ID)) {
                        var message = ui.item.label;
                        var code = ui.item.ID;
                        /* Log */
                        var new_elem = "<h3 class='ui-item-list'> <span class='ui-accordion-header-icon ui-icon ui-icon-triangle-1-e'></span><span class='ui-item-list-content'>" + message + "</span><a class='acf-button-delete ir' href='#' data-item-id='" + code + "' >Remove</a></h3>";
                        $(new_elem).appendTo("#" + container_id + " div.field-items");
                        /* End Log */
                        values.push(ui.item.ID);
                        var unique_values = values;
                        if (input_hidden_val.length === 0) {
                            input_hidden.val(unique_values.join(""));
                        } else {
                            input_hidden.val(unique_values.join("— "));
                        }
                        emptyListMsg.css("display", "none");
                    }

                    this.value = "";
                    return false;

                }
            });
        });

        jQuery("a.acf-button-delete", $el).click(function (event) {

            var data_input_id = "input#" + $(this).attr("data-input_id");
            var input_hidden = jQuery(data_input_id);
            var id = $(this).attr("data-item-id");
            var input_hidden_val = input_hidden.val();

            var values = split(input_hidden_val);
            var newvalues = removeItem(values, id);

            input_hidden.val(values.join("— "));
            $(this).closest("h3").remove();
            console.log("-> Values: " + newvalues.toString());
            event.preventDefault();
        });
    }

    if (typeof acf.add_action !== 'undefined') {
        /*
         *  ready append (ACF5)
         *
         *  These are 2 events which are fired during the page load
         *  ready = on page load similar to $(document).ready()
         *  append = on new DOM elements appended via repeater field
         *
         *  @type	event
         *  @date	20/07/13
         *
         *  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
         *  @return	n/a
         */
        acf.add_action('ready append', function ($el) {
            // search $el for fields of type 'autocomplete'
            acf.get_fields({type: 'autocomplete'}, $el).each(function () {
                initialize_field($(this));
            });
        });
    } else {
        /*
         *  acf/setup_fields (ACF4)
         *
         *  This event is triggered when ACF adds any new elements to the DOM. 
         *
         *  @type	function
         *  @since	1.0.0
         *  @date	01/01/12
         *
         *  @param	event		e: an event object. This can be ignored
         *  @param	Element		postbox: An element which contains the new HTML
         *
         *  @return	n/a
         */
        $(document).on('acf/setup_fields', function (e, postbox) {
            $(postbox).find('.field[data-field_type="autocomplete"]').each(function () {
                initialize_field($(this));
            });
        });
    }
})(jQuery);
