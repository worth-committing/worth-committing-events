jQuery(document).ready(function ($) {


  /** WHEN THE ADD ROW BUTTON IS CLICKED... **/
  $("#add_new_event_link").click(function () {
    /** set the ajax action we are calling, and the row we are currently on in the form. **/
    const data = {
      'action': 'add_event_link',
      'current_row': $(this).data('current_row'),
      'is_admin': 'frontend'
    };

    /** Set Row Numbers. **/
    const add_row_num = $(this).data('current_row') + 1;
    const del_row_num = $(this).data('current_row');

    /** Set remove link element.  */
    let remove_link_button = $("#remove_link");

    /** Log the information going to the ajax call & the row numbers. **/
    // console.log(data);
    // console.log("ADD ROW NUMBER : " + add_row_num);
    // console.log("DEL ROW NUMBER : " + del_row_num);

    $.post(ajaxurl, data, function (response) {
      /** Log the html we received back. **/
      // console.log('Got this from the server: ' + response);

      /** Uplink the button data. **/
      remove_link_button.data("delete_row", del_row_num);
      $(this).data("current_row", add_row_num);

      /** Check to see if there is more than one row... **/
      if (add_row_num > 1) {
        // if there is, enable the remove row button.
        remove_link_button.prop("disabled", false)
      }

      /** Add the row to the end of the table. **/
      $("#link-input").append(response);
    }); // END AJAX CALL.
  }); // END CLICK #add_link.



  /** WHEN THE REMOVE ROW BUTTON IS CLICKED... **/
  $("#remove_link").click(function () {

    /** Get the row number we are deleting. **/
    const row_num = $(this).data('delete_row');

    /** Log the row we are removing **/
    // console.log("delete_row : " + row_num );

    /** Build the ID selector for the row we are deleting. **/
    const row_to_del = "#link_row_" + row_num;

    /** Log the ID selector **/
    // console.log("id selector : " + row_to_del );

    /** Remove the row. **/
    $(row_to_del).remove();

    /** Set the row numbers. **/
    const add_row_num = row_num;
    const del_row_num = row_num - 1;

    /** Log the row numbers. **/
    console.log("ADD LINK ROW NUMBER : " + add_row_num);
    console.log("DEL LINK ROW NUMBER : " + del_row_num);

    /** Uplink the row numbers. **/
    $(this).data("delete_row", del_row_num);
    $("#add_new_event_link").data("current_row", add_row_num);

    /** Check to see if there is more than one row... **/
    if (row_num <= 1) {
      // If there is not, disable the remove button.
      $(this).prop("disabled", true)
    } // END IF less than 2 rows.
  }); // END CLICK #remove_link.


}); // END DOCUMENT READY.