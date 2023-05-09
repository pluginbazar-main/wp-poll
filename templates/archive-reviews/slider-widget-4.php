<?php
/**
 * Slider Widget - 1
 */

defined( 'ABSPATH' ) || exit;

$styles = isset($args['styles']) ? $args['styles'] : '1';

if ( ! isset( $args['id'] ) ) {
	return;
}

$poll_id           = $args['id'];
$poll              = liquidpoll_get_poll( $poll_id );
$single_review_url = $poll->get_permalink();
$slider_button_styles = $args['slider_button'] ?? 1;

?>

<div class="reviews-slider style-<?php echo esc_attr($styles); ?>">
	<div class="slider-heading-wrap">
		<div class="slider-heading">
			<div class="rate-wrap">
				<span class="rate">Rated Excellent!</span>
				<div class="review-count">
					<span>Based on</span>
					<span class="count">225 Reviews</span>
				</div>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4.5 ); ?>
			</form>
		</div>
	</div>
	<div class="reviews-wrap style-4">
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
		<div class="single-review">
			<div class="review-comment-heading">
				<h2 class="comment-heading"><?php echo 'I’m impressed with the product and service!'; ?></h2>
			</div>
			<form class="review-stars">
				<?php echo liquidpoll_get_review_stars( 4 ); ?>
			</form>
			<div class="review-comment">
				<?php echo apply_filters( 'the_content', 'Couldn’t have had a better experience than I have with SUNS lifestyle from...' ); ?>
			</div>
			<hr class="liquidpoll-divider">
			<div class="user">
				<div class="user-avatar">
					<img src="<?php echo esc_url( LIQUIDPOLL_PLUGIN_URL . 'assets/images/author.png' ) ?>" alt="">
				</div>
				<div class="user-info">
					<span class="user-name">Miranda Ash-Patel</span>
					<div class="user-location">
						<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 6.5C5.82843 6.5 6.5 5.82843 6.5 5C6.5 4.17157 5.82843 3.5 5 3.5C4.17157 3.5 3.5 4.17157 3.5 5C3.5 5.82843 4.17157 6.5 5 6.5Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 11C7 9 9 7.20914 9 5C9 2.79086 7.20914 1 5 1C2.79086 1 1 2.79086 1 5C1 7.20914 3 9 5 11Z" stroke="#8D8D8D" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="location">United Kingdom</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    jQuery('.reviews-wrap.style-4').slick({
        infinite: true,
        speed: 500,
        slidesToShow: 5,
        slidesToScroll: 1,
        prevArrow: false,
        nextArrow: '<button type="button" class="style-<?php echo esc_attr( $slider_button_styles ); ?> slick-next">Next</button>'
    });
</script>

