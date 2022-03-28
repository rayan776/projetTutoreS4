<?php
    if(!defined('CONSTANTE'))
        die("Accès interdit");
?>

<div style="margin-top:30px" class="content">
    <p style="padding-bottom:3px; border-bottom:1px solid darkgreen"> Réalisez votre bilan en remplissant le formulaire ci-dessous </p>

    <form id="formBilan" onsubmit="submitFormBilan(event);" action="index.php?module=ModBilan&action=calculerBilan&pdf=no" method="post">

        <div style="margin-left:30px; width:15%" class="row formbox">
            <label for="age" class="col-sm-2 col-form-label">Age </label>
            <div style="display:flex" class="col-sm-10">
                <input style="width:60px; position:relative; left:50px" type="text" name="age" class="form-control" id="age" onkeyup="verifAge();">
                <div style="position:relative; left:60px; top:5px"> années </div>
            </div>
            <p style="padding-left:15px; color:red" id="errorAge"></p>
        </div>

        <div style="position:relative; left:30px; top:20px"> Sexe </div>

        <div style="width:15%; margin-top:30px; margin-left:30px;" class="row formbox">
          <input type="radio" name="sexe" value="male" checked>
            <label style="position:relative; top:3px; left:5px">
                 Homme
            </label>
            
            <input style="margin-left:20px" type="radio" name="sexe" value="female">
            <label style="position:relative; top:3px; left:5px">
                Femme 
            </label>
            
        </div>

        


        <h4 class="h4borderbottom" style="margin-top:50px; margin-left:30px"> Insérez des aliments </h4>
       

        <div id="suggestionsAliments">

        </div>

        <div style='margin-left:30px'>

            <label for="nomAliment"> Aliment </label>
            <input style="width:500px" type="text" onkeyup="chercherAliment(this.value);" class="form-control" id="nomAliment"/>

            <label for="jourAliment"> Jour </label>
            <select style="margin-top:30px" id="jourAliment">
                <option value="lundi"> Lundi </option>
                <option value="mardi"> Mardi </option>
                <option value="mercredi"> Mercredi</option>
                <option value="jeudi"> Jeudi </option>
                <option value="vendredi"> Vendredi </option>
                <option value="samedi"> Samedi </option>
                <option value="dimanche"> Dimanche </option>
            </select>

            <label style="position:relative; left:30px" for="momentAliment"> Moment </label>
            <select style="margin-left:30px" id="momentAliment">
                <option value="Matin"> Matin </option>
                <option value="Midi"> Midi </option>
                <option value="Soir"> Soir </option>
            </select>

            <label style="position:relative; left:30px" for="quantiteAliment"> Quantité </label>
            <select style="margin-left:30px" id="quantiteAliment">
                <option value="Peu"> Peu </option>
                <option value="Moyen"> Moyen </option>
                <option value="Beaucoup"> Beaucoup </option>
            </select>

            <button type="button" class="btn btn-success" onclick="ajouterAliment();"> Ajouter </button>


            <div style="margin-top:80px">
                <input type="submit" class="btn btn-success" value="Calculer mon bilan"/>

                <input type="submit" style="margin-left:50px" formaction="index.php?module=ModBilan&action=calculerBilan&pdf=yes" class="btn btn-success" value="Générer mon bilan au format PDF"/>

                <button onclick="resetFormBilan();" style="margin-left:30px; margin-top:10px; width:158px" type="button" class="btn btn-dark"> Reset </button>
            </div>

            <table id="tableaubilan" style="margin-top:50px; margin-left:30px">
            <thead>
                <tr>
                <th></th>
                <th>Lundi</th>
                <th>Mardi</th>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
                <th>Samedi</th>
                <th>Dimanche</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Matin</th>
                    <td id="lundiMatin"></td>
                    <td id="mardiMatin"></td>
                    <td id="mercrediMatin"></td>
                    <td id="jeudiMatin"></td>
                    <td id="vendrediMatin"></td>
                    <td id="samediMatin"></td>
                    <td id="dimancheMatin"></td>
                </tr>
                <tr>
                    <th>Midi</th>
                    <td id="lundiMidi"></td>
                    <td id="mardiMidi"></td>
                    <td id="mercrediMidi"></td>
                    <td id="jeudiMidi"></td>
                    <td id="vendrediMidi"></td>
                    <td id="samediMidi"></td>
                    <td id="dimancheMidi"></td>
                </tr>
                <tr>
                    <th>Soir</th>
                    <td id="lundiSoir"></td>
                    <td id="mardiSoir"></td>
                    <td id="mercrediSoir"></td>
                    <td id="jeudiSoir"></td>
                    <td id="vendrediSoir"></td>
                    <td id="samediSoir"></td>
                    <td id="dimancheSoir"></td>
                </tr>
            </tbody>
        </table>
        </div>


        <?php
            foreach ($_SESSION["jours"] as $jour) {
                foreach ($_SESSION["moments"] as $moment) {
                    foreach ($_SESSION[$jour][$moment] as $class => $aliment) {
                        $stringAliment = explode("/", $aliment);
                        

                        $nomAliment=$stringAliment[0];
                        $quantite=$stringAliment[1];

                        echo 
                            "<script>
                                
                                \$newDiv = \$(\"<div class='aliment $class'> <div> $nomAliment : $quantite </div> <button class='btn btn-danger' type='button' onclick=deleteAliment('$class');> Supprimer </button> </div>\");

                                var hiddenInput = document.createElement('input');
                                hiddenInput.setAttribute('class', '$class');
                                hiddenInput.setAttribute('type','hidden');
                                hiddenInput.setAttribute('name','$jour" . "[" . $moment . "][]');
                                hiddenInput.setAttribute('value','$aliment');

                                \$('#formBilan').append(hiddenInput);
                                \$('#$jour$moment').append(\$newDiv);
                        
                            </script>";

                    }
                }
            }
        ?>


    </form>

    
</div>