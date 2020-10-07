<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'cryptonews_db' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dS^W<Qu?(9QSgk=)53KN9!,7(]f#x>cqlB$jsH$&SRi$H6A,+&reW!8f~|{sI9G>' );
define( 'SECURE_AUTH_KEY',  'W!.O7x!,;2{F5GQ8IZ#e4:u]%]XKT8$UPAo(f.pA+oQWrCG(9{^xtgTqQ]bndz{t' );
define( 'LOGGED_IN_KEY',    '/`+LC-oMF:LrBUw7t+#ZS?44pNuA,6~IA5ZB&U>_wPk,?3.s`pj!4SVJfov9&QF7' );
define( 'NONCE_KEY',        '{{?fCAK*#?pqx{-/g<l`_=b2E&%CM&STu-6P 9<wE23SS/p97afiAl?>g;u%<NmU' );
define( 'AUTH_SALT',        'df{KTHkLyw{G8|]UraPdrc^qgMLOx+,0yv}z$,;/m4V1aQ>R~$U/#PW/wiotvcC0' );
define( 'SECURE_AUTH_SALT', '4|H^x$Y7&BQ#7.?* ,X$/`<,Y>l$Bw#bO[*RL9!mk]%%m4&GB$2O`g~_@{^c+i:|' );
define( 'LOGGED_IN_SALT',   '}vq6@&%Z5BM{aulB:r_V/4a(XaeS-@Ih5eUqG$c_FX]-]pg3}hs]ZGmJ|)IsLM2w' );
define( 'NONCE_SALT',       'A{L:N(q<b}}DdS~NONxn+68Q[S/X<.29-6^)rTTEgBR$V`+M_f)H8g*#X>r[E@yq' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
