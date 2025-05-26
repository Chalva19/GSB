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
$idFrais = filter_input(INPUT_POST, 'idfrais', FILTER_SANITIZE_SPECIAL_CHARS);
$libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_SPECIAL_CHARS);
$montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);



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
            $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur, $mois);
            ajouterErreur('Les frais forfait ont bien ete modifié');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        } else {

            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;

    case 'majHorsForfait':
        if (isset($_POST['corrigerFHF'])){

            $pdo->majFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant, $idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            ajouterErreur('Les frais hors forfait ont bien ete modifié');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        }elseif (isset($_POST['supprimerFHF'])){

            $pdo->supprimerFraisHorsForfait($idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            ajouterErreur('Les frais hors forfait ont bien ete supprimé');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        }elseif (isset($_POST['reporterFHF'])){
            $moisSuivant = getMoisSuivant($mois);
            $pdo->creeNouveauFraisHorsForfaitV($idVisiteur, $moisSuivant, $libelle, $date, $montant);
            $libelle = "Frais refusé : ". $libelle;
            $pdo->majFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant, $idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);

            //var_dump($idVisiteur, $moisSuivant,$mois, $libelle, $date, $montant);
            //$pdo->creeNouveauFraisHorsForfaitV($idVisiteur, $moisSuivant, $libelle, $date, $montant);
            //$pdo->majFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant, $idFrais);
            ajouterErreur("Le frais hors forfait a bien été reporté");
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");


        } 
        break;

    case 'validerMontant';
        $totalFF = $pdo->calculerFF($idVisiteur, $leMois);
        $totalFF2 = $totalFF [0][0];
        //var_dump($totalFF2);

        $totalFHF = $pdo->calculerFHF($idVisiteur, $leMois);
        $totalFHF2 = $totalFHF [0][0];
        // var_dump($totalFHF2);

        $total = $totalFF2 + $totalFHF2;
        //var_dump($total);
        $montantValide = $pdo->totalValide($idVisiteur, $leMois, $total);
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, 'VA');

        ajouterErreur('Vos frais forfaits et hors forfaits ont bien été validés');
        include 'vues/v_erreurs.php'; 
        header("Refresh: 2;URL=index.php?uc=validerFicheFrais&action=selectionnerUtilisateur");
              
        break;
}

?>