<?php
/*
 * Template Name: Ordine completato
 // questo corrisponde a /ordine-completato/
 */
/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di dafault presente nella root
require( 'template-parts/header/no-header.php' );
?>

<div id="loader" class="lds-dual-ring hidden overlay-cart"></div>

<section class="trigger-header m-t-10" data-scroll-section id="intro">
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

    <div class="" data-scroll data-scroll-speed="2">
        <h3 class="o-h1"><?php the_title(); ?></h3>
    </div>
    <div data-scroll data-scroll-speed="4" class="wrap-prenota" id="delivery-order-completed" delivery-order-completed="<?=$_GET['orderId']; ?>" data-scroll-offset="900">
		<?php get_template_part( 'loop', 'page' ); ?>
        <div id="delivery-order-pending" style="display: none;"><h3><?php esc_html_e("Il pagamento dell'ordine è in fase di elaborazione.", "mos-theme"); ?></h3></div>
        <div id="delivery-order-processing" style="display: none;"><h3><?php esc_html_e("Il pagamento dell'ordine è andato a buon fine.", "mos-theme"); ?></h3></div>
        <h3><?php esc_html_e("Riepilogo ordine", "mos-theme");?></h3>
				<div class="wrap-delivery-order-completed">
	        <div id="delivery-order-order_number">
	            <span><?php esc_html_e("Numero d'ordine:", "mos-theme");?></span> <span id="delivery-order-order_number-value"><?php $_GET['orderId']; ?></span>
	        </div>
	        <div id="delivery-order-grand_total">
	            <span><?php esc_html_e("Totale:", "mos-theme");?></span> <span id="delivery-order-grand_total-value"></span>
	        </div>
	        <div id="delivery-order-item_count">
	            <span><?php esc_html_e("N° articoli acquistati:", "mos-theme");?></span> <span id="delivery-order-item_count-value"></span>
	        </div>
	        <div id="delivery-order-first_name">
	            <span><?php esc_html_e("Nome:", "mos-theme");?></span> <span id="delivery-order-first_name-value"></span>
	        </div>
	        <div id="delivery-order-last_name">
	            <span><?php esc_html_e("Cognome:", "mos-theme");?></span> <span id="delivery-order-last_name-value"></span>
	        </div>

	        <div id="delivery-order-phone_number">
	            <span><?php esc_html_e("Telefono:", "mos-theme");?></span> <span id="delivery-order-phone_number-value"></span>
	        </div>
	        <div id="delivery-order-voucher_name">
	            <span><?php esc_html_e("Nome destinatario voucher:", "mos-theme");?></span> <span id="delivery-order-voucher_name-value"></span>
	        </div>
	        <div id="delivery-order-voucher_email">
	            <span><?php esc_html_e("E-mail destinatario voucher:", "mos-theme");?></span> <span id="delivery-order-voucher_email-value"></span>
	        </div>
	        <div id="delivery-order-notes">
	            <span><?php esc_html_e("Note:", "mos-theme");?></span> <span id="delivery-order-notes-value"></span>
	        </div>
				</div>
    </div>
	</div>
</section>
<?php get_footer(); ?>
