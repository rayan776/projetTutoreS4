function chercherAliment(nomAliment) {
    if (nomAliment.trim()!=='') {
        $.ajax({
            url: 'models/ajaxChercherAliment.php',
            type: 'get',
            data: {nomAliment:nomAliment},
            success: function(response){

                $("#suggestionsAliments").css('opacity', '100%');

                if (response.trim()==='') {
                    $("#suggestionsAliments").html("<div> Pas de suggestions. </div>");
                }
                else {
                    $("#suggestionsAliments").html(response);
                }

            }
        });
    }
    else {
        $("#suggestionsAliments").css('opacity', '0%');
        $("#suggestionsAliments").html('');
    }
}

function changeTextInput(inputName, str) {
    
    $("#"+inputName).val(str);
}

function getRandomInt(max) {
	return Math.floor(Math.random()*max);
}

function deleteAliment(classToDel) {

	$("." + classToDel).remove();
    
    $.ajax({
        url: 'models/ajaxDeleteAliment.php',
        type: 'get',
        data: {classToDel:classToDel},
        success: function(response){

        }
    });
}

function ajouterAliment() {
    var jour = $("#jourAliment").val();
    var moment = $("#momentAliment").val();
    var aliment = $("#nomAliment").val().trim();
    var numero = getRandomInt(2500000);

    	if(aliment!=='') {
	    var quantite = $("#quantiteAliment").val();

	    $newDiv = $("<div class='aliment aliment" + numero + "'> " + "<div>" + aliment + " : " + quantite + " </div> <button class='btn btn-danger' type='button' onclick='deleteAliment(\"aliment"+numero+"\")';> Supprimer </button> </div>");

	    var hiddenInput = document.createElement("input");
	    hiddenInput.setAttribute("class", "aliment"+numero);
	    hiddenInput.setAttribute("type","hidden");
	    hiddenInput.setAttribute("name",jour + "[" + moment + "][]");
	    hiddenInput.setAttribute("value",aliment+"/"+quantite);

        $.ajax({
            url: 'models/ajaxChercherAliment.php',
            type: 'get',
            data: {nomAliment:aliment, exact:'true', jour:jour, moment:moment, quantite:quantite, numero:numero},
            success: function(response){

                if (response.trim()==='') {
                    $("#nomAliment").css('border','1px solid red'); 
                    $("#nomAliment").val("Cet aliment n'existe pas dans notre base de données.");
                }
                else {
                    $("#nomAliment").css('border','none');
                    $("#formBilan").append(hiddenInput);
                    $("#" + jour + moment).append($newDiv);
                }

            }
        });

	
	}

}

function verifAge() {
    var age = $("#age").val().trim();

    if (age==='') {
        $("#errorAge").html("Veuillez saisir votre âge.");
        $("#age").css("border","none");
        return false;
    }
    else {
        let nbage = Number(age);

        if (!Number.isInteger(nbage) || nbage <= 0) {
            $("#errorAge").html("Age invalide.");
            $("#age").css("border","1px solid red");
            return false;
        }
        else {
            $("#errorAge").html("");
            $("#age").css("border","none");
            return true;
        }
       
    }
   
}

function submitFormBilan(event) {
    if (verifAge()) {
        $("#formBilan").submit();
    }
    else {
        event.preventDefault();
    }
}

function resetFormBilan() {

    $.ajax({
        url: 'models/ajaxDeleteAliment.php',
        type: 'get',
        data: {reset:1},
        dataType:"JSON",
        success: function(response){
            for (let x=0; x<response.length; x++) {
                $("."+response[x]).remove();
            }
        }
    });

}