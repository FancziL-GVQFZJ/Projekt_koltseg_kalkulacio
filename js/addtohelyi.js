$(document).ready(function(){

 $('.addtohelyi').click(function(){
   var el = this;

   var addcartid = $(this).data('id');

   var confirmalert = confirm("Biztosan be akarok helyezni a listába?",addcartid);
   if (confirmalert == true) {
      $.ajax({
        url: 'includes/databaseinsert/insertha.inc.php',
        type: 'POST',
        data: { id:addcartid },
        success: function(response){

          if(response == 1){
              $(el).closest('tr').css('background','green');
      	      alert('Sikeres felvétel az adatbázisba');

          }else if(response == 0){
	            alert('Az ID nem stimmel');
          }else if(response == 2){
	            alert('Már bennevan a helyi adatbázisban ez az alkatrész');
          }else if(response == 3){
	            alert('Kezdjen egy projektet ehez a tevékenységhez');
          }else{
	            alert('Sikertelen felvétel a helyi anyaglistába');
          }
        }
      });
   }
 });
});
