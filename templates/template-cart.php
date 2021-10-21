<?php
/*
 * Template Name: Carrello
 // questo corrisponde a /carrello/
 */
/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di dafault presente nella root
require( 'template-parts/header/no-header.php' );
?>
<div id="loader" class="lds-dual-ring hidden overlay-cart">

</div>

<section class="m-t-10" data-scroll-section id="intro">
	<div class="trigger-header" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">

    <div class="" data-scroll data-scroll-speed="2">
        <h3 class="o-h1"><?php the_title(); ?></h3>
    </div>
    <div data-scroll data-scroll-speed="4" class="wrap-prenota" id="delivery-cart" >
    </div>
    <div class=" wrap-prenota">
        <div class="wpcf7 buttons">
            <a href="<?= get_permalink( get_page_by_path( 'regala-un-esperienza' ) ) ?>">⥢ <?php esc_html_e( "Torna agli acquisti", 'mos-theme' ); ?></a>
            <a class="btn-add-to-cart btn btn-black"
               href="<?= get_permalink( get_page_by_path( 'delivery-login' ) ); ?>"><?php esc_html_e( "Procedi con l'ordine", "mos-theme" ); ?></a>
        </div>
				<div class="credit-card-info-box">
					<img src="<?php echo site_url(); ?>/wp-content/themes/mos/images/credit-cards-icons.svg"
				           alt="<?php bloginfo('name') ?>  <?php bloginfo('description') ?>">

				<p>
					<?php the_field('testi_personalizzati', 'option'); ?>
				</p>
      </div>
    </div>
	</div>
</section>

<div id="loader" class="lds-dual-ring hidden overlay"></div>

<?php get_footer(); ?>
