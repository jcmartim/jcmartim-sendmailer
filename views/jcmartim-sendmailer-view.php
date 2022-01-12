<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title(), 'jcmartim-sendmailer' ) ?></h1>
    <form action="options.php" method="post">
        <?php
            settings_fields('jcmartim_sendmailer_group'); // Adiciona campos hidden e nouce ao formulário.
            do_settings_sections('jcmartim_sendmailer_page');  // Conteúdo da seção.
            submit_button(esc_html__('Save Settings', 'jcmartim-sendmailer'));
        ?>
    </form>
</div>