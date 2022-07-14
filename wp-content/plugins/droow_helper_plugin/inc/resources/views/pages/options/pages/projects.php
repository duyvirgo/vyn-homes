<?php


acf_add_local_field_group(array(
    'key' => 'group_5d7ad8d4858c6',
    'title' => 'Option Post Project',
    'fields' => array(
        array(
            'key' => 'field_5d7ad8d4944fb',
            'label' => 'option',
            'name' => 'option',
            'type' => 'clone',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'clone' => array(
                0 => 'field_5d277d7783e23',
                1 => 'field_5d7816181c1b8',
                2 => 'field_5d7ad7627cc90',
                3 => 'field_5d91fe88784b8',
                4 => 'field_5d922f97bd74d',
                5 => 'field_5d91fc272305e',
                6 => 'field_5d8b599a4e186',
                7 => 'field_5d7adee424fbd',
                8 => 'field_5d7ae2e910093',
                9 => 'field_5d7adef224fbe',
                10 => 'field_5d7adf5124fc1',
                11 => 'field_5d7adf0224fbf',
                12 => 'field_5d27836b5f452',
                13 => 'field_5d7819985dec4',
                14 => 'field_5d2784225f454',
            ),
            'display' => 'seamless',
            'layout' => 'block',
            'prefix_label' => 0,
            'prefix_name' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'miao-portfolio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
));


acf_add_local_field_group( array(
    'key'                   => 'group_5d89472fc0336' ,
    'title'                 => 'Project Image' ,
    'fields'                => array(
        array(
            'key'               => 'field_5d894b498dc9a' ,
            'label'             => 'Image Size' ,
            'name'              => 'image_size' ,
            'type'              => 'select' ,
            'instructions'      => '' ,
            'required'          => 0 ,
            'conditional_logic' => 0 ,
            'wrapper'           => array(
                'width' => '' ,
                'class' => '' ,
                'id'    => '' ,
            ) ,
            'choices'           => array(
                'full'             => 'Full' ,
                'mavy_height_work' => 'Mavy Vertical' ,
            ) ,
            'default_value'     => array(
                0 => 'full' ,
            ) ,
            'allow_null'        => 0 ,
            'multiple'          => 0 ,
            'ui'                => 0 ,
            'return_format'     => 'value' ,
            'ajax'              => 0 ,
            'placeholder'       => '' ,
        ) ,
        array(
            'key'               => 'field_5d8947458602d' ,
            'label'             => 'Backgrond Image 2' ,
            'name'              => 'backgrond_image_2' ,
            'type'              => 'image' ,
            'instructions'      => '' ,
            'required'          => 0 ,
            'conditional_logic' => 0 ,
            'wrapper'           => array(
                'width' => '' ,
                'class' => '' ,
                'id'    => '' ,
            ) ,
            'return_format'     => 'id' ,
            'preview_size'      => 'medium' ,
            'library'           => 'all' ,
            'min_width'         => '' ,
            'min_height'        => '' ,
            'min_size'          => '' ,
            'max_width'         => '' ,
            'max_height'        => '' ,
            'max_size'          => '' ,
            'mime_types'        => '' ,
        ) ,
    ) ,
    'location'              => array(
        array(
            array(
                'param'    => 'post_type' ,
                'operator' => '==' ,
                'value'    => 'miao-portfolio' ,
            ) ,
        ) ,
    ) ,
    'menu_order'            => 0 ,
    'position'              => 'side' ,
    'style'                 => 'default' ,
    'label_placement'       => 'top' ,
    'instruction_placement' => 'label' ,
    'hide_on_screen'        => '' ,
    'active'                => true ,
    'description'           => '' ,
) );