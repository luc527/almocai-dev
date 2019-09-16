document.addEventListener('DOMContentLoaded', function () {
  var elems = document.querySelectorAll('.sidenav');
  var instances = M.Sidenav.init(elems);
});

// Autocomplete intolerância/alergia alimentar
document.addEventListener('DOMContentLoaded', function () {
  var elems = document.querySelectorAll('.autocomplete');
  var instances = M.Autocomplete.init(elems, {
    data: {
      "Lactose": null,
      "Glútem": null,
      "Ovo": null,
      "Soja": null,
      "Milho": null,
      "Levedura": null,
      "Corantes amarelos": null
    }
  });
});