jQuery(document).ready( function() {
   jQuery(".university").click( function(e) {
      e.preventDefault(); 
      university_id = jQuery(this).attr("university-id");
      university_id = jQuery(this).attr("university-data-array");
      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "create_custom_dom", university_id : university_id},
         success: function(response) {
         	console.log("response:",response);
            if(response.type == "success") {
               jQuery("#main-content").html(response);
            }
            else {
               alert("Some error occured");
            }
         }
      });
   });
});