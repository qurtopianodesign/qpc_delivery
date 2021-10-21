<?php
/*
 * Template Name: Cambia password
 // questo corrisponde a /cambia-password/
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

<section data-scroll-section class="wrap-prenota" >
	<div class="trigger-header" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

    <form  class="wpcf7" method="post">
        <input type="hidden" name="token" id="token" value="<?=$_GET['token']; ?>" />
        <div id="delivery-change-ok"></div>
        <div id="delivery-change-error" class="delivery-status-error"></div>
        <div class="wrap-input-contact-group">
                    <span class="wpcf7-form-control-wrap">
                        <input type="email" name="email" id="email"
                               placeholder="<?php esc_html_e( "E-mail", "mos-theme" ); ?> *" required/>
                    </span>
        </div>
        <div class="wrap-input-contact-group">
                    <span class="wpcf7-form-control-wrap">
                        <input type="password" name="password" id="password"
                               placeholder="<?php esc_html_e( "Password", "mos-theme" ); ?> *" required/>
                    </span>
        </div>
        <div class="wrap-input-contact-group">
                    <span class="wpcf7-form-control-wrap">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="<?php esc_html_e( "Conferma password", "mos-theme" ); ?> *" required/>
                    </span>
        </div>
        <div class=" wrap-prenota">
            <div class="wpcf7 buttons">
                <a href="<?= get_permalink( get_page_by_path( 'delivery-login' ) ) ?>">⥢ <?php esc_html_e( "Torna al login/registrazione", 'mos-theme' ); ?></a>
                <a class="delivery-change-password btn icon-qpc-delivery-20"><?php esc_html_e( "Cambia password", "mos-theme" ); ?></a>
            </div>
        </div>
    </form>
	</div>
</section>
<?php get_footer(); ?>
