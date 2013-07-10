function addabo(div)
{
	display = document.getElementById(div).style.display;
	if(display == 'none')
	{
		document.getElementById(div).style.display = 'block';
	}
	else
	{
		document.getElementById(div).style.display ='none';
	}
}

function dateSearch(number)
{
	if(number == '4')
	{
		document.getElementById("appendedInputButton").style.display ='none';
		document.getElementById("datesearch").style.display = 'inline-block';

	}
	else
	{
		if(document.getElementById("datesearch").style.display == 'inline-block')
		{
		document.getElementById("datesearch").style.display ='none';
		document.getElementById("appendedInputButton").style.display = 'inline-block';
		}
	}
}

function suppfilm(idfilm)
{
	document.getElementById(idfilm).style.visibility ='visible';
}

function suppfilm2(idfilm)
{
	document.getElementById(idfilm).style.visibility ='hidden';
}

function editFilm(name)
{
	document.getElementById("editfilm").style.display = 'block';
	document.getElementById("editfilm").value = name;
}

function avis(div,div2){
		document.getElementById(div).style.display = 'block';
			document.getElementById(div2).style.display = 'none';
			document.getElementById('add-avis').focus();
}


// Cacher la div si on clique en dehors - Popup tweet
     $(document).mouseup(function (e){
         var container = $("#bloc-em");
         var container2 = $("#bloc-text");
       if (container.has(e.target).length === 0 && $("#bloc-text").css("display") != "none"){
          $("#bloc-text").css("display","none");
        $("#avis-em").css("display","block");
      }
     });

function avisBloc(block,none)
{
	document.getElementById(none).style.display = 'none';
	document.getElementById(block).style.display = 'block';
	document.getElementById(block + "-btn").className = "btn btn-primary";
	document.getElementById(none + "-btn").className = "btn ";
}