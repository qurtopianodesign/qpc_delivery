<?php
/*
 * Template Name: archivio menu
 // questo corrisponde a /menu/
 */
/* uso l'header personalizzato */
define( 'mos', false ); // determino il tema e rimuovo l'hedear di dafault presente nella root
require( 'template-parts/header/no-header.php' );
$product = null;
$category = null;
if ( isset( $wp_query->query_vars['product_slug'] ) ) : $product = QPC_Delivery::getProduct( $product_slug ); endif;
if ( isset( $wp_query->query_vars['category_slug'] ) ) : $category = QPC_Delivery::getCategory( $category_slug ); endif;
?>

<section class="wrap-delivery-breadcrumbs p-b-0" data-scroll-section>
	<?=QPC_Delivery::getBreadcrumb($category, $product); ?>
</section>
	<?php
	global $wp_query;

	//singolo prodotto - es. "Conoscersi" /menu/degustazioni/conoscersi
	if ( isset( $wp_query->query_vars['product_slug'] ) ) :
		?>
        <nav class="detail-menu flex alignItemsCenter" data-scroll-section>
            <div class="block-left m-l-100">
                <div class="" data-scroll data-scroll-offset="200">
                    <h3 class="o-h1"><?= QPC_Delivery::getProductName($product); //$product["name"]; ?></h3>
                    <div class="text-mw-600">
						<?= QPC_Delivery::getProductDescription($product); //$product["description"]; ?>
                        <?php if ($product['price'] > 0) : ?>
                        <p><span class="qpc-price"><?= str_replace(",00", "", number_format($product["price"], 2, ",", "")); ?> €</span>
                        <?php endif; ?>
                        <!-- <p class="no-barba delivery-add-to-cart" delivery-price="<?=$product['price']; ?>" delivery-productId="<?=$product['id']; ?>" delivery-qty="1"><?php esc_html_e( 'Regala il menù', 'mos-theme' ); ?> <span class="icon-qpc-prenota"></span></p> -->
                        <?php if (trim(QPC_Delivery::getAttributeValuesHTML($product['attributes'])) != '') : ?>
                        <p><?php esc_html_e("Abbinamento vini", "mos-theme");?></p>
                        <p>
                            <?=QPC_Delivery::getAttributeValuesHTML($product['attributes']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="cont-swiper-container">
                <div class="swiper-container swiper1 swiper-home " data-scroll>
                    <div class="swiper-wrapper">
				        <?php foreach ( $product['images'] as $image ) : ?>
                            <div class="swiper-slide">
                                <img src="<?= QPC_DELIVERY_URL.$image['resize_1']; ?>"
                                     alt="<?= $product['name'] ?>"/>
                            </div>
				        <?php endforeach; ?>
                    </div>
                </div>
                <div class="swiper-pagination swiper-pagination1 swiper-pagination-white"></div>
            </div>
        </nav>
	<?php
    //singola categoria - es. "Antipasti" /menu/antipasti/
    elseif ( isset( $wp_query->query_vars['category_slug'] ) ) :
	//	$category_slug = $wp_query->query_vars['category_slug'];
	//	$category = QPC_Delivery::getCategory( $category_slug );
		$products = QPC_Delivery::getCategoryProducts( $category_slug ); ?>
            <nav class="detail-menu flex alignItemsCenter" data-scroll-section>
                <div class="block-left position-abs m-l-100">
                    <div class="" data-scroll data-scroll-offset="200">
                        <h3 class="o-h1">
                            <?= QPC_Delivery::getCategoryName($category["category"]); ?>
                        </h3>
                        <div class="text-mw-600">
                            <ul>
                                <?php foreach ( $products as $product ): ?>
                                    <li>
                                        <span><?= QPC_Delivery::getProductName($product); //$product["name"]; ?></span>
                                        <span class="qpc-price"><?= str_replace(",00", "", number_format($product["price"], 2, ",", "")); ?> €</span>
                                        <!-- <p class="no-barba delivery-add-to-cart" delivery-price="<?=$product['price']; ?>" delivery-productId="<?=$product['id']; ?>" delivery-qty="1" ><?php esc_html_e( 'Prenota', 'mos-theme' ); ?> <span
                                                    class="icon-qpc-prenota"></span></p> -->
                                    </li>
                                <?php
                                endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="cont-swiper-container">
                    <div class="swiper-container swiper1 swiper-home " data-scroll>
                        <div class="swiper-wrapper">
                            <?php $images = QPC_Delivery::prepareCategoryGalleryData($products, $category); ?>
                            <?php foreach ( $images as $image ) : ?>
                                <div class="swiper-slide">
                                    <img src="<?= QPC_DELIVERY_URL.$image; ?>"
                                        alt="<?php bloginfo(); ?>"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination1 swiper-pagination-white"></div>

                </div>

            </nav>
	<?php
	else :
        // Listing categorie + degustazioni - /menu/
	  include "template-parts/elements/blocco-swiper.php";
		$products = QPC_Delivery::getCategories();  ?>
        <nav class="qpc-typo-menu" data-scroll-section id="intro">
			<?php
			$count = 0;
			foreach ( $products as $product ):?>

                <div class="qpc-typo-menu__item">
                    <a class="qpc-typo-menu__item-link mos<?= $count++ ?>"
                       href="<?= QPC_Delivery::getURL( $product ); ?>"><span><?=  QPC_Delivery::getProductName($product); //$product["name"]; ?></span></a>
                    <?php if ($image = QPC_Delivery::getImageForMenuListing($product)) :?>
                    <img class="qpc-typo-menu__item-img" src="<?=$image ?>" alt="<?=  QPC_Delivery::getProductName($product); //$product['name']; ?>"/>
                    <?php endif; ?>
                    <div class="rolling">
                        <div class="rolling__inner" aria-hidden="true">
                            <span><?=  QPC_Delivery::getProductName($product); //$product["name"]; ?></span>
                            <span><?=  QPC_Delivery::getProductName($product); //$product["name"]; ?></span>
                            <span><?=  QPC_Delivery::getProductName($product); //$product["name"]; ?></span>
                            <span><?=  QPC_Delivery::getProductName($product); //$product["name"]; ?></span>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
			<p class="info-add">(Tutti i nostri prodotti che vengono serviti crudi o appena scottati vengono sottoposti ad abbattimento rapido<br>
di temperatura a -25° per almeno 24 ore per garantire freschezza e sicurezza, come previsto dalle nuove normative igienico sanitarie.)</p>
        </nav>
	<?php endif; ?>

<?php
// Ripristina Query & Post Data originali
//wp_reset_query();
//wp_reset_postdata();
?>
<?php get_footer(); ?>
