<?php

global $wp_hasher;

if ( ! class_exists('Jcmartim_SendMailer_Settings')) {

    class Jcmartim_SendMailer_Settings {

        public static $options = [];

        public function __construct()
        {
            // Nome da chave no banco de dados
            self::$options = get_option('jcmartim_sendmailer_options');
            add_action( 'admin_init', [$this, 'jcmartim_sendmailer_admin_init'] );
        }

        /**
         * Método que cria as seções e campos da página de adminstração.
         */
        public function jcmartim_sendmailer_admin_init()
        {
            register_setting( //Registra a chave que deve guardar todas as informações no banco de dados.
                $option_group = 'jcmartim_sendmailer_group',                // Grupo ( usado em settings_field na page view )
                $option_name = 'jcmartim_sendmailer_options',               // Nome da chave (mesma da "get_option acima").           
                $args = [$this, 'jcmartim_sendmailer_options_sinitize'],    // Callback para fazer as devidas validações dos campos.
            );

            /**
             * Seção
             */
            add_settings_section(
                $id = 'jcmartim_sendmailer_section',
                $title = esc_html__( 'SMTP data', 'jcmartim-sendmailer' ),
                $callback = [$this, 'jcmartim_sendmailer_explanation'],
                $page = 'jcmartim_sendmailer_page'
            );
            /**
             * Campos
             */
            //SMTP de saída
            add_settings_field(
                $id = 'jcmartim_sendmailer_smtp',
                $title = esc_html__('Sending SMTP', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_smtp_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_smtp',
                ]
            );
            //Porta
            add_settings_field(
                $id = 'jcmartim_sendmailer_port',
                $title = esc_html__('Port', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_port_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_port',
                    'items'     => [
                        '587' => '587 LTS',
                        '465' => '465 SSL',
                    ]
                ]
            );
            //Usuário
            add_settings_field(
                $id = 'jcmartim_sendmailer_username',
                $title = esc_html__('Username', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_username_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_username',
                ]
            );
            //Senha
            add_settings_field(
                $id = 'jcmartim_sendmailer_password',
                $title = esc_html__('Password', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_password_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_password',
                ]
            );
            //Nome que será exibido.
            add_settings_field(
                $id = 'jcmartim_sendmailer_name',
                $title = esc_html__('Name', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_name_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_name',
                ]
            );
            //E-mail do destinatário.
            add_settings_field(
                $id = 'jcmartim_sendmailer_add_addres',
                $title = esc_html__('Recipient', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_add_addres_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_add_addres',
                ]
            );
            //Requer autenticação?
            add_settings_field(
                $id = 'jcmartim_sendmailer_auth',
                $title = esc_html__('Requires authentication?', 'jcmartim-sendmailer'),
                $callback = [$this, 'jcmartim_sendmailer_auth_callback'],
                $page = 'jcmartim_sendmailer_page',
                $section = 'jcmartim_sendmailer_section',
                $args = [
                    'label_for' => 'jcmartim_sendmailer_auth',
                ]
            );
        }

        //Texto explicativo da seção.
        public function jcmartim_sendmailer_explanation()
        {
            ?>
            <p style="max-width: 600px;"><?php esc_html_e('Enter here with SMTP configuration data.', 'jcmartim-sendmailer') ?></p>
            <?php
        }

        /**
         * Campos de formulários
         */
        //SMTP de saída
        public function jcmartim_sendmailer_smtp_callback()
        {
            ?>
            <input
                type="text"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_smtp]" 
                id="jcmartim_sendmailer_smtp"
                value="<?php echo isset(self::$options['jcmartim_sendmailer_smtp']) ? 
                esc_html(self::$options['jcmartim_sendmailer_smtp']) : '' ?>" />
            <p><?php esc_html_e('Your sending SMTP. Eg.: smtp.mydoman.com.', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Porta
        public function jcmartim_sendmailer_port_callback($args)
        {
            ?>
            <select 
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_port]" 
                id="jcmartim_sendmailer_port"
            >
            <?php
                foreach ($args['items'] as $key => $value) :
            ?>
                <option 
                    value="<?php echo esc_attr($key); ?>" 
                    <?php echo isset(self::$options['jcmartim_sendmailer_port']) ? selected($key, self::$options['jcmartim_sendmailer_port'], true) : '' ?>
                >
                <?php esc_attr_e($value); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <p><?php esc_html_e('Choose the SMTP port. Port 587 for (TLS) or port 465 (SSL).', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Usuário
        public function jcmartim_sendmailer_username_callback()
        {
            ?>
            <input
                type="email"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_username]" 
                id="jcmartim_sendmailer_username"
                value="<?php echo isset(self::$options['jcmartim_sendmailer_username']) ? 
                esc_html(self::$options['jcmartim_sendmailer_username']) : '' ?>" />
            <p><?php esc_html_e('User email. Eg.: myusermail@mydoman.com', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Senha
        public function jcmartim_sendmailer_password_callback()
        {
            ?>
            <input
                type="text"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_password]" 
                id="jcmartim_sendmailer_password"
                value="<?php echo isset(self::$options['jcmartim_sendmailer_password']) ? 
                esc_html(self::$options['jcmartim_sendmailer_password']) : '' ?>"
            />
            <p><?php esc_html_e('Enter your email password.', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Nome que será exibido.
        public function jcmartim_sendmailer_name_callback()
        {
            ?>
            <input
                type="text"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_name]" 
                id="jcmartim_sendmailer_name"
                value="<?php echo isset(self::$options['jcmartim_sendmailer_name']) ? 
                esc_html(self::$options['jcmartim_sendmailer_name']) : '' ?>" />
            <p><?php esc_html_e('Enter here the name that should appear in the email. Eg: My Company Name.', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Destinatário
        public function jcmartim_sendmailer_add_addres_callback()
        {
            ?>
            <input
                type="email"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_add_addres]" 
                id="jcmartim_sendmailer_add_addres"
                value="<?php echo isset(self::$options['jcmartim_sendmailer_add_addres']) ? 
                esc_html(self::$options['jcmartim_sendmailer_add_addres']) : '' ?>" />
            <p><?php esc_html_e('Recipient email. Eg.: contact@mydoman.com', 'jcmartim-sendmailer') ?></p>
            <?php
        }
        //Requer autenticação
        public function jcmartim_sendmailer_auth_callback()
        {
            ?>
            <input
                type="checkbox"
                name="jcmartim_sendmailer_options[jcmartim_sendmailer_auth]" 
                id="jcmartim_sendmailer_auth"
                value="1"
                <?php isset(self::$options['jcmartim_sendmailer_auth']) ? 
                    checked('1', self::$options['jcmartim_sendmailer_auth'], true) : checked('0', true, true);
                ?> 
            />
            <label for="jcmartim_sendmailer_auth"><?php echo __('Check to require authentication. By default it is true. If you do not require authentication, uncheck the box.'); ?></label>
            <?php
        }

        public function jcmartim_sendmailer_options_sinitize($fields)
        {
            $field_sanitize = [];

            foreach ($fields as $key => $value) {
                switch ($key) {
                    case 'jcmartim_sendmailer_smtp':
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                    case 'jcmartim_sendmailer_port':
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                    case 'jcmartim_sendmailer_username':
                        $field_sanitize[$key] = sanitize_email($value);
                        break;
                    case 'jcmartim_sendmailer_password':
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                    case 'jcmartim_sendmailer_name':
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                    case 'jcmartim_sendmailer_add_addres':
                        $field_sanitize[$key] = sanitize_email($value);
                        break;
                    case 'jcmartim_sendmailer_auth':
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                    default :
                        $field_sanitize[$key] = sanitize_text_field($value);
                        break;
                }
            }
            return $field_sanitize;
        }

    }

}