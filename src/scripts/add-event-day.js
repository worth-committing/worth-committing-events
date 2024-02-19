jQuery(document).ready(function($) {
  /** WHEN THE ADD ROW BUTTON IS CLICKED... **/
  $("#add_date").click(function () {
    /** set the ajax action we are calling, and the row we are currently on in the form. **/
    const data = {
      'action':'add_event_day',
      'current_row': $(this).data('current_row'),
      'is_admin': 'frontend'
    };

    /** Set Row Numbers. **/
    const add_row_num = $("#add_date").data('current_row') + 1;
    const del_row_num = $("#add_date").data('current_row');

    /** Log the information going to the ajax call & the row numbers. **/
    console.log( data );
    console.log( "ADD ROW NUMBER : " + add_row_num );
    console.log( "DEL ROW NUMBER : " + del_row_num );

    jQuery.post(ajaxurl, data, function(response) {
      /** Log the html we received back. **/
      // console.log('Got this from the server: ' + response);

      /** Update the button data. **/
      $("#remove_date").data("delete_row", del_row_num );
      $("#add_date").data("current_row", add_row_num );

      /** Check to see if there is more than one row... **/
      if( add_row_num > 1 ) {
        // if there is, enable the remove row button.
        $("#remove_date").prop( "disabled", false )
      }

      /** Add the row to the end of the table. **/
      $(".day-time-input").append( response );
    }); // END AJAX CALL.
  }); // END CLICK #add_date.

  /** WHEN THE REMOVE ROW BUTTON IS CLICKED... **/
  $("#remove_date").click(function () {

    /** Get the row number we are deleting. **/
    const row_num = $("#remove_date").data('delete_row');

    /** Log the row we are removing **/
    // console.log("delete_row : " + row_num );

    /** Build the ID selector for the row we are deleting. **/
    const row_to_del = "#date_row_" + row_num;

    /** Log the ID selector **/
    // console.log("id selector : " + row_to_del );

    /** Remove the row. **/
    $(row_to_del).remove();

    /** Set the row numbers. **/
    const add_row_num = row_num;
    const del_row_num = row_num - 1;

    /** Log the row numbers. **/
    console.log( "ADD ROW NUMBER : " + add_row_num );
    console.log( "DEL ROW NUMBER : " + del_row_num );

    /** Update the row numbers. **/
    $("#remove_date").data("delete_row", del_row_num );
    $("#add_date").data("current_row", add_row_num );

    /** Check to see if there is more than one row... **/
    if( row_num <= 1 ) {
      // If there is not, disable the remove button.
      $("#remove_date").prop( "disabled", true )
    } // END IF less than 2 rows.
  }); // END CLICK #remove_date.


}); // END DOCUMENT READY.