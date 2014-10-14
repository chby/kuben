<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil innehåller följande konfigurationer: Inställningar för MySQL,
 * Tabellprefix, Säkerhetsnycklar, WordPress-språk, och ABSPATH.
 * Mer information på {@link http://codex.wordpress.org/Editing_wp-config.php 
 * Editing wp-config.php}. MySQL-uppgifter får du från ditt webbhotell.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsen, du kan kopiera denna fil direkt till
 * "wp-config.php" och fylla i värdena.
 *
 * @package WordPress
 */

// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define('DB_NAME', 'kuben');

/** MySQL-databasens användarnamn */
define('DB_USER', 'root');

/** MySQL-databasens lösenord */
define('DB_PASSWORD', 'batman');

/** MySQL-server */
define('DB_HOST', 'localhost');

/** Teckenkodning för tabellerna i databasen. */
define('DB_CHARSET', 'utf8');

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@E<MH6V19hVL]qW |cQ4%*L,y?>Zz(S3udW9}La(|XvLogv;%RseMn>t=1zxIhWC');
define('SECURE_AUTH_KEY',  '?+k>xyf|CEnT0dEmk7r%^]ZA.W&-t1O%Yb@,dNjOn#bTz<D)w)|us#9w.{U.DKo;');
define('LOGGED_IN_KEY',    '0Pa^][Ahl33jY4-|vT--s^pFW@`vrhn!2d@f5jW{{ae,K,Y&;ypLkF<1)O}u$pqX');
define('NONCE_KEY',        'J+VcD$o21 P)<$CFDs>}u?VithO,7nF[~G?e+y86c:4[AR|*%yt,Oq=M }Bs^H9V');
define('AUTH_SALT',        'KiTw1j(wC)}?mC!2|Qzo(mZa3/:0]hu?U!XfR@f1G m<G%ka|fI0Av+YT>Z-v)vX');
define('SECURE_AUTH_SALT', 'wC0r^gFU&`T&bg4P%Q+9VMyE70+XB8 LGz~bp;XZlT|da(Te^{M0(bY+X@Abue{w');
define('LOGGED_IN_SALT',   'QJ#J; ~NeqYk#pt0QM}[wxh8m%C-aB#+Y FjoR;mZssAioPS]TxU+L/8+mXx}iAW');
define('NONCE_SALT',       'GAg.ZF!Z4!yt%P.c7EAMTd|~7(`- 0Vvo6}Ohe,fU*/T}U$Gp`Mb2$Rlf%b;^c9a');

/**#@-*/

/**
 * Tabellprefix för WordPress Databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Endast siffror, bokstäver och understreck!
 */
$table_prefix  = 'wp_';

/** 
 * För utvecklare: WordPress felsökningsläge. 
 * 
 * Ändra detta till true för att aktivera meddelanden under utveckling. 
 * Det är rekommderat att man som tilläggsskapare och temaskapare använder WP_DEBUG 
 * i sin utvecklingsmiljö. 
 */ 
define('WP_DEBUG', false);

/* Det var allt, sluta redigera här! Blogga på. */

/** Absoluta sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');