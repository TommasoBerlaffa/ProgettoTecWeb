function AdminSearch() {

  var valoreDaCercare, valoreDaCercareFiltered, lista, tr, td, i, txtValue;
  // Input dell'Admin 
  valoreDaCercare = document.getElementById("search");
  // Rendo l'Input tutto Uppercase
  valoreDaCercareFiltered = valoreDaCercare.value.toUpperCase();
  // Prendo la lista degli utenti/lavori/offerte
  lista = document.getElementById("AdminTable");
  // Dalla lista prendo le righe
  tr = lista.getElementsByTagName("tr");

  // Ciclo le righe
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      // Se matcha mostra, sennò nasconde mettendo display.none
      // In questo modo se cambio la ricerca ricompaiono anche se prima erano nascosti
      if (txtValue.toUpperCase().indexOf(valoreDaCercareFiltered) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}