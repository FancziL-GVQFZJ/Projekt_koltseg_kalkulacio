$(document).ready(function(){

 $('.deleteprojekt').click(function(){
   var el = this;

   var projektid = $(this).data('id');

   var confirmalert = confirm("Biztosan törölni akarod ezt a projektet?");
   if (confirmalert == true) {
      $.ajax({
        url: 'includes/databasedel/projektdelete.inc.php',
        type: 'POST',
        data: { pid:projektid },
        success: function(response){

          if(response == 1){
              //$(el).closest('tr').css('background','green');
      	      alert('A projekt törlődött');
              window.location.reload();
          }else if(response == 0){
	            alert('Próbálja meg mégegyszer');
          }else{
	            alert('Sikertelen projekt törlés');
          }
        }
      });
   }
 });
});
