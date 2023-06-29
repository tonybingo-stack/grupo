   window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementsByClassName('table');

    for (var i = datatablesSimple.length - 1; i >= 0; i--) {
       
        new simpleDatatables.DataTable( datatablesSimple[i],{
             sortable:true,
        } );
    }

});

   window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


$( "title" ).text("Grupo Chat Extension's Admin Panel");



$( "body" ).on( "click", ".edit_credits", function() {
 $('#add_package input').val('');

   var id = $(this).attr('item_id');
    $.each(data1, function( index, value ) {
     if (value.id ==id) {
      $('#add_package input[name="package_id"]').val(id);
      $('#add_package input[name="package_name"]').val(value.name);
      $('#add_package input[name="credit"]').val(value.credits);
      $('#add_package input[name="price"]').val(value.price);
      $('#add_package input[name="app_in_id"]').val(value.app_in_id);
      
     }
   });
   add_form.show();
});



$( "body" ).on( "click", ".edit_subs", function() {
 $('#add_package input').val('');

   var id = $(this).attr('item_id');
    $.each(data1, function( index, value ) {
     if (value.id ==id) {
      $('#add_package input[name="package_id"]').val(id);
      $('#add_package input[name="package_name"]').val(value.name);
      $('#add_package input[name="mountly"]').val(value.monthly_fee);
      $('#add_package input[name="annual"]').val(value.annual_fee);
      $('#add_package input[name="gift"]').val(value.free_credit);
      $('#add_package input[name="app_in_id"]').val(value.app_in_id);
      
     }
   });
   add_form.show();
});








function add_credits_package() {
   $('#add_package input').val('');
   add_form.show();
}

function add_subs_package() {
   $('#add_package input').val('');
   add_form.show();
}
