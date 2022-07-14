<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Elementor accordion widget.
 *
 * Elementor widget that displays a collapsible display of content in an
 * accordion style, showing only one item at a time.
 *
 * @since 1.0.0
 */
class DroowButton extends \Elementor\Widget_Base
{


    /**
     * Get widget name.
     *
     * Retrieve accordion widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'dsn_button';
    }


    /**
     * Get widget title.
     *
     * Retrieve accordion widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __( 'Droow Button', 'droow' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve accordion widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-button';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since 2.1.0
     * @access public
     *
     */
    public function get_keywords()
    {
        return [ 'dsn', 'droow', 'Button' ];
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return [ 'droow_cat' ];

    }

    /**
     * Register accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {

        $this->start_controls_section( "content_button", [
            'label' => __( 'Content', 'droow' )
        ] );

        $this->add_control(
            'Button_style',
            [
                'label'   => __( 'Button Style', 'droow' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'link-custom',
                'options' => [
                    'link-custom' => __( 'Style 1', 'droow' ),
                    'button'      => __( 'Style 2', 'droow' ),

                ],
            ]
        );

        $this->add_control(
            'bg_Button',
            [
                'label'   => __( 'Background Button', 'droow' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''                   => __( 'Default', 'droow' ),
                    'background-main'    => __( 'Background Main', 'droow' ),
                    'background-section' => __( 'Background Section', 'droow' ),

                ],
            ]
        );


        $this->add_control(
            'popup_video',
            [
                'label'        => __( 'Popup Video', 'droow' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'droow' ),
                'label_off'    => __( 'No', 'droow' ),
                'return_value' => 'vid',
                'default'      => '',
            ]
        );
        $this->add_control(
            'widget_title',
            [
                'label'       => __( 'Title', 'droow' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'title button', 'droow' ),
                'placeholder' => __( 'Type your title here', 'droow' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'         => __( 'Link', 'droow' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'droow' ),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label'        => __( 'Full Width', 'droow' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'droow' ),
                'label_off'    => __( 'No', 'droow' ),
                'return_value' => 'w-100',
                'default'      => '',
            ]
        );
        $this->add_control(
            'align_button',
            [
                'label'   => __( 'Align Button', 'droow' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text-left'   => [
                        'title' => __( 'Left', 'droow' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'text-center' => [
                        'title' => __( 'Center', 'droow' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'text-right'  => [
                        'title' => __( 'Right', 'droow' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'w-100'       => [
                        'title' => __( 'Justify', 'droow' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'text-left'
            ]
        );


        $this->end_controls_section();

        $this->styleTab();


    }


    private function styleTab()
    {
        $this->start_controls_section( "style_button", [
            'label' => __( 'Animate', 'droow' )
        ] );

        $this->add_control(
            'fade_up',
            [
                'label'        => __( 'Fade Up', 'droow' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'droow' ),
                'label_off'    => __( 'No', 'droow' ),
                'return_value' => '1',
                'default'      => '',
            ]
        );

        $this->add_control(
            'parallax',
            [
                'label'        => __( 'Parallax', 'droow' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'droow' ),
                'label_off'    => __( 'No', 'droow' ),
                'return_value' => '1',
                'default'      => '',
            ]
        );


        $this->end_controls_section();

    }

    /**
     * Render accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'widget_title', 'none' );
        $align = droow_acf_option_array( $settings, 'align_button', 'text-left' );
        $fade_up = droow_acf_option_array( $settings, 'fade_up' );
        $parallax = droow_acf_option_array( $settings, 'parallax' );


        $this->add_render_attribute( 'section_button', 'class', droow_acf_option_array( $settings, 'Button_style', 'link-custom' ) );
        $this->add_render_attribute( 'section_button', 'class', $align );
        if ( $fade_up )
            $this->add_render_attribute( 'section_button', 'data-dsn-animate', 'up' );

        $this->add_link_attributes( 'link', $settings[ 'link' ] );

        $this->add_render_attribute( 'link', 'class', droow_acf_option_array( $settings, 'bg_Button', 'link-custom' ) );
        $this->add_render_attribute( 'link', 'class', droow_acf_option_array( $settings, 'full_width', '' ) );
        $this->add_render_attribute( 'link', 'class', droow_acf_option_array( $settings, 'popup_video', '' ) );

        if ( $parallax )
            $this->add_render_attribute( 'link', [
                'class'    => 'image-zoom',
                'data-dsn' => 'parallax',
            ] );


        if ( $align === 'w-100' )
            $this->add_render_attribute( 'link', 'class', 'w-100' );

        ?>

        <div <?php $this->print_render_attribute_string( 'section_button' ) ?>>

            <a <?php $this->print_render_attribute_string( 'link' ) ?> >
                <span <?php $this->print_render_attribute_string( 'widget_title' ) ?>>
                    <?php echo droow_acf_option_array( $settings, 'widget_title' ) ?>
                </span>
            </a>
        </div>


        <?php
    }


}
