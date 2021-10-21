<?php
/*
 * Template Name: Recupera password
 // questo corrisponde a /recupera-password/
 */

/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di default presente nella root
require( 'template-parts/header/no-header.php' );
?>
<div id="loader" class="lds-dual-ring hidden overlay-cart"></div>

<section class="m-t-10 p-b-0 trigger-header" data-scroll-section id="intro">
    <div class="" data-scroll data-scroll-speed="2">
        <h3 class="o-h1"><?php the_title(); ?></h3>
    </div>
</section>

<section data-scroll-section class="wrap-prenota" id="delivery-login-forms">
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

    <form  class="wpcf7" method="post">
        <div id="delivery-recover-ok"></div>
        <div id="delivery-recover-error" class="delivery-status-error"></div>
        <div class="wrap-input-contact-group">
                    <span class="wpcf7-form-control-wrap">
                        <input type="email" name="email" id="email-login"
                               placeholder="<?php esc_html_e( "E-mail", "mos-theme" ); ?> *" required/>
                    </span>
        </div>
        <div class=" wrap-prenota">
            <div class="wpcf7 buttons">
                <a href="<?= get_permalink( get_page_by_path( 'delivery-login' ) ) ?>">⥢ <?php esc_html_e( "Torna al login/registrazione", 'mos-theme' ); ?></a>
                <a class="delivery-recover-password btn icon-qpc-delivery-20"><?php esc_html_e( "Recupera password", "mos-theme" ); ?></a>
            </div>
        </div>
    </form>
	</div>
</section>
<?php get_footer(); ?>
