$(document).ready(function(){

 $('.deletemdij').click(function(){
   var el = this;

   var deleteid = $(this).data('id');

   var confirmalert = confirm("Biztosan törlöd az elemet?");
   if (confirmalert == true) {
      $.ajax({
        url: 'includes/databasedel/removemdij.inc.php',
        type: 'POST',
        data: { id:deleteid },
        success: function(response){

          if(response == 1){
      	    $(el).closest('tr').css('background','tomato');
      	    $(el).closest('tr').fadeOut(800,function(){
      	    $(this).remove();
            window.location.reload();
      	    });
          }else if(response == 0){
	            alert('Nem megfelelő id.');
          }else if(response == 2){
	            alert('Nem megfelelő id.');
          }else{
	            alert('Sikertelen törlés.');
          }
        }
      });
   }
 });
});
