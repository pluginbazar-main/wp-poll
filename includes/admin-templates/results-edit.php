<?php
/**
 * Results Edit Template
 */

use WPDK\Utils;

global $wpdb;

$result_id   = isset( $_GET['id'] ) ? $_GET['id'] : '';
$result      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . LIQUIDPOLL_RESULTS_TABLE . " WHERE id = {$result_id} " ), ARRAY_A );
$poll_result = reset( $result );

$poll_id = Utils::get_args_option( 'poll_id', $poll_result );
$poll    = liquidpoll_get_poll( $poll_id );

$poll_title      = $poll->get_name();
$poll_type       = Utils::get_args_option( 'poll_type', $poll_result );
$poller_id       = Utils::get_args_option( 'poller_id_ip', $poll_result );
$poller_user     = get_user_by( 'id', $poller_id );
$polled_value    = Utils::get_args_option( 'polled_value', $poll_result );
$polled_comments = Utils::get_args_option( 'polled_comments', $poll_result );
$datetime        = strtotime( Utils::get_args_option( 'datetime', $poll_result ) );
$polled_datetime = date( "F j, Y", $datetime );
$review_title    = liquidpoll_get_results_meta( $result_id, 'review_title' );
$experience_time = strtotime( liquidpoll_get_results_meta( $result_id, 'experience_time' ) );
$experience_time = date( "F j, Y", $experience_time );

?>

<div class="liquidpoll-result-edit">
    <div class="liquidpoll-result-details">

        <div class="liquidpoll-edit-item">
            <div class="item-title">Poll</div>
            <div class="item-value"><?php echo esc_html__( $poll_title ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Poll Type</div>
            <div class="item-value"><?php echo esc_html__( $poll_type ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Polled By</div>
            <div class="item-value"><?php echo esc_html__( $poller_user->display_name ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Polled Value</div>
            <div class="item-value"><?php echo esc_html__( $polled_value ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Title</div>
            <div class="item-value"><?php echo esc_html__( $review_title ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Comments</div>
            <div class="item-value"><?php echo apply_filters( 'the_content', $polled_comments ); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Date Time</div>
            <div class="item-value"><?php echo esc_html__( $polled_datetime ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Experienced Time</div>
            <div class="item-value"><?php echo esc_html__( $experience_time ) ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Make Reply</div>
            <div class="item-value">Reviews</div>
        </div>

    </div>

    <form class="liquidpoll-result-replies" action="" method="get">
        <div class="liquidpoll-reply-box">
            <div class="textarea-wrap">
                <label for="liquidpoll-result-reply">Submit Reply</label>
                <textarea required name="result_reply" id="liquidpoll-result-reply" rows="5" placeholder="Start typing reply..."></textarea>
            </div>
            <button type="submit" class="liquidpoll-button">Send Reply</button>
        </div>
    </form>
</div>
