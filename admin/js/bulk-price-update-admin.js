(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   */
  $(function () {
    $("#cat").select2({
      closeOnSelect: false,
      placeholder: "Select a category",
	  allowClear: true
    });

    //$("#submit").click(function () {
    $("#bulk-price-update").submit(function (e) {
      e.preventDefault();
      var cat = $("#cat").val();
      var percent = $("#percentage").val();
      if (cat && cat.length > 0) {
        if (percent > 0) {
          var data = {
            action: "my_action",
            edit_bulk_product: plugin_data.edit_bulk_product,
            percentage: $("#percentage").val(),
            categories: cat,
            "round-off": $("#check-round-point:checked").val(),
            price_action: $("#price_change_type:checked").val(),
          };
          $.ajax({
            type: "post",
            url: plugin_data.ajax_url,
            data: data,
            beforeSend: function () {
              $("#loader").show();
            },
            success: function (response) {
              $("#update_product_results").show();
              $("#loader").hide();
              $("#update_product_results_body").empty();
              $("#update_product_results_body").append(response.data);
			  document.getElementById("bulk-price-update").reset();
			  $("#cat").val("").trigger('change');
			  $("#cat").trigger('change');
            },
          });
        } else {
          Swal.fire({
            icon: "warning",
            title: "",
            text: "Percentage Value should be greater than 0",
          });
        }
      } else {
        Swal.fire({
          icon: "warning",
          title: "",
          text: "Category should be selected",
        });
      }
    });
  });
  /*
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
})(jQuery);
