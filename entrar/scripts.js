document.addEventListener('DOMContentLoaded', function () {
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);
  {erro_trigger}
});

function erroLogin() {
  var texto = 'Usuário ou senha incorreto, por favor, tente novamente.';
  const elem = document.getElementById('modal-erro');
  const instance = M.Modal.init(elem);
  var textId = document.getElementById('texto-erro');
  textId.innerHTML = texto;
  instance.open();
}

