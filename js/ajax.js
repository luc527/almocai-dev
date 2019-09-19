window.onload = function(){
  document.getElementById('matricula').addEventListener('keyup', function(){
      ValidaMatricula();
  });
}

function ValidaMatricula(){
  ajax = new XMLHttpRequest();
    ajax.onreadystagechange = function(){
    if(ajax.status == 200){
      if(ajax.responseText == 1){
        document.getElementById('matricula_info').innerHTML = "Matricula já cadastrada";
      }else{
        document.getElementById('matricula_info').innerHTML = "Matrícula disponível";
      }
    }
  }
  acao = document.getElementById('acao').value;
  matricula = document.getElementById('matricula').value;
  ajax.open('GET', 'usuario_acao.php?acao='+acao+'&matricula='+matricula, true);
  ajax.send();

}
