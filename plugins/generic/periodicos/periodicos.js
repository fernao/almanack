// 
// arquivo com biblitecas js usadas

var pilha = new Array();

function toggleArtigos(id) {
  // TODO: 2.3.2-1 com jQuery
    
  //  el.style.background = "red";
  
  divs = document.getElementById('artigos').getElementsByTagName("div");
  
  for (d = 0; d < divs.length; d++) {

      patternId = /artigo\_(.*)$/;
      
      id_match = new String(divs[d].id);
      id_match = id_match.match(patternId, '');
      
      if (divs[d].id != id) {
          // div escondida
	  el = document.getElementById(divs[d].id);
	  el.style.display = "none";	  
      }
      
      // link
      elLink = document.getElementById("link_" + id_match[1]);
      elLink.style.fontWeight = "normal";
      elLink.style.fontStyle = "normal";
  }
  
  el = document.getElementById("artigo_" + id);
  el.style.display = "inline";
  
  // link
  elLink = document.getElementById("link_" + id);
  elLink.style.fontWeight = "bold";
  elLink.style.fontStyle = "italic";
  
}


function getPilha() {
    var pilha = new Array();
    return pilha;
}