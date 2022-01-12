<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

if (!class_exists('JCMartim_Sendmailer_Back_End')) {

    class JCMartim_Sendmailer_Back_End
    {

        public function __construct()
        {
            add_action('phpmailer_init', [ $this, 'jcmartim_sendmailer_init' ]);
        }

        public function jcmartim_sendmailer_init($phpmailer)
        {
            //Recupera os dados do banco.
            $data = get_option('jcmartim_sendmailer_options');

            $phpmailer->Host = $data['jcmartim_sendmailer_smtp'];
            $phpmailer->Port = $data['jcmartim_sendmailer_port'];
            $phpmailer->Username = $data['jcmartim_sendmailer_username'];
            $phpmailer->Password = $data['jcmartim_sendmailer_password'];
            $phpmailer->setFrom($data['jcmartim_sendmailer_username'], $data['jcmartim_sendmailer_name'], false);
            $phpmailer->addReplyTo($data['jcmartim_sendmailer_add_addres'], $data['jcmartim_sendmailer_name']);
            $phpmailer->SMTPAuth = isset($data['jcmartim_sendmailer_auth']) ? true : false;
            $phpmailer->SMTPSecure = $data['jcmartim_sendmailer_port'] == '587' ? 'lts' : 'ssl';
            $phpmailer->IsSMTP();
        }
    }
}
