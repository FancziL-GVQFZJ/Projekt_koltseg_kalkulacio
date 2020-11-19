$(document).ready(function(){

 $('.stopprojekt').click(function(){
   var el = this;

   var projektid = $(this).data('id');

   var confirmalert = confirm("Biztosan be akarod fejezni ezt a projektet?");
   if (confirmalert == true) {
      $.ajax({
        url: 'includes/projektstop.inc.php',
        type: 'POST',
        data: { pid:projektid },
        success: function(response){

          if(response == 1){
              //$(el).closest('tr').css('background','green');
      	      alert('Kiléptél a projektből');
              window.location.reload();
          }else if(response == 0){
	            alert('Próbálja meg mégegyszer');
          }else{
	            alert('Sikertelen projektből való kilépés');
          }
        }
      });
   }
 });
});
