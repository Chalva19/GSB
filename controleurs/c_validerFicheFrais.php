<?php
/**
 * Gestion de la connexion
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
$leMois = getMois(date('d/m/Y'));
$lesMois = getDerniers12Mois($leMois);
$lesVisiteurs = $pdo->getLesVisiteurs();
$idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_SPECIAL_CHARS );
$visiteurASelectionner = $idVisiteur;
$mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS );
$moisASelectionner = $mois;
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
$lesFraisHorsForfais = filter_input(INPUT_POST, 'lesFraisHorsForfais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);


switch($action){
    case 'selectionnerVisiteur':
        $lescles = array_keys($lesVisiteurs);
        $visiteurASelectionne = $lescles[0];
        include 'vues/v_listeVisiteur.php';
        break; 

    case 'validerFicheFrais':
        if (empty($lesFraisForfait) && empty($lesFraisHorsForfait) ){
            ajouterErreur('Aucune fiche de frais pour le visiteur et le mois selectionné');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");
        }else{
            $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $mois);
            include 'vues/v_validerFicheFrais.php';

        }
        break;
    
    case 'majForfait':
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;

    case 'majHorsForfait':
        if (isset($_POST['corrigerFHF'])){
            $pdo->majFraisHorsForfait($lesFraisHorsForfais);
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");
        }elseif (isset($_POST['supprimerFHF'])){
            $ids = array_keys($lesFraisHorsForfais);
            $idFrais = $ids[0]; // ici $premierId vaut 1
            $pdo->supprimerFraisHorsForfait($idFrais);
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");
        }
        

        break;
}

?>