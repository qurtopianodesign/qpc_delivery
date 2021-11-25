<?php
/*
 * Template Name: Vouchers
 // questo corrisponde a /vouchers/
 */
/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di dafault presente nella root
require( 'template-parts/header/no-header.php' );
global $wp_query;
?>
<div id="loader" class="lds-dual-ring hidden overlay-cart">
	<p><?php esc_html_e( "Stiamo inserendo il prodotto nel carrello", 'mos-theme' ); ?></p>
</div>


<section class="m-t-10 trigger-header" data-scroll-section id="intro">
	<div class="grifone"></div>
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">
    <div class="" data-scroll data-scroll-speed="2" >
        <h3 class="o-h1"><?php the_title(); ?></h3>
    </div>
		<div data-scroll data-scroll-speed="2" class="wrap-prenota" >
			 <?php get_template_part( 'loop', 'page' ); ?>
		</div>
    <div data-scroll data-scroll-speed="4" class=" accordion-head-wrap" >
		<?php
		$products = QPC_Delivery::getVouchers();
		foreach ( $products as $product ): 
		

		?>

            <div class=" voucher wpcf7 accordion-head">
							<div class="wrap-qpc-price">
								<span class="qpc-price"><?= QPC_Delivery::getProductName($product); //$name; ?></span>
                    <div class="wrap-qpc-price-sub">
                        <p class="qpc-price"><?=  str_replace(",00", "", number_format( $product["price"], 2, ",", "" )); ?> €</p>
                        <button class="delivery-add-to-cart btn icon-qpc-delivery-15"
                                delivery-price="<?= $product['price']; ?>"
                                delivery-productId="<?= $product['id']; ?>"
                                delivery-qty="1"><?php esc_html_e( "Aggiungi al carrello", 'mos-theme' ); ?>
	</button>
                    </div>
				</div>
					<?php
					$productDetails = QPC_Delivery::getProduct( QPC_Delivery::getProductSlug($product) );

					foreach ( $productDetails['attributes'] as $attribute ) : ?>
						<div class="wrap-qpc-price">
								<span class="qpc-price"><?= QPC_Delivery::getProductName($product) ?> + <?= QPC_Delivery::getAttributeTitle($attribute); //$title; ?></span>
								<div class="wrap-qpc-price-sub">
								<p class="qpc-price"><?=  str_replace(",00", "", number_format( $product['price'] + $attribute["price"], 2, ",", "" )); ?>
										€
								</p>
								<button class="delivery-add-to-cart btn icon-qpc-delivery-15"
									delivery-productId="<?= $product['id']; ?>"
									delivery-price="<?= $product['price']; ?>"
									delivery-attributeId="<?= $attribute["id"]; ?>"
									delivery-attributeTheId="<?= $attribute["attribute_id"]; ?>"
									delivery-attributePrice="<?= $attribute["price"]; ?>"
									delivery-attributeValue="<?= QPC_Delivery::getAttributeTitle($attribute); ?>"
									delivery-qty="1"><?php esc_html_e( "Aggiungi al carrello", 'mos-theme' ); ?>
					</button>
							</div>
						</div>
					<?php endforeach; ?>
					<input type="radio" id="rd_<?= $product["id"]; ?>" name="rd" class="accordion-radio"/>

					<label class="accordion-head-label" for="rd_<?= $product["id"]; ?>">
						<?php esc_html_e( "info menù", 'mos-theme' ); ?>
					</label>
                <div class="accordion-head-content"><?= QPC_Delivery::getProductDescription($product); ?></div>
            </div>
		<?php
		endforeach; ?>
		<div class="wrap-btn-procedi">
			<div class="wpcf7 buttons">
					<a class="btn-add-to-cart" href="<?= get_permalink( get_page_by_path( 'carrello' ) ) ?>">
							<?php esc_html_e( "Procedi con l'ordine", "mos-theme" ); ?>
					</a>
			</div>
		</div>
    </div>

  </div>
</section>


<?php get_footer(); ?>
