<?php
/**
 * Vue ValiderFichedeFrais
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
<form action="index.php?uc=validerFrais&action=validerFicheFrais"  method="post" role="form">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="lstVisiteurs" accesskey="n">Visiteur: </label>
            <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
            <?php
                foreach ($lesVisiteurs as $unVisiteur) {
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
                foreach ($lesMois as $unMois) {
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
    <div class="row mt-3" style="padding-bottom: 20px;">
        <div class="col">
            <input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" role="button">
        </div>
    </div>
</form>