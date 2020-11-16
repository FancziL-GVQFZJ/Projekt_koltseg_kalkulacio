function myFunction1() {
  var input, filter, table, tr, td0, td1, td2, td3, i, txtValue0, txtValue1, txtValue2, txtValue3;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("AnyaglistaTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td0 = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    td3 = tr[i].getElementsByTagName("td")[3];
    if (td0 || td1 || td2 || td3) {
      txtValue0 = td0.textContent || td0.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      txtValue3 = td3.textContent || td3.innerText;
      if (txtValue0.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }
      else if (txtValue1.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }
      else if (txtValue2.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }
      else if (txtValue3.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }
      else {
        tr[i].style.display = "none";
      }
    }
  }
}
