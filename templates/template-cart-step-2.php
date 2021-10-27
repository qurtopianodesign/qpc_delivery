<?php
/*
 * Template Name: Carrello - Step 2
 // questo corrisponde a /delivery-login/
 */

/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di default presente nella root
require( 'template-parts/header/no-header.php' );
?>


<section data-scroll-section class="trigger-header wrap-prenota m-t-10" id="delivery-login-forms">
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

	<div class="" data-scroll data-scroll-speed="2">
			<h3 class="o-h1"><?php the_title(); ?></h3>
	</div>
    <div class="accordion-head-wrap">
        <div class="accordion-head ">
            <input type="radio" id="rd_0" name="rd" class="accordion-radio"/>
            <label class="accordion-head-label" for="rd_0">
				<?php esc_html_e( "Se ti sei già registrato clicca qui", "mos-theme" ); ?>
            </label>
            <form class="wpcf7 wpcf7-form accordion-head-content" method="post">
                <div id="delivery-login-error"></div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "E-mail", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="email" name="email" id="email-login" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "Password", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="password" name="password" id="password-login" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                    <a class="recupera-password" href="<?= get_permalink( get_page_by_path( 'recupera-password' ) ) ?>"><?php esc_html_e("Password dimenticata?", "mos-theme"); ?></a>
                </div>
                <div class=" wrap-prenota">
                    <div class="wpcf7 buttons">
                        <a href="<?= get_permalink( get_page_by_path( 'carrello' ) ) ?>">⥢ <?php esc_html_e( "Torna al carrello", 'mos-theme' ); ?></a>
                        <a class="delivery-login btn icon-qpc-delivery-20"><?php esc_html_e( "Login", "mos-theme" ); ?></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="accordion-head">
            <input type="radio" id="rd_1" name="rd" class="accordion-radio" checked="checked"/>
            <label class="accordion-head-label" for="rd_1">
				<?php esc_html_e( "Se sei un nuovo utente compila il form", "mos-theme" ); ?>
            </label>

            <form class="wpcf7 wpcf7-form accordion-head-content" method="post">
                <div id="delivery-register-error"></div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "Nome", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="text" name="first_name" id="first_name" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e("Cognome", "mos-theme"); ?> *</label>
                                <input class="input-border" type="text" name="last_name" id="last_name" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "E-mail", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="email" name="email" id="email" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "Password", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="password" name="password" id="password" required/>
                            </span>
                </div>
                <div class="wrap-input-contact-group">
                            <span class="wpcf7-form-control-wrap">
															<label for=""><?php esc_html_e( "Telefono", "mos-theme" ); ?> *</label>
                                <input class="input-border" type="tel" name="phone_number" id="phone_number" required/>
                            </span>
                </div>

                <div class="wrap-check-input">
                            <span class="wpcf7-form-control-wrap classform-check-input">
                                <span class="wpcf7-form-control wpcf7-acceptance">
                                    <span class="wpcf7-list-item">
                                        <input type="checkbox" value="1" required/>
                                    </span>
                                </span>
                            </span> <?php esc_html_e( "Autorizzo ai sensi dell’art. 13 D.lgs. 13/06, informato ai sensi dell’art. 7 D.lgs. 13/06", "mos-theme" ); ?>
                </div>
                <div class="wrap-check-input">

                            <span class="wpcf7-form-control-wrap classform-check-input">
                                <span class="wpcf7-form-control wpcf7-acceptance">
                                    <span class="wpcf7-list-item">
                                        <input type="checkbox" name="class:form-check-input" value="1" required/>
                                    </span>
                                </span>
                            </span>
		       <div class="">
					<?php
						_e( 'Autorizzo il Salvataggio e la Manipolazione dei miei Dati Personali <a href="'.get_privacy_policy_url().'">(GDPR)*.</a>', 'mos-theme' ); ?>
						</div>
                </div>
                <div class=" wrap-prenota">
                    <div class="wpcf7 buttons">
                        <a href="<?= get_permalink( get_page_by_path( 'carrello' ) ) ?>">⥢ <?php esc_html_e( "Torna al carrello", 'mos-theme' ); ?></a>
                        <a class="delivery-register btn icon-qpc-delivery-07"><?php esc_html_e( "Registrati", "mos-theme" ); ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
	</div>
</section>
<section data-scroll-section class="qpc-container" id="delivery-voucher-form">
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

    <form class="voucher-form wrap-input-contact-group wpcf7 wpcf7-form wrap-prenota">
        <div id="delivery-user-ok"></div>
        <p><?php esc_html_e( "Ora inserisci nome ed email del beneficiario della cena al quale invieremo il relativo coupon regalo.", "mos-theme" ); ?></p>
        <div id="delivery-checkout-error"></div>
        <div id="delivery-checkout-ok"></div>
        <input type="hidden" id="voucher-api-token" name="voucher-api-token" value="" required/>
        <input type="hidden" id="voucher-first_name" name="voucher-first_name" value="" required/>
        <input type="hidden" id="voucher-last_name" name="voucher-last_name" value="" required/>
        <input type="hidden" id="voucher-email" name="voucher-email" value="" required/>
        <input type="hidden" id="voucher-address" name="voucher-address" value="" required/>
        <input type="hidden" id="voucher-city" name="voucher-city" value="" required/>
        <input type="hidden" id="voucher-country" name="voucher-country" value="" required/>
        <input type="hidden" id="voucher-post_code" name="voucher-post_code" value="" required/>
        <input type="hidden" id="voucher-phone_number" name="voucher-phone_number" value=""/>
        <span class="wpcf7-form-control-wrap">
					<label for=""><?php esc_html_e( 'Nome e cognome *', 'mos-theme' ); ?></label>
					<input class="input-border" type="text" id="voucher-dest-name" name="name" value="" required="required"/></span>
        <span class="wpcf7-form-control-wrap">
					<label for=""><?php esc_html_e( 'E-mail *', 'mos-theme' ); ?></label>
					<input class="input-border" type="email" id="voucher-dest-email" name="email" value="" required="required"/></span>
        <!-- <span class="wpcf7-form-control-wrap"><input type="tel" id="voucher-dest-tel" name="tel" value="" size="40"
                                                         placeholder="<?php esc_html_e( 'Telefono', 'mos-theme' ); ?>"/></span>-->
        <span class="wpcf7-form-control-wrap">
            <textarea name="note" id="voucher-notes" cols="40" rows="10"
                     class="wpcf7-form-control wpcf7-textarea wpcf7-validates-as-required form-control input-border"
                     placeholder="<?php esc_html_e( "Note particolari, es: Bambino con carrozzina, eventuali allergie, intolleranze alimentari, ecc…", 'mos-theme' ); ?>"></textarea>
        </span>
        <div class=" wrap-prenota">
            <div class="wpcf7 buttons">
                <a href="<?= get_permalink( get_page_by_path( 'carrello' ) ) ?>">⥢ <?php esc_html_e( "Torna al carrello", 'mos-theme' ); ?></a>
								<div class="btn delivery-checkout btn-black"><?php esc_html_e( "Procedi con l'ordine", "mos-theme" ); ?> </div>
        </div>

        </div>

    </form>
	</div>
</section>

<div id="loader" class="lds-dual-ring hidden overlay-cart"></div>
<?php get_footer(); ?>
