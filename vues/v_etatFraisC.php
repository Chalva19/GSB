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
<form action="index.php?uc=validerFrais&action=detailFicheFrais"  method="post" role="form">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="lstVisiteur" accesskey="n">Visiteur: </label>
            <select id="lstVisiteur" name="lstVisiteur" class="form-control">
            <?php
                foreach ($listeVisiteurs as $unVisiteur) {
                    $idVisiteur = $unVisiteur['id'];
                    $nomVisiteur = $unVisiteur['nom'];
                    $prenomVisiteur = $unVisiteur['prenom'];
                    if ($idVisiteur == $visiteurASelectionner) {
                        ?>
                        <option selected value="<?php echo $idVisiteur ?>">
                        <?php echo $nomVisiteur . ' ' . $prenomVisiteur ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $idVisiteur ?>">
                        <?php echo $nomVisiteur . ' ' . $prenomVisiteur ?> </option>
                        <?php
                    }
                }
            ?>
            </select>
            </div>
            <div class="col-md-6 form-group">
            <label for="lstMois" accesskey="n">Mois: </label>
            <select id="lstMois" name="lstMois" class="form-control">
            <?php
                foreach ($listeMois as $unMois) {
                    $mois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $MoisASelectionner) {
                        ?>
                        <option selected value="<?php echo $mois ?>">
                        <?php echo $numMois .'/'. $numAnnee ?> </option>
                        <?php
                    } else {
                        ?>
                        <option selected value="<?php echo $mois ?>">
                        <?php echo $numMois .'/'. $numAnnee ?> </option>
                        <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" role="button">
        </div>
    </div>
</form>
<hr>
<div class="row" style="padding-bottom: 20px;">    
    <h2 style="color: orange;"> Valider la fiche de frais </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
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
                <button class="btn btn-danger" type="reset">Réinitialisé</button>
            </fieldset>
        </form>
    </div>
</div>

<div class="panel panel-warning">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>
            <th class='bouton' > </th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td >
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialisé</button>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<div >
<label for="nb_justificatif">Nombre de justificatifs :</label>
<input type="text" value="<?php echo $nbJustificatifs ?>">
</div>
<div>
<input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
<input id="annuler" type="reset" value="Réinitialisé" class="btn btn-danger" role="button">
</div>