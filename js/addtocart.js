$(document).ready(function(){

 $('.addtocart').click(function(){
   var el = this;

   var addcartid = $(this).data('id');

   var confirmalert = confirm("Biztosan be akarok helyezni a listába?",addcartid);
   if (confirmalert == true) {
      $.ajax({
        url: 'includes/insertpa.inc.php',
        type: 'POST',
        data: { id:addcartid },
        success: function(response){

          if(response == 1){
              $(el).closest('tr').css('background','green');
      	      alert('Sikeres felvétel az adatbázisba');

          }else if(response == 0){
	            alert('Az ID nem stimmel');
          }else if(response == 2){
	            alert('Már bennevan a kosárban ez a termék');
          }else if(response == 3){
	            alert('Kezdjen egy projektet ehez a tevékenységhez');
          }else{
	            alert('Sikertelen behelyezés a listába');
          }
        }
      });
   }
 });
});
