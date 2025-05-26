<?php
/**
 * Fonctions pour l'application GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return vrai ou faux
 */
function estConnecte()
{
    return isset($_SESSION['idVisiteur']) || isset($_SESSION['idComptable']);
}

/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idVisiteur ID du visiteur
 * @param String $nom        Nom du visiteur
 * @param String $prenom     Prénom du visiteur
 *
 * @return null
 */
function connecterV($idVisiteur, $nom, $prenom)
{
    $_SESSION['idVisiteur'] = $idVisiteur;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
}
function connecterC($idComptable, $nom, $prenom)
{
    $_SESSION['idComptable'] = $idComptable;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
}

/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @return Date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    /*@list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));*/
    $dateFormatee = DateTime::createFromFormat('d/m/Y', $maDate);
    if ($dateFormatee) {
        $dateSql = $dateFormatee->format('Y-m-d');
    } else {
        $dateSql = null; // ou gérer une erreur
    }
    return $dateSql;

}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @return Date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    @list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

function getMoisPrecedent($mois){
    $annee = substr($mois, 0,4);
    $moisrecup = substr($mois, -2);
    if ($moisrecup == 01){
        $moisPrecedent = 12;
        $anneePrecedente = $annee -1;
    }
    else {
        $moisPrecedent = $moisrecup -1;
        $anneePrecedente = $annee;
    }
    return $anneePrecedente . $moisPrecedent;
}

function getMoisSuivant($mois){
    $annee = substr($mois, 0,4);
    $moisrecup = substr($mois, -2);
    if ($moisrecup == 12){
        $moisSuivant = 01;
        $anneeSuivante = $annee + 1;
    }
    else {
        $moisSuivant = $moisrecup + 1;
        $anneeSuivante = $annee;
    }
    $moisSuivant = str_pad($moisSuivant, 2, "0", STR_PAD_LEFT);
    return $anneeSuivante . $moisSuivant;
}



/**
 * Retourne les 12 mois precedents
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getDerniers12Mois($mois) {
    // Extraire l'année et le mois
    $numAnnee = (int)substr($mois, 0, 4);
    $numMois = (int)substr($mois, 4, 2);

    // Initialiser un tableau pour stocker les mois
    $listemois = array();

    // Boucle sur les 12 derniers mois
    for ($i = 0; $i < 12; $i++) {
        // Formater le mois avec deux chiffres
        $listemois[$i] = [
            'mois' => $numAnnee . str_pad($numMois, 2, '0', STR_PAD_LEFT), // Format "YYYYMM"
            'numAnnee' => $numAnnee,
            'numMois' => str_pad($numMois, 2, '0', STR_PAD_LEFT) // Format "MM"
        ];

        // Décrémenter le mois
        $numMois--;

        // Si on passe en dessous de janvier, revenir à décembre de l'année précédente
        if ($numMois == 0) {
            $numMois = 12;
            $numAnnee--;
        }
    }

    return $listemois;
}

// Test
//print_r(getDerniers12Mois(date('d/m/Y')));

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param String $valeur Valeur
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    if (!is_numeric($valeur) || $valeur < 0) {
        return false;
    }
    return preg_match('/^\d+$/', (string)$valeur) === 1;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers Un tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    if (!is_array($tabEntiers)) {
        return false; // Empêche l'erreur si ce n'est pas un tableau
    }

    foreach ($tabEntiers as $unEntier) {
        if (!is_numeric($unEntier) || !estEntierPositif($unEntier)) {
            return false;
        }
    }
    return true;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param String $dateTestee Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $anPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param Array $lesFrais Tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais Date des frais
 * @param String $libelle   Libellé des frais
 * @param Float  $montant   Montant des frais
 *
 * @return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an"
                );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg Libellé de l'erreur
 *
 * @return null
 */
function ajouterErreur($msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}
