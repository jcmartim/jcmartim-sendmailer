<?php

/**
 * Plugin Name:       Jcmartim SendMailer
 * Plugin URI:        https://jcmartim.site/plugins/jcmartim-sendmailer
 * Description:       SMTP configuration for sending emails from WordPress.
 * Version:           1.0.0
 * Author:            João Carlos Martimbianco
 * Author URI:        https://jcmartim.site
 * Requires at least: 4.7
 * Tested up to:      5.8.2
 * Requires PHP:      7.4
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       jcmartim-sendmailer
 * Domain Path:       /languages
 **/
//*

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

if ( ! class_exists( 'Jcmartim_SendMailer' ) ) {
    class Jcmartim_SendMailer {
    
        public function __construct()
        {
            //Instancias as constantes
            $this->define_constants();

            //Registra os metodos de instalação, desativação e desistalação respectivamente.
            register_activation_hook( JCMARTIM_SENDMAILER_PATH, [$this, 'activate'] );
            register_deactivation_hook( JCMARTIM_SENDMAILER_PATH, [$this, 'deactivation'] );
            //register_uninstall_hook( JCMARTIM_SENDMAILER_PATH, [$this, 'uninstall'] );

            //Página e Menu do Front end.
            add_action('admin_menu', [$this, 'jcmartim_sendmailer_admin_page']);

            //Back-end do plugin.
            require_once( JCMARTIM_SENDMAILER_PATH . 'class/jcmartim-sendmailer-backend.php');
            new JCMartim_Sendmailer_Back_End();

            //Classe de configurações do plugins.
            require_once( JCMARTIM_SENDMAILER_PATH . 'class/jcmartim-sendmailer-settings.php');
            new Jcmartim_SendMailer_Settings();
            
        }

        public function define_constants()
        {
            define('JCMARTIM_SENDMAILER_PATH', plugin_dir_path(__FILE__));
            define('JCMARTIM_SENDMAILER_URL', plugin_dir_url(__FILE__));
            define('JCMARTIM_SENDMAILER_VERSION', '1.0.0');
        }

        /**
         * Método de ativação do plugin.
         */
        public static function activate()
        {   
            // code...
        }

        /**
         * Método de desativação do plugin.
         */
        public static function deactivation()
        {
            // code...
        }

        /**
         * Método de desinstalação do plugin.
         */
        public static function uninstall()
        {
            # code...
        }

        /**
         * Método para carregar os arquivos de tradução do plugin.
         *
         * @return void
         */
        public function load_textdomain()
        {
            load_plugin_textdomain(
                $domain = 'jcmartim-sendmailer', 
                $deprecated = false, 
                $plugin_rel_path = dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }

        /**
         * Página de adminstração.
         *
         * @return void
         */
        public function jcmartim_sendmailer_admin_page()
        {
            add_menu_page(
                'JCMartim SendMailer',
                'JCMartim SendMailer',
                'edit_pages',
                'jcmartim-sendmailer',
                [$this, 'jcmartim_sendmailer_page'],
                'dashicons-email',
                100
            );
        }
        public function jcmartim_sendmailer_page()
        {
            if ( !current_user_can( 'edit_pages' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.', 'jcmartim-sendmailer', ) );
            }
            //Importa a classe de configurações.
            require_once(JCMARTIM_SENDMAILER_PATH . 'views/jcmartim-sendmailer-view.php');
        }
    
    }
}

if ( class_exists( 'Jcmartim_SendMailer' ) ) {
    if ( is_admin() )
    new Jcmartim_SendMailer();
}