<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
/**
 * Vue État de Frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<h2> Valider la fiche de frais</h2>
<div class="row">
    <div class="col-md-4">
        <label for="lstVisiteurs" accesskey="n">Sélectionner un visiteur :</label>
        <div class="form-group">
            <label for="lstVisiteurs" accesskey="n"></label>
            <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                <?php
                foreach ($lesVisiteurs as $unVisiteur) {
                    $id = $unVisiteur['id'];
                    $nom = $unVisiteur['nom'];
                    $prenom = $unVisiteur['prenom'];
                    if ($id == $visiteurASelectionner) {
                        ?>
                        <option selected value="<?php echo $id ?>">
                            <?php echo $nom . ' ' . $prenom ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $id ?>">
                            <?php echo $nom . ' ' . $prenom ?> </option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>

    </div>
    <div class="col-md-4">
        <label for="lstMois" accesskey="n">Sélectionner un mois :</label>
        <div class="form-group">
            <label for="lstMois" accesskey="n"></label>
            <select id="lstMois" name="lstMois" class="form-control">
                <?php
                foreach ($lesMois as $unMois) {
                    $mois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $moisASelectionner) {
                        ?>
                        <option selected value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    }
                }
                ?>    

                ?>   

            </select>
        </div>

    </div>
</div>

<form method="post" action="index.php?uc=validerFrais&action=majForfait" role="form">
    <div class="row">    
        <div style="color: coral" class="panel-heading">
            <h3>Valider la fiche de frais</h3>
        </div> 
        <h4>Eléments forfaitisés</h4>
        <div class="col-md-4">
            <fieldset> 
            <input type="hidden" name="lstVisiteurs" value="<?php echo $visiteurASelectionner ?>">
            <input type="hidden" name="lstMois" value="<?php echo $moisASelectionner ?>">
     
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">

                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset" onclick="return confirm('Voulez-vous vraiment renisialisé ce frais?');">Réinitialiser</button>
            </fieldset>
        </div>
    </div>
</form>
<br>
<br>


<div class="row HF">
    <div class="panel panel-info">

        <div class="panel-heading"style="background-color: coral; color: #ffffff;">Descriptif des éléments hors forfait </div>

        <table class="table table-bordered table-responsive" >
            <tr > 
                <th class="col-md-3 date" >Date</th>
                <th class="col-md-3 libelle">Libellé</th>
                <th class="col-md-3 montant">Montant</th>
                <th class="col-md-3 actions"></th>
            </tr>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                $unIdFrais = $unFraisHorsForfait['id'];
                ?>

                <tr>
                <form method="post" action="index.php?uc=validerFrais&action=majHorsForfait" role="form">
                    <input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
                    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">
                    <input name="idfrais" type="hidden" id="idfrais" class="form-control" value="<?php echo $unIdFrais ?>">
                    <td>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" name="date" type="text" maxlength="45" value="<?php echo $date ?>">
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" name="libelle" type="text" maxlength="45" value="<?php echo $libelle ?>">
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" name="montant" type="text" maxlength="45" value="<?php echo $montant ?>">                            </div>
                        </div>
                    </td>

                    <td>   
                        <input id="corrigerFHF" name="corrigerFHF" type="submit" value="Corriger" class="btn btn-success"/>  
                        <input id="supprimerFHF" name="supprimerFHF" type="submit" value="Supprimer" class="btn btn-danger" />
                        <input id="reporterFHF" name="reporterFHF" type="submit" value="Reporter" class="btn btn-danger" style='background-color: coral'  onclick="return confirm('Voulez-vous vraiment reporter ce frais?');"/>
                    </td>
                </form>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <form method="post"  action="index.php?uc=validerFrais&action=validerMontant"
          role="form">
        <div class="col-md-4">
            <label for="nbJustificatif"> Nombre de justificatifs </label>
            <input type="text" id="nbJustificatif" 
                   name="nbjustificatifs"
                   size="2" maxlength="2" 
                   value="<?php echo $nbJustificatifs ?>" >

            <input name="validerMontant" type="submit" value="Valider" class="btn btn-success"/>  
            <input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
            <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">
        </div>
    </form>



</div>
</body>
</html>