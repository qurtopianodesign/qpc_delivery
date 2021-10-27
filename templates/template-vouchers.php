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
	<div class="" data-scroll data-scroll-repeat data-scroll-call="stickyHeader">
    <div class="" data-scroll data-scroll-speed="2" >
        <h3 class="o-h1"><?php the_title(); ?></h3>
    </div>
		<div data-scroll data-scroll-speed="2" class="wrap-prenota" >
			 <?php get_template_part( 'loop', 'page' ); ?>
		</div>
    <div data-scroll data-scroll-speed="4" class=" accordion-head-wrap" >
		<?php
		$products = $QPC_Delivery->getVouchers();
		foreach ( $products as $product ): 
		
		$name = json_decode($product['name'], true);

		if( $name != NULL ){
			$name = $name[ICL_LANGUAGE_CODE];
		} else {
			$name = $product['name'];
		}
	
		?>

            <div class=" voucher wpcf7 accordion-head">
							<div class="wrap-qpc-price">
								<span class="qpc-price"><?= $name; ?></span>
                    <div class="wrap-qpc-price-sub">
                        <p class="qpc-price"><?=  str_replace(",00", "", number_format( $product["price"], 2, ",", "" )); ?> €</p>
                        <a class="delivery-add-to-cart wpcf7-submit btn icon-qpc-delivery-15"
                                delivery-price="<?= $product['price']; ?>"
                                delivery-productId="<?= $product['id']; ?>"
                                delivery-qty="1"><?php esc_html_e( "Aggiungi al carrello", 'mos-theme' ); ?>
                        </a>
                    </div>
				</div>
					<?php
					$productDetails = $QPC_Delivery->getProduct( $product['slug'] );

					foreach ( $productDetails['attributes'] as $attribute ) : ?>
						<div class="wrap-qpc-price">
								<?php
								$title = json_decode($attribute['title'], true);

								if( $title != NULL ){
									$title = $title[ICL_LANGUAGE_CODE];
								} else {
									$title = $attribute['title'];
								}
								?>
								<span class="qpc-price"><?= $name; ?> + <?= $title; ?></span>
								<div class="wrap-qpc-price-sub">
								<p class="qpc-price"><?=  str_replace(",00", "", number_format( $product['price'] + $attribute["price"], 2, ",", "" )); ?>
										€
								</p>
								<a class="delivery-add-to-cart wpcf7-submit btn icon-qpc-delivery-15"
									delivery-productId="<?= $product['id']; ?>"
									delivery-price="<?= $product['price']; ?>"
									delivery-attributeId="<?= $attribute["id"]; ?>"
									delivery-attributeTheId="<?= $attribute["attribute_id"]; ?>"
									delivery-attributePrice="<?= $attribute["price"]; ?>"
									delivery-attributeValue="<?= $title; ?>"
									delivery-qty="1"><?php esc_html_e( "Aggiungi al carrello", 'mos-theme' ); ?>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
					<input type="radio" id="rd_<?= $product["id"]; ?>" name="rd" class="accordion-radio"/>

					<label class="accordion-head-label" for="rd_<?= $product["id"]; ?>">
						<?php esc_html_e( "info menù", 'mos-theme' ); ?>
					</label>
                <div class="accordion-head-content"><?= $product["description"]; ?></div>
								<p>	<?php esc_html_e( "*I menu sono comprensivi di coperto, acqua e caffè, il prezzo si intende a persona.", 'mos-theme' ); ?></p>
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
