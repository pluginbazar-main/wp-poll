<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

defined( 'ABSPATH' ) || exit;

class LIQUIDPOLL_Poll_meta {

	public function __construct() {

		$this->generate_poll_meta_box();
	}


	public function generate_poll_meta_box() {

		$prefix = 'liquidpoll_poll_meta';

		PBSettings::createMetabox( $prefix,
			array(
				'title'     => __( 'Slider Options', 'wp-poll' ),
				'post_type' => 'poll',
				'data_type' => 'unserialize',
				'context'   => 'normal',
				'nav'       => 'inline',
				'preview'   => true,
			)
		);

		foreach ( $this->get_meta_field_sections() as $section ) {
			PBSettings::createSection( $prefix, $section );
		}
	}


	public function get_meta_field_sections() {

		$poll_setting_fields = array(
			array(
				'id'    => 'settings_vote_after_deadline',
				'title' => esc_html__( 'Poll settings', 'wp-poll' ),
				'label' => esc_html__( 'Allow users to vote after deadline.', 'wp-poll' ),
				'type'  => 'switcher',
			),
			array(
				'id'    => 'settings_multiple_votes',
				'title' => ' ',
				'label' => esc_html__( 'Allow users to vote on multiple options in a single poll.', 'wp-poll' ),
				'type'  => 'switcher',
				'class' => 'padding-top-none',
			),
			array(
				'id'    => 'settings_new_options',
				'title' => ' ',
				'label' => esc_html__( 'Allow users to add new option.', 'wp-poll' ),
				'type'  => 'switcher',
				'class' => 'padding-top-none',
			),
			array(
				'id'    => 'settings_hide_timer',
				'title' => ' ',
				'label' => esc_html__( 'Hide countdown timer for this poll.', 'wp-poll' ),
				'type'  => 'switcher',
				'class' => 'padding-top-none',
			),
		);
		$poll_setting_fields = apply_filters( 'LiquidPoll/Filters/poll_setting_fields', $poll_setting_fields );

		$field_sections['general_settings'] = array(
			'title'  => __( 'General Settings', 'wp-poll' ),
			'icon'   => 'fa fa-cog',
			'fields' => array_merge( array(
				array(
					'id'      => '_type',
					'title'   => esc_html__( 'Poll type', 'wp-poll' ),
					'type'    => 'button_set',
					'options' => array(
						'poll'         => array( 'label' => esc_html__( 'Poll', 'wp-poll' ) ),
						'reaction'     => array( 'label' => esc_html__( 'Reaction', 'wp-poll' ), 'availability' => 'upcoming', ),
						'subscription' => array( 'label' => esc_html__( 'Subscription', 'wp-poll' ), 'availability' => 'upcoming', ),
						'feedback'     => array( 'label' => esc_html__( 'Feedback', 'wp-poll' ), 'availability' => 'upcoming', ),
					),
					'default' => 'poll',
				),
				array(
					'id'       => '_content',
					'title'    => esc_html__( 'Poll Content', 'wp-poll' ),
					'subtitle' => esc_html__( 'Description about this poll', 'wp-poll' ),
					'type'     => 'wp_editor',
				),
				array(
					'id'            => '_deadline',
					'title'         => esc_html__( 'Deadline', 'wp-poll' ),
					'subtitle'      => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
					'type'          => 'date',
					'autocomplete'  => 'off',
					'placeholder'   => date( 'Y-m-d' ),
					'field_options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
				),
			), $poll_setting_fields )
		);
		$field_sections['poll_options']     = array(
			'title'  => __( 'Poll Options', 'wp-poll' ),
			'icon'   => 'fa fa-th-large',
			'fields' => array(
				array(
					'id'              => 'poll_meta_options',
					'title'           => esc_html__( 'Options', 'wp-poll' ),
					'subtitle'        => esc_html__( 'Add poll options here. You can skip using media if you do not need this.', 'wp-poll' ),
					'type'            => 'repeater',
					'button_title'    => esc_html__( 'Add option', 'wp-poll' ),
					'disable_actions' => array( 'clone' ),
					'fields'          => array(
						array(
							'id'    => 'label',
							'title' => esc_html__( 'Label', 'wp-poll' ),
							'type'  => 'text',
						),
						array(
							'id'           => 'thumb',
							'title'        => esc_html__( 'Thumbnail', 'wp-poll' ),
							'type'         => 'media',
							'preview_size' => 'full',
							'library'      => 'image',
							'url'          => false,
						),
//						array(
//							'id'      => 'shortcode',
//							'title'   => esc_html__( 'Shortcode', 'wp-poll' ),
//							'type'    => 'text',
//							'class'   => 'hide-input-field',
//							'default' => '',
//							'desc'    => sprintf( '<span class="shortcode tt--hint tt--top" aria-label="Click to Copy">[poller_list poll_id="%s" option_id="%s"]</span>', '', '' )
//						),
					),
				),
				array(
					'id'    => 'hide_option_labels',
					'title' => esc_html__( 'Hide labels', 'wp-poll' ),
					'label' => esc_html__( 'Hide labels of all options.', 'wp-poll' ),
					'type'  => 'switcher',
				),
			),
		);
		$field_sections['poll_styling']     = array(
			'title'  => __( 'Styling', 'wp-poll' ),
			'icon'   => 'fa fa-bolt',
			'fields' => array(
				array(
					'id'       => '_theme',
					'title'    => esc_html__( 'Theme Style', 'wp-poll' ),
					'subtitle' => esc_html__( 'By default it will apply from global settings.', 'wp-poll' ),
					'type'     => 'select',
					'options'  => array(
						'default' => array(
							'label' => esc_html__( 'Default (Global settings)', 'wp-poll' ),
						),
						'1'       => array(
							'label' => esc_html__( 'Theme One', 'wp-poll' ),
						),
						'2'       => array(
							'label' => esc_html__( 'Theme Two', 'wp-poll' ),
						),
						'3'       => array(
							'label' => esc_html__( 'Theme Three', 'wp-poll' ),
						),
						'4'       => array(
							'label'        => esc_html__( 'Theme Four (Pro)', 'wp-poll' ),
							'availability' => 'pro',
						),
						'x'       => array(
							'label'        => esc_html__( '10+ are coming soon', 'wp-poll' ),
							'availability' => 'upcoming',
						),
					),
					'default'  => 'default',
				),
			),
		);

		return apply_filters( 'LiquidPoll/Filters/poll_meta_field_sections', $field_sections );
	}
}

new LIQUIDPOLL_Poll_meta();