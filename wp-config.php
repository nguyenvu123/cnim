<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'cnim');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
#define('DB_PASSWORD', 'fWKhjfs1');
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', '');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

define( 'WP_AUTO_UPDATE_CORE', false );

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '75R|h@In+<adI$/YUIEK5t##.j0Z%*7W[L!EJ2DIS*z5<9;!v3]n^7>#XYz|s0:5');
define('SECURE_AUTH_KEY',  '6qH4?RTUq9zE}Iipoj:Z$ivlGqc[+.H)3!de47};s]B=Y$n2<9ozP`BaZM%.@z4D');
define('LOGGED_IN_KEY',    ')U)e-5!:Un5?~k,W`U+nVS4+kQF%b|nv-O8WM8/i:X-/iTr?w9KB(Li,3@*7R)8i');
define('NONCE_KEY',        '^o/@ ns-SJY]Z0+q{xG$>(paeli^QeKCjKwZe-k@vc_a|[nqY!;]no,gxM)5[z$+');
define('AUTH_SALT',        '0F>0&fdCi7N`N<1Vv^u^)*f=j48OcR.m5DAik{8*T`ufz&zI,w,bPh6YyzI``S;M');
define('SECURE_AUTH_SALT', 'XM{?Bru2FB?$6q9zG!XX7%rBe!,]v~M=H&,gQqd)lWF`ttwhp6-5wn0vnJabuz)<');
define('LOGGED_IN_SALT',   ' @Olm 01ScIR1SLUWPnSsw{al01sSC*k:z~h8d_O.ouA0e(4IA[f hl:i&a/ c+C');
define('NONCE_SALT',       '*={>q6O(l;o]Ou{$lduEw`9*i{oNbwq5Sdz4=+LMpqe~l(?[; 4Y`Bsf)KP=4@gv');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress 
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
