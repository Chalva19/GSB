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

<hr>
<div class="row" style="padding-bottom: 20px;">    
    <h2 style="color: orange;"> Valider la fiche de frais </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" action="index.php?uc=validerFrais&action=majFraisForfait" role="form">
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
            <th class='bouton'> </th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $id => $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; ?>
            <tr>
                <form method="post" action="index.php?uc=validerFrais&action=corrigerFraisHorsForfait">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <td><input type="date" name="date" value="<?php echo $date ?>" class="form-control"></td>
                    <td><input type="text" name="libelle" value="<?php echo $libelle ?>" class="form-control"></td>
                    <td><input type="number" step="0.01" name="montant" value="<?php echo $montant ?>" class="form-control"></td>
                    <td>
                        <button class="btn btn-success" type="submit" name="corriger">Corriger</button>
                        <button class="btn btn-danger" type="submit" name="reinitialiser" value="<?php echo $id; ?>">Réinitialiser</button>
                    </td>
                </form>
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