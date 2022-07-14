<?php

Kirki::add_section( $dsn_section, array(
    'panel' => $dsn_panel,
    'title' => esc_html__( 'Color', 'droow' ),
    'icon' =>'dashicons-admin-customizer'
) );

$dsn_color = [
    'background_color'           => [
        'label'   => __( 'background Color Dark', 'droow' ),
        'default' => '#000',
    ],
    'assistant-background-color' => [
        'label'   => __( 'The assistant background Color Dark', 'droow' ),
        'default' => '#090909',
    ],
    'heading-color'              => [
        'label'   => __( 'Heading Color Dark', 'droow' ),
        'default' => '#fff',
    ],
    'body-color'                 => [
        'label'   => __( 'Body Color Dark', 'droow' ),
        'default' => 'rgba(255, 255, 255, 0.69)',
    ],
    'border-color'               => [
        'label'   => __( 'Border Color Dark', 'droow' ),
        'default' => 'rgba(112, 112, 112, 0.5)',
    ]
];

$dsn_color_light = [
    'background_color-light'           => [
        'label'   => __( 'background Color Light', 'droow' ),
        'default' => '#f9f9f9',
    ],
    'assistant-background-color-light' => [
        'label'   => __( 'The assistant background Color Light', 'droow' ),
        'default' => '#e6e6e6',
    ],
    'heading-color-light'              => [
        'label'   => __( 'Heading Color Light', 'droow' ),
        'default' => '#000',
    ],
    'body-color-light'                 => [
        'label'   => __( 'Body Color Light', 'droow' ),
        'default' => '#0009',
    ],
    'border-color-light'               => [
        'label'   => __( 'Border Color Light', 'droow' ),
        'default' => '#bebebe',
    ]
];

Kirki::add_field( $dsn_section, [
    'type'     => 'switch',
    'settings' => 'dsn_custom_color',
    'label'    => esc_html__( 'Custom Color Theme', 'droow' ),
    'section'  => $dsn_section,
    'default'  => '',


    'choices' => [
        'on'  => esc_html__( 'Enable', 'droow' ),
        'off' => esc_html__( 'Disable', 'droow' ),
    ],
] );

foreach ( $dsn_color as $key => $value ):
    Kirki::add_field( $dsn_section, [
        'type'      => 'color',
        'settings'  => $key,
        'label'     => $value[ 'label' ],
        'section'   => $dsn_section,
        'default'   => $value[ 'default' ],
        'transport' => 'postMessage',

        'js_vars'            => [
            [
                'element'  => ':root',
                'function' => 'style',
                'property' => '--dsn-' . $key,
            ]
        ], 'active_callback' => [
            [
                'setting'  => 'dsn_custom_color',
                'operator' => '==',
                'value'    => '1',
            ]
        ],
    ] );
endforeach;

droow_custom_Label( $dsn_section, __( 'Version Light', 'droow' ) , '', [
    [
        'setting'  => 'dsn_custom_color',
        'operator' => '==',
        'value'    => '1',
    ]
] );


foreach ( $dsn_color_light as $key => $value ):
    Kirki::add_field( $dsn_section, [
        'type'      => 'color',
        'settings'  => $key,
        'label'     => $value[ 'label' ],
        'section'   => $dsn_section,
        'default'   => $value[ 'default' ],
        'transport' => 'postMessage',

        'js_vars'            => [
            [
                'element'  => ':root',
                'function' => 'style',
                'property' => '--dsn-' . $key,
            ]
        ], 'active_callback' => [
            [
                'setting'  => 'dsn_custom_color',
                'operator' => '==',
                'value'    => '1',
            ]
        ],
    ] );
endforeach;

if ( get_theme_mod( 'dsn_custom_color' ) )
    add_action( 'wp_head', function () use ( $dsn_color, $dsn_color_light ) { ?>
        <style id="droow_style_color">
            :root {
            <?php
            foreach ($dsn_color as $key => $value):
                printf('--dsn-%s:%s;' , $key , get_theme_mod($key , $value['default']));
             endforeach;
             foreach ($dsn_color_light as $key => $value):
                printf('--dsn-%s:%s;' , $key , get_theme_mod($key , $value['default']));
             endforeach;
            ?>
            }
        </style>
    <?php }, 100 );