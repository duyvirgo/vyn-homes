<?php 
/*
Widget Name: Search Filter
Description: Search Filter
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use TheplusAddons\Theplus_Element_Load;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class ThePlus_Search_Filter extends Widget_Base {

	public function get_name() {
		return 'tp-search-filter';
	}

    public function get_title() {
        return esc_html__('WP Filters', 'theplus');
    }

    public function get_icon() {
        return 'fa fa-sort-size-up-alt theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-search-filter');
    }
		
	public function get_keywords() {
		return ['search','filter','search filter','product filter','wp filter'];
	}
	
    protected function _register_controls() {

		$this->start_controls_section('FilterArea_section',
			[
				'label' => esc_html__('Filter Area','theplus'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        $repeater = new \Elementor\Repeater();

        $repeater->add_control('filteroption',
			[
				'label'=>esc_html__('Filter Type','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'wpfilter',
				'options'=>[
					'wpfilter'=>esc_html__('WP Filter','theplus'),
                    'Woofilter'=>esc_html__('Woo Filter','theplus'),
                    'extrafilter'=>esc_html__('Filter Meta','theplus'),
				],
			]
		);
        $repeater->add_control('WpFilterType',
            [
                'label'=>esc_html__('Select Source','theplus'),
                'type'=>Controls_Manager::SELECT,
                'default'=>'',
                'options'=>[
                    ''=>esc_html__('Select Source','theplus'),
                    'alphabet'=>esc_html__('Alphabet Filter','theplus'),
                    'checkbox' => esc_html__('CheckBox','theplus'),
                    'date'=>esc_html__('Date Picker','theplus'),
                    'drop_down' => esc_html__('Drop Down','theplus'),
                    'radio' => esc_html__('Radio Button','theplus'),
                    'range' => esc_html__('Range Slider','theplus'),
                    'search'=>esc_html__('Search Input','theplus'),
                    'tabbing'=>esc_html__('Tabbing Filter','theplus'),
                ],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                ],
            ]
        );
        $repeater->add_control('WooFilterType',
			[
				'label'=>esc_html__('Select Source','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'',
				'options'=>[
					''=>esc_html__('Select Source','theplus'),
                    'button'=>esc_html__('Button','theplus'),
                    'color'=>esc_html__('Color','theplus'),
                    'image'=>esc_html__('Image','theplus'),
                    'rating'=>esc_html__('Rating','theplus'),
				],
                'condition'=>[
					'filteroption'=>'Woofilter',
				],
			]
		);
        $repeater->add_control('ExFilterType',
            [
                'label'=>esc_html__('Select Source','theplus'),
                'type'=>Controls_Manager::SELECT,
                'default'=>'filter_tag',
                'options'=>[
                    'filter_tag'=>esc_html__('Filter Tag','theplus'),
                    'filter_reset'=>esc_html__('Filter Reset','theplus'),
                    'total_results'=>esc_html__('Total Search Results','theplus'),
                ],
                'condition' => [
                    'filteroption'=>'extrafilter',
                ],
            ]
        );

        $repeater->add_control('ContentType',
			[
				'label' => esc_html__('Select Type','theplus'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
                    ''=>esc_html__('Select Source','theplus'),
					'taxonomy'=>esc_html__('Taxonomy','theplus'),
                    'acf_conne'=>esc_html__('ACF connection','theplus'),
				],
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
				],
                'conditions'=>[
					'relation'=>'or',
					'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'in','value'=>['checkbox','date','drop_down','search','tabbing','radio','range']],
                        ['name'=>'WooFilterType','operator'=>'in','value'=>['color','image','button','rating']],
					],
				],
			]
		);
        $repeater->add_control('TaxonomyType',
			[
				'label'=>esc_html__('Select Taxonomy','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'',
                'options'=>theplus_get_post_taxonomies(),
                'condition'=>[
                    'filteroption'=>['wpfilter','Woofilter'],
                    'ContentType'=>'taxonomy'
				],
                'conditions' => [
					'relation'=>'or',
					'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'in','value'=>['checkbox','date','drop_down','tabbing','radio']],
                        ['name'=>'WooFilterType','operator'=>'in','value'=>['color','image','button']],
					],
				],
			]
		);
        $repeater->add_control('pAttr',
			[
				'label'=>esc_html__('Select Attributes','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'',
				'options'=>theplus_get_woocommerce_taxonomies(),
                'condition'=>[
                    'filteroption'=>['Woofilter'],
                    'ContentType'=>'taxonomy',
					'TaxonomyType'=>'product_attr',
				],
                'conditions'=>[
					'relation'=>'or',
					'terms'=>[
                        ['name'=>'WooFilterType','operator'=>'in','value'=>['color','image','button']],
					],
				],
			]
		);
        $repeater->add_control('acfKey',
            [
                'label'=>__('ACF Key','theplus'),
                'type'=>Controls_Manager::TEXT,
                'default'=>__('','theplus'),
                'placeholder'=>__('Enter ACF Key','theplus'),
                'condition'=>[
                    'filteroption'=>['wpfilter','Woofilter'],
                    'ContentType'=>'acf_conne',
                ],
                'conditions'=>[
                    'relation'=>'or',
                    'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'in','value'=>['checkbox','date','drop_down','search','tabbing','radio','range']],
                        ['name'=>'WooFilterType','operator'=>'in','value'=>['color','image','button']],
                    ],
                ],
            ]
        );
        $repeater->add_control('ColorPickerKey',
            [
                'label'=>__('ACF ColorPicker','theplus'),
                'type'=>Controls_Manager::TEXT,
                'default'=>__('','theplus'),
                'placeholder'=>__('Enter ACF Key','theplus'),
                'condition'=>[
                    'filteroption'=>'Woofilter',
                    'ContentType'=>'acf_conne',
                ],
                'conditions'=>[
                    'relation'=>'or',
                    'terms'=>[
                        ['name'=>'WooFilterType','operator'=>'===','value'=>'color'],
                    ],
                ],
            ]
        );

        $repeater->add_control('placeholder',
			[
				'label'=>__('Placeholder Text','theplus'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('','theplus'),
				'placeholder'=>__('Type your keyword to search...','theplus'),
                'condition'=>[
					'filteroption'=>'wpfilter',
                    'WpFilterType'=>['search']
				],
			]
		);
        $repeater->add_control('GenericFilter',
            [
                'label'=>__('Generic Filters','theplus'),
                'type'=>Controls_Manager::POPOVER_TOGGLE,
                'label_off'=>__('Default','theplus'),
                'label_on'=>__('Custom','theplus'),
                'return_value'=>'yes',
                'default'=>'yes',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'search',
                ],
            ]
        );
        $repeater->start_popover();
        $repeater->add_control('haddingGF',
            [
                'label'=>__('Generic Filters','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $repeater->add_control('sintitle',
            [
                'label'=>esc_html__('Search in Title','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'yes',
            ]
        );
        $repeater->add_control('sincontent',
            [
                'label'=>esc_html__('Search in Content','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'',
            ]
        );
        $repeater->add_control('sinname',
            [
                'label'=>esc_html__('Search in Name','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'',
            ]
        );
        $repeater->add_control('sinexcerpt',
            [
                'label'=>esc_html__('Search in Excerpt','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'',
            ]
        );
        $repeater->add_control('sincategory',
            [
                'label'=>esc_html__('Search in Category','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'',
            ]
        );
        $repeater->add_control('sinTags',
            [
                'label'=>esc_html__('Search in Tags','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>esc_html__('Show','theplus'),
                'label_off'=>esc_html__('Hide','theplus'),
                'default'=>'',
            ]
        );
        $repeater->add_control('SearchMatch',
            [
                'label'=>__('Search Type','theplus'),
                'type'=>Controls_Manager::SELECT,
                'multiple'=>true,
                'options'=>[
                    'otheroption'=>__('Default','theplus'),
                    'fullMatch'=>__('Full Match','theplus'),
                ],
                'default'=>'otheroption',
            ]
        );
        $repeater->end_popover();

        $repeater->add_control('lableDisable',
			[
				'label'=>__('Show lable','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'yes',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['date'],
                ],
			]
		);
        $repeater->add_control('lableOne_date',
			[
				'label'=>__('First Lable','theplus'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('Start','theplus'),
				'placeholder'=>__('Enter First Lable','theplus'),
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['date'],
                ],
			]
		);
        $repeater->add_control('lableTwo_date',
			[
				'label'=>__('Second Lable','theplus'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('End','theplus'),
				'placeholder'=>__('Enter Second Lable','theplus'),
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['date'],
                ],
			]
		);
        $repeater->add_control('lableStyleDate',
			[
				'label'=>__('Border Style','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default' =>'default',
				'options'=>[
					'default'=>__('Default','theplus'),
					'inline'=>__('Inline','theplus'),
				],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['date'],
                ],
			]
		);

        $repeater->add_control('AlphabetType',
			[
				'label'=>__('Alphabet Type','theplus'),
				'type'=>Controls_Manager::SELECT2,
				'multiple'=>true,
				'options'=>[
					'alphabet'=>__('Alphabet (A-Z)','theplus'),
					'number'=>__('Number (0-9)','theplus')
				],
				'default'=>['alphabet'],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'alphabet',
				],
			]
		);

        $repeater->add_control('TabbingContent',
			[
				'label'=>__('Select Media','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'none',
				'options'=> [
					'none'=>__('None','theplus'),
					'icon'=>__('Icon','theplus'),
					'image'=>__('Image','theplus'),
				],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'tabbing',
				],
			]
		);
        $repeater->add_control('TabbingIconlib',
            [
                'label'=>__('Icon Library','theplus'),
                'type'=>Controls_Manager::ICONS,
                'condition' => [
                    'WpFilterType'=>'tabbing',
                    'TabbingContent'=>'icon',
                ],
                'default'=>[
                    'value'=>'fa fa-bank',
                    'library'=>'solid',
                ],
                'condition'=>[
                    'WpFilterType'=>'tabbing',
                    'TabbingContent'=>'icon',
				],
            ]
        );
        $repeater->add_control('TabbingImage',
			[
				'label'=>__('Choose Image','theplus'),
				'type'=>Controls_Manager::MEDIA,
				'default'=>[
					'url'=>Utils::get_placeholder_image_src(),
				],
                'condition'=>[
                    'WpFilterType'=>'tabbing',
                    'TabbingContent'=>'image',
				],
			]
		);

		$repeater->add_control('DDtitle',
			[
				'label'=>__('Default Title','theplus'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('All Data','theplus'),
				'placeholder'=>__('Enter Default','theplus'),
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'ContentType'=>'taxonomy',
                    'WpFilterType'=>'drop_down',
                ],
			]
		);
        $repeater->add_control('layout_style',
			[
				'label'=>__('layout','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'style-1',
				'options'=>[
					'style-1'=>__('Style-1','theplus'),
					'style-2'=>__('Style-2','theplus'),
				],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    // 'ContentType'=>'taxonomy',
                    'WpFilterType'=>['checkbox','drop_down','radio'],
                ],
			]
		);
        $repeater->add_control('Imageshow',
            [
                'label'=>__('Show Image','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'ContentType'=>'taxonomy',
                    'WpFilterType'=>['checkbox','drop_down','radio','tabbing'],
                ],
            ]
        );
        $repeater->add_control('WooFiltersSort',
            [
                'label'=>__('Woo Sorting','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'ContentType'=>'taxonomy',
                ],
                'conditions'=>[
                    'relation'=>'AND',
                    'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>''],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'alphabet'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'checkbox'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'date'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'radio'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'range'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'search'],
                    ],
                ],
            ]
        );
        $repeater->add_control('WooFiltersSelect',
			[
				'label'=>__('Select','theplus'),
				'type'=>Controls_Manager::SELECT2,
				'multiple'=>true,
				'options'=>[
					'featured'=>__('Featured','theplus'),
					'on_sale'=>__('On sale','theplus'),
                    'top_sales'=>__('Top Sales','theplus'),
                    'instock'=>__('In Stock','theplus'),
                    'outofstock'=>__('Out of Stock','theplus'),
				],
				'default'=>['on_sale'],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'ContentType'=>'taxonomy',
                    'WooFiltersSort'=>'yes'
                ],
                'conditions'=>[
                    'relation'=>'AND',
                    'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>''],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'alphabet'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'checkbox'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'date'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'radio'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'range'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'search'],
                    ],
                ],
			]
		);
        $repeater->add_control('showCount',
			[
				'label'=>__('Show Count','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__( 'Show', 'theplus' ),
				'label_off'=>__( 'Hide', 'theplus' ),
				'return_value'=>'yes',
				'default'=>'yes',
                'condition'=>[
                    'filteroption' => ['wpfilter','Woofilter'],
                    'ContentType'=>'taxonomy'
				],
                'conditions'=>[
                    'relation'=>'AND',
                    'terms'=>[
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'alphabet'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'date'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'search'],
                        ['name'=>'WpFilterType','operator'=>'!==','value'=>'range'],
                    ],
                ],
			]
		);
        $repeater->add_control('showtickIcon',
			[
				'label'=>__('Show Tick icon','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'tabbing',
                ],
			]
		);
        $repeater->add_control('showChild',
			[
				'label'=>__('Show Child Category','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'yes',
                'condition'=>[
                    'filteroption'=>['wpfilter'],
                    'ContentType'=>'taxonomy',
				],
                'conditions' => [
					'relation' => 'or',
					'terms' => [						
						['name' => 'WpFilterType', 'operator' => '===', 'value' => 'checkbox'],
                        ['name' => 'WpFilterType', 'operator' => '===', 'value' => 'radio'],
					],
				],
			]
		);
        $repeater->add_control('Range_note',
			[
				'label'=>esc_html__('Note : Works in Products.','theplus'),
				'type'=>Controls_Manager::HEADING,                
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'ContentType'=>'taxonomy',
                    'WpFilterType'=>'range',
			    ],
            ],
        );	
        $repeater->add_control('maxPrice',
			[
				'label'=>__('Maximum Price','theplus'),
				'type'=>Controls_Manager::NUMBER,
				'step'=>25,
				'default'=>10000,
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'range',
				],
			]
		);
        $repeater->add_control('minPrice',
			[
				'label'=>__('Minimum Price','theplus'),
				'type'=>Controls_Manager::NUMBER,
				'step'=>10,
				'default'=>0,
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'range',
				],
			]
		);
        $repeater->add_control('steps',
			[
				'label'=>__( 'Steps', 'theplus' ),
				'type'=>Controls_Manager::NUMBER,
				'step'=>5,
				'default'=>100,
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>'range',
				],
			]
		);

        $repeater->add_control('ShowMorePen',
            [
                'label'=>__('Show More','theplus'),
                'type'=>Controls_Manager::POPOVER_TOGGLE,
                'label_off'=>__('Default','theplus'),
                'label_on'=>__('Custom','theplus'),
                'return_value'=>'yes',
                'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing']
                ],
            ]
        );
        $repeater->start_popover();
        $repeater->add_control('ShowMore',
            [
                'label'=>__('ShowMore','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                ],
            ]
        );
        $repeater->add_control('MoreDefault',
            [
                'label'=>__('Default Display','theplus'),
                'type'=>Controls_Manager::NUMBER,
                'min'=>0,
                'max'=>100,
                'step'=>1,
                'default'=>3,
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                    'ShowMore'=>'yes',
                ],
            ]
        );
        $repeater->add_control('showmoretxt',
            [
                'label'=>__('ShowMore Text','theplus'),
                'type'=>Controls_Manager::TEXT,
                'default'=>__('Show More','theplus'),
                'placeholder'=>__('Enter Value','theplus'),
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                    'ShowMore'=>'yes',
                ],
            ]
        );
        $repeater->add_control('showlesstxt',
            [
                'label'=>__('ShowLess Text','theplus'),
                'type'=>Controls_Manager::TEXT,
                'default'=>__('Show Less','theplus'),
                'placeholder'=>__('Enter Value','theplus'),
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                    'ShowMore'=>'yes',
                ],
            ]
        );
        $repeater->add_control('scrollOn',
            [
                'label'=>__('Scroll Height','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                    'ShowMore'=>'yes',
                ],
            ]
        );
        $repeater->add_responsive_control('height_scroll',
            [
                'label'=>__('Height','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>[ 'px', '%' ],
                'separator'=>'after',
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%'=>[
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'condition'=>[
                    'filteroption'=>'wpfilter',
                    'WpFilterType'=>['checkbox','radio','tabbing'],
                    'ShowMore'=>'yes',
                    'scrollOn'=>'yes',
                ],
            ]
        );
        $repeater->end_popover();

        $repeater->add_control('tooltip',
			[
				'label'=>__('Tooltip','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'',
                'separator'=>'before',
                'condition'=>[
                    'filteroption'=>'Woofilter',
                    'WooFilterType'=>['color','image','button']
				],
			]
		);
        $repeater->add_control('RDesktop_column',
			[
				'label'=>esc_html__( 'Desktop', 'theplus' ),
				'type'=>Controls_Manager::SELECT,
				'default'=>'3',
				'options'=>theplus_get_columns_list(),
				'condition'=>[
                    'filteroption' => 'Woofilter',
                    'WooFilterType'=>['color','image','button']
				],
			]
		);
		$repeater->add_control('RTablet_column',
			[
				'label' => esc_html__( 'Tablet', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => theplus_get_columns_list(),
				'condition' => [
                    'filteroption'=>'Woofilter',
                    'WooFilterType'=>['color','image','button']
				],
			]
		);
		$repeater->add_control('RMobile_column',
			[
				'label'=>esc_html__('Mobile','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'6',
				'options'=>theplus_get_columns_list(),
				'condition'=>[
                    'filteroption'=>'Woofilter',
                    'WooFilterType'=>['color','image','button']
				],
			]
		);

        $repeater->add_control('FRemove_style',
			[
				'label'=>__('Tag Position','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'end',
				'options'=>[
					'start'=>__('Start','theplus'),
					'end'=>__('End','theplus'),
				],
                'condition'=>[
                    'filteroption'=>'extrafilter',
                    'ExFilterType'=>'filter_reset',
				],
			]
		);
        $repeater->add_control('FTR_txt',
			[
				'label'=>__('Total Results Text','theplus'),
				'type'=>Controls_Manager::TEXTAREA,
				'rows'=>2,
				'default'=>__('Showing {visible_product_no} of {total_product_no} results','theplus'),
				'placeholder'=>__('Enter Total Message','theplus'),
                'condition'=>[
                    'filteroption'=>'extrafilter',
                    'ExFilterType'=>'total_results',
				],
			]
		);
        $repeater->add_control('FTR_Note',
			[
				'label'=>esc_html__('Note : You can include dynamic tags like {visible_product_no} and {total_product_no} here.','theplus'),
				'type'=>Controls_Manager::HEADING,
                'condition'=>[
                    'filteroption'=>['extrafilter'],
                    'ExFilterType'=>'total_results',
				],
			]   
		);

        $repeater->add_control('HadingPopup',
			[
				'label'=>__('Heading setting','theplus'),
				'type'=>Controls_Manager::POPOVER_TOGGLE,
				'label_off'=>__('Default','theplus'),
				'label_on'=>__('Custom','theplus'),
				'return_value'=>'yes',
				'default'=>'yes',
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
				],
			]
		);
		$repeater->start_popover();
        $repeater->add_control('headingOn',
            [
                'label'=>__('Enable Heading','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'yes',
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
                ],
            ]
        );
        $repeater->add_control('fieldTitle',
            [
                'label'=>__('Title Text','theplus'),
                'type'=>Controls_Manager::TEXT,
                'default'=>__('Category','theplus'),
                'placeholder'=>__('Enter Title Text','theplus'),
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
                ],
            ]
        );
        $repeater->add_control('Titlelayout',
			[
				'label'=>__('Layout','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'default',
				'options'=>[
					'default'=>__('Default','theplus'),
					'inline'=>__('inline','theplus'),
				],
			]
		);
        $repeater->add_control('DDwidth',
			[
				'label'=>__('Title Lable Width','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px','%'],
				'range'=>[
					'px'=>[
						'min'=>0,
						'max'=>1000,
						'step'=>5,
					],
					'%'=>[
						'min'=>0,
						'max'=>100,
					],
				],
				'default'=>[
					'unit'=>'%',
					'size'=>'',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-search-filter .tp-search-form {{CURRENT_ITEM}} .tp-title-inline'=>'width:{{SIZE}}{{UNIT}};',
				],
                'condition'=>[
                    'Titlelayout'=>'inline',
                ],
			]
		);
        $repeater->add_control('Toggdisable',
            [
                'label'=>__('Toggle Disable','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'yes',
                'condition'=>[
                    'filteroption'=>['wpfilter','Woofilter'],
                ],
            ]
        );
        $repeater->add_control('ToggDef',
            [
                'label'=>__('Default Toggle On','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'yes',
                'condition'=>[
                    'filteroption'=>['wpfilter','Woofilter'],
                    'Toggdisable'=>'yes',
                ],
            ]
        );
        $repeater->add_control('showIcon',
            [
                'label'=>__('Show Icon','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'yes',
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
                ],
            ]
        );
        $repeater->add_control('Iconlib',
            [
                'label'=>__('Icon Library','theplus'),
                'type'=>Controls_Manager::ICONS,
                'default'=>[
                    'value'=>'fas fa-university',
                    'library'=>'solid',
                ],
                'condition' => [
                    'filteroption' => ['wpfilter','Woofilter'],
                    'showIcon'=>'yes',
                ],
            ]
        );
        $repeater->end_popover();

        $this->add_control('searchField',
            [
				'label'=>esc_html__('Search Field','theplus'),
                'type'=>Controls_Manager::REPEATER,
                'default'=>[ 
                    ['filteroption'=>'wpfilter']
                ],
				'fields'=>$repeater->get_controls(),
                'title_field'=>'{{{ fieldTitle }}}',
            ]
        ); 
		$this->end_controls_section();

        /*columns start*/
		$this->start_controls_section('columns_manage_section',
			[
				'label' => esc_html__( 'Columns Manage', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control('desktop_column',
			[
				'label'=>esc_html__('Desktop','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'12',
				'options'=>theplus_get_columns_list(),
			]
		);
		$this->add_control('tablet_column',
			[
				'label'=>esc_html__('Tablet','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'12',
				'options'=>theplus_get_columns_list(),
			]
		);
		$this->add_control('mobile_column',
			[
				'label'=>esc_html__('Mobile','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'12',
				'options'=>theplus_get_columns_list(),
			]
		);
		$this->add_responsive_control('columnSpace',
			[
				'label'=>esc_html__('Columns Gap / Space Between','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'default'=>[
					'top'=>0,
					'right'=>0,
					'bottom'=>0,
					'left'=>0,
				],
				'separator'=>'before',
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control('columnSpaceMargin',
			[
				'label'=>esc_html__('Columns Margin','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],				
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*columns end*/

        /*Extra start */
        $this->start_controls_section('ExtraOption_section',
			[
				'label' => esc_html__('Extra Option','theplus'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);  
        $this->add_control('FilterBtnPen',
            [
                'label'=>__('Filter Toggle Button','theplus'),
                'type'=>Controls_Manager::POPOVER_TOGGLE,
                'label_off'=>__('Default','theplus'),
                'label_on'=>__('yes','theplus'),
                'return_value'=>'yes',
                'default'=>'',
            ]
        );
        $this->start_popover();
        $this->add_control('FilterBtn',
			[
				'label'=>__('Filter Toggle Button','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'',
			]
		);
        $this->add_control('TogBtnNum',
            [
                'label'=>__('Default Filters','theplus'),
                'type'=>Controls_Manager::NUMBER,
                'step'=>1,
                'default'=>3,
                'condition'=>[
                    'FilterBtn'=>'yes'
                ],
            ]
        );
        $this->add_control('TogBtnPos',
            [
                'label'=>__('Button Style','theplus'),
                'type'=>Controls_Manager::SELECT,
                'default'=> 'relative',
                'options'=> [
                    'fix'=>__('Fix','theplus'),
                    'relative'=>__('Relative','theplus')
                ],
                'condition' => [
                    'FilterBtn' => 'yes'
                ],
            ]
        );
        $this->add_control('TogBtnTitle',
            [
                'label' => __('Show More','theplus'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Show More','theplus'),
                'placeholder' => __('Enter Text','theplus'),
                'condition' => [
                    'FilterBtn' => 'yes'
                ],
            ]
        );
        $this->add_control('TogBtnTitleLess',
            [
                'label' => __('Show Less','theplus'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Show Less','theplus'),
                'placeholder' => __('Enter Text','theplus'),
                'condition' => [
                    'FilterBtn' => 'yes'
                ],
            ]
        );
        $this->add_control('ToggleMedia',
            [
                'label'=>__('Button Icon','theplus'),
                'type'=>Controls_Manager::SELECT,
                'default'=> 'icon',
                'options'=> [
                    ''=>__('None','theplus'),
                    'icon'=>__('Icon','theplus'),
                    'image'=>__('Image','theplus')
                ],
                'condition' => [
                    'FilterBtn' => 'yes'
                ],
            ]
        );
        $this->add_control('ToggleBtnIcon',
            [
                'label'=>__('Select Button Icon','theplus'),
                'type'=>Controls_Manager::ICONS,
                'condition' => [
                    'FilterBtn'=>'yes',
                    'ToggleMedia'=>'icon'
                ],
                'default'=>[
                    'value'=>'fas fa-sliders-h',
                    'library'=>'solid',
                ],
            ]
        );
        $this->add_control('Toggleimage',
            [
                'label'=>__('Choose Image','theplus'),
                'type'=>Controls_Manager::MEDIA,
                'default'=>[
                    'url'=>Utils::get_placeholder_image_src(),
                ],
                'condition'=>[
                    'FilterBtn'=>'yes',
                    'ToggleMedia'=>'image'
                ],
            ]
        );
        $this->add_control('TogMPosition',
            [
                'label'=>__('Icon Position','theplus'),
                'type'=>Controls_Manager::SELECT,
                'options'=> [
                    'start'=>__('Before','theplus'),
                    'end'=>__('After','theplus')
                ],
                'default'=> 'start',
                'condition' => [
                    'FilterBtn'=>'yes',
                    'TogBtnTitle!'=>'',
                    'ToggleMedia!'=>''
                ],
            ]
        );
        $this->end_popover();
        $this->add_control('URLParameter',
            [
                'label'=>__('URL Parameter','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
            ]
		);  
        $this->add_control('URLParameterNote',
			[
				'label'=>esc_html__('Note : By enabling this option, You will have semantic URLs with all selected filters as a URL postfix. It\'s make filter pages SEO Friendly.','theplus'),
				'type'=>Controls_Manager::HEADING,
                'separator'=>'after',
				'condition'=>[
					'URLParameter'=>'yes',
				],
			]   
		);
        $this->add_control('Ftagtitle',
            [
                'label'=>__('Filtertag Title Enable','theplus'),
                'type'=>Controls_Manager::SWITCHER,
                'label_on'=>__('Show','theplus'),
                'label_off'=>__('Hide','theplus'),
                'return_value'=>'yes',
                'default'=>'',
            ]
        );
        $this->add_control('ErrorMsg',
			[
				'label'=>__('Post Not Found Massage','theplus'),
				'type'=>Controls_Manager::TEXTAREA,
				'rows'=>2,
				'default'=>__('Sorry! No Results Found! Try Again.','theplus'),
				'placeholder'=>__('Enter Error Massage','theplus'),
			]
		);
        $this->end_controls_section();
        /*Extra start*/

		/*style start*/

        /*Title start*/
        $this->start_controls_section('FieldTitle_styling',
            [
                'label' => esc_html__('Title','theplus'),
                'tab' => Controls_Manager::TAB_STYLE			
            ]
        );
        $this->add_responsive_control('titlePad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('titleMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'TitleTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-field-title .tp-title-text',
            ]
        );
        $this->start_controls_tabs('Title_Ntabs');
        $this->start_controls_tab('Title_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('titletxtNrcolor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title .tp-title-text'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title .tp-title-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('titleIconNrcolor',
            [
                'label'=>__('Toggle Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title .tp-title-toggle'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'titleNbackground',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-field-title',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TitleNBg',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-field-title',
            ]
        );
        $this->add_responsive_control('titleNBBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-field-title'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'titleNboxshadow',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .field-col .tp-field-title',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Title_Htabs',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('titletxtHrcolor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .field-col:hover .tp-field-title .tp-title-text'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .field-col:hover .tp-field-title .tp-title-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('titleIconHrcolor',
            [
                'label'=>__('Toggle Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .field-col:hover .tp-field-title .tp-title-toggle'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'titleHbackground',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .field-col:hover .tp-field-title',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TitleHBg',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col:hover .tp-field-title',
            ]
        );
        $this->add_responsive_control('titleHBBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col:hover .tp-field-title'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'titleHboxshadow',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .field-col:hover .tp-field-title',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Title End*/

        /*Alphabet start*/
        $this->start_controls_section('AlphabetSection',
            [
                'label'=>esc_html__('Alphabet','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE			
            ]
        );  
        $this->add_responsive_control('AlphabetPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('AlphabetMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'AlphabetTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content',
            ]
        );
        $this->start_controls_tabs('Alphabet_tab');
        $this->start_controls_tab('Alphabet_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('AlfNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'AlfNbackground',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'AlfNborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content',
            ]
        );
        $this->add_responsive_control('AlphaNBRds',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Alphabet_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('AlfHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content:hover'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'AlfHground',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'AlfHborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content:hover',
            ]
        );
        $this->add_responsive_control('AlphaHBRds',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Alphabet_Active',
            [
                'label' => esc_html__('Active','theplus')
            ]
        );
        $this->add_control('AlfActiveColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .field-col .tp-alphabet-content .tp-alphabet-item.active'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'ActAbackground',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .field-col .tp-alphabet-content .tp-alphabet-item.active',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'AlfAborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .field-col .tp-alphabet-content .tp-alphabet-item.active',
            ]
        );
        $this->add_responsive_control('AlphaABRds',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-content .tp-alphabet-item.active'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('AlphaBox_hadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_responsive_control('AlphabetBPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('AlphabetBMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('AlphabetBox_tab');
        $this->start_controls_tab('AlphabetBox_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Aplha_Nbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'AlphaBNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Alph_BoxN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper',
            ]
        );
        $this->add_responsive_control('Alph_NBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('AlphabetBox_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Aplha_Hbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'AlphaBHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper:hover',			
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Alph_BoxH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper:hover',
            ]
        );
        $this->add_responsive_control('Alph_HBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-alphabet-wrapper:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*Checkbox start*/
		$this->start_controls_section('CheckBox_styling',
            [
                'label'=>esc_html__('Check Box','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control('checkSize',
            [
                'label'=>__( 'Size', 'theplus' ),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>[ 'px', '%' ],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%'=>[
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon'=>'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; min-width:{{SIZE}}{{UNIT}}; min-height:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('leftOffset',
            [
                'label' => __( 'Offset Left', 'theplus' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('CB_tabs');
        $this->start_controls_tab('CB_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'checkBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name' => 'checkBorder',
                'label' => esc_html__('Border','theplus'),
                'selector' => '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon'
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('CB_Focus',
            [
                'label' => esc_html__('Focus','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'checkedkBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-checkBox input[type=checkbox]:checked+label .tp-check-icon',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'checkedkBor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-checkBox input[type=checkbox]:checked+label .tp-check-icon'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control('checkBradius',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control('CB_Heading',
            [
                'label'=>__('Select Checkbox Icon','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('checkIconSize',
            [
                'label'=>__('Size','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>100,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-check-icon .checkbox-icon' => 'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('CBI_tabs');
        $this->start_controls_tab('CBI_Normal',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('cheIcHvrColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-checkBox input[type=checkbox]+label:hover .tp-check-icon .checkbox-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('CBI_Focus',
            [
                'label'=>esc_html__('Checked','theplus')
            ]
        );
        $this->add_control('chekedIconColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-checkBox input[type=checkbox]:checked+label .tp-check-icon .checkbox-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('CBL_Heading',
            [
                'label' => __('Label','theplus'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name' => 'chlabelTypo',
                'label' => esc_html__( 'Typography', 'theplus' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-clabel'
            ]
        );
        $this->start_controls_tabs('CBL_tabs');
        $this->start_controls_tab( 'CBL_Normal',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('chlabelColor',
            [
                'label' => __('Color','theplus'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox .tp-clabel'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'CBL_Focus',
            [
                'label' => esc_html__('Checked','theplus')
            ]
        );
        $this->add_control('chkedLaColor',
            [
                'label' => __('Color','theplus'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-toggle-div .tp-checkBox input[type=checkbox]:checked+label .tp-clabel'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('CBcount_Heading',
            [
                'label' => __('Counter','theplus'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control('Ck_countpad',
			[
				'label'=> __('Inner Padding','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control('Ck_countmargin',
			[
				'label'=> __('Margin','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'separator'=>'after',
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(Group_Control_Typography::get_type(),
			[
				'name'=>'Ck_countTypo',
				'label'=>__('Typography','theplus'),
				'scheme'=>Typography::TYPOGRAPHY_1,
				'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter',
			]
		);
        $this->start_controls_tabs('Ck_count_tabs');
            $this->start_controls_tab('Ck_count_Normal',
                [
                    'label'=>esc_html__('Normal','theplus')
                ]
            );  
                $this->add_control('Ck_countNColor',
                    [
                        'label'=>__('Color','theplus'),
                        'type'=>Controls_Manager::COLOR,
                        'selectors'=>[
                            '{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter'=>'color:{{VALUE}}'
                        ]
                    ]
                );
                $this->add_group_control(Group_Control_Background::get_type(),
                    [
                        'name'=>'Ck_countBgN',
                        'types'=>['classic','gradient'],
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter',
                    ]
                );
                $this->add_group_control(Group_Control_Border::get_type(),
                    [
                        'name'=>'Ck_countTxtBN',
                        'label'=>esc_html__('Border','theplus'),
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter',
                    ]
                );
                $this->add_responsive_control('Ck_countTxtBRsN',
                    [
                        'label'=>__('Border Radius','theplus'),
                        'type'=>Controls_Manager::DIMENSIONS,
                        'size_units'=>['px','%'],
                        'selectors'=>[
                            '{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_group_control(Group_Control_Box_Shadow::get_type(),
                    [
                        'name'=>'Ck_countBsdN',
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2 .tp-field-Counter',
                    ]
                );
            $this->end_controls_tab();
            $this->start_controls_tab('Ck_count_Hover',
                [
                    'label'=>esc_html__('Hover','theplus')
                ]
            );
                $this->add_control('Ck_countHColor',
                    [
                        'label'=>__('Color','theplus'),
                        'type'=>Controls_Manager::COLOR,
                        'selectors'=>[
                            '{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2:hover .tp-field-Counter'=>'color:{{VALUE}}'
                        ]
                    ]
                );
                $this->add_group_control(Group_Control_Background::get_type(),
                    [
                        'name'=>'Ck_countBgH',
                        'types'=>['classic','gradient'],
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2:hover .tp-field-Counter',
                    ]
                );
                $this->add_group_control(Group_Control_Border::get_type(),
                    [
                        'name'=>'Ck_countTxtBH',
                        'label'=>esc_html__('Border','theplus'),
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2:hover .tp-field-Counter',
                    ]
                );
                $this->add_responsive_control('Ck_countTxtBRsH',
                    [
                        'label'=>__('Border Radius','theplus'),
                        'type'=>Controls_Manager::DIMENSIONS,
                        'size_units'=>['px','%'],
                        'selectors'=>[
                            '{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2:hover .tp-field-Counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_group_control(Group_Control_Box_Shadow::get_type(),
                    [
                        'name'=>'Ck_countBsdH',
                        'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-checkBox.style-2:hover .tp-field-Counter',
                    ]
                );
            $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('TogIcon_Heading',
            [
                'label'=>__('Toggle Icon','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('togplusiconSize',
            [
                'label'=>__('Size','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 2,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tog-plus'=>'font-size:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('togplusiconalign',
            [
                'label'=>__('Position','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => -100,
                        'max' => 100,
                        'step' => 2,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-toggle'=>'right:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('togplus_tabs');
        $this->start_controls_tab('togplus_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('togNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-toggle'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('togplus_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('togHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-toggle:hover'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('CkImage_Heading',
            [
                'label'=>__('Image','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('ckimageWidth',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-checkbox-thumbimg'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('ckOffsetsH',
            [
                'label'=>esc_html__('Image Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-checkbox-thumbimg'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Ckimg_tabs');
        $this->start_controls_tab('Ckimg_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'ckimg_Nb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-checkbox-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'ckimg_Nbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-checkbox-thumbimg',
            ]
        );
        $this->add_responsive_control('ckbrsN',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox .tp-checkbox-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Ckimg_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'ckimg_Hb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox:hover .tp-checkbox-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'ckimg_Hbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox:hover .tp-checkbox-thumbimg',
            ]
        );
        $this->add_responsive_control('ckbrsH',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-checkBox:hover .tp-checkbox-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /*Ck Scroll Bar*/
        $this->add_control('Ck_showmore_Heading',
            [
                'label'=>__('Scroll Bar','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->start_controls_tabs('Ck_scrollC_style');
        $this->start_controls_tab('Ck_scrollC_Bar',
            [
                'label'=>esc_html__('Scrollbar','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Ck_ScrollBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar',
            ]
        );
        $this->add_responsive_control('Ck_ScrollWidth',
            [
                'type'=>Controls_Manager::SLIDER,
                'label'=>esc_html__('Width','theplus'),
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Ck_scrollC_Tmb',
            [
                'label' => esc_html__('Thumb','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Ck_ThumbBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar-thumb',
            ]
        );
        $this->add_responsive_control('Ck_ThumbBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=> [
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Ck_ThumbBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Ck_scrollC_Trk',
            [
                'label'=>esc_html__('Track','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Ck_TrackBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->add_responsive_control('Ck_TrackBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar-track'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Ck_TrackBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('CkBox_hadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_responsive_control('CkBPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('CkBMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('CkBox_tab');
        $this->start_controls_tab('CkBox_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'CkB_Nbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'CkBNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Ck_BoxN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox',
            ]
        );
        $this->add_responsive_control('Ck_NBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('CkB_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'CkB_Hbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'CkBHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox:hover',			
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'CkB_BoxH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox:hover',
            ]
        );
        $this->add_responsive_control('CkB_HBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-checkBox:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*Checkbox end*/

        /*Datepicker Start*/
        $this->start_controls_section('datesection',
            [
                'label'=>esc_html__('DatePicker','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );  
        $this->add_control('DateBoxHadding',
            [
                'label'=>__('Box Option','theplus'),
                'type'=>Controls_Manager::HEADING,
            ]
        );
        $this->add_control('dateboxpad',
            [
                'label'=>__('Box Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-date-wrap'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('DatelableHadding',
            [
                'label'=>__('Lable Option','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'datelabletypo',
                'label'=>esc_html__('Lable Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > label',
            ]
        );
        $this->add_control('datelablepad',
            [
                'label'=>__('Lable Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > label'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('Datelbltabs');
        $this->start_controls_tab('Datelbl_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('Datelbl_Ncolor',
            [
                'label'=>__('lable Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > label'=>'color: {{VALUE}}',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Datelbl_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );  
        $this->add_control('Datelbl_Hcolor',
            [
                'label'=>__('lable Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover > div > label'=>'color: {{VALUE}}',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('DatepickerHadding',
            [
                'label'=>__('Datepicker Option','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'datetypo',
                'label'=>esc_html__('Date Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]',
            ]
        );
        $this->add_control('Datepickerepad',
            [
                'label'=>__('Datepicker Inner Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('Datepickertabs');
        $this->start_controls_tab('datepicker_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('Datepicker_Ncolor',
            [
                'label'=>__('Placeholder Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]'=>'color: {{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DatepickerNBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Datepicker_NBorder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Datepicker_Nshadow',
                'label'=>__( 'Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap > div > input[type=date]',
            ]
        );
        $this->add_control('DatepickericonNCr',
            [
                'label'=>__('Icon Opacity','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min'=>0.1,
                        'max'=>1,
                        'step'=>0.1,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap input[type=date]::-webkit-calendar-picker-indicator'=>'filter:invert({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('datepicker_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );  
        $this->add_control('Datepicker_Hcolor',
            [
                'label'=>__('Placeholder Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover > div > input[type=date]'=>'color: {{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DatepickerHBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover > div > input[type=date]',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Datepicker_HBorder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover > div > input[type=date]',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Datepicker_Hshadow',
                'label'=>__( 'Box Shadow', 'theplus' ),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover > div > input[type=date]',
            ]
        );
        $this->add_control('DatepickericonHCr',
            [
                'label'=>__('Icon Opacity','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min'=>0.1,
                        'max'=>1,
                        'step'=>0.1,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-date-wrap:hover input[type=date]::-webkit-calendar-picker-indicator'=>'filter:invert({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('DateBox_hadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_responsive_control('DateBPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('DateBMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('DateBox_tab');
        $this->start_controls_tab('DateBox_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DateB_Nbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Date_BoxN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap',
            ]
        );
        $this->add_responsive_control('Date_NBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DateBNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('DateB_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DateB_Hbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'DateB_BoxH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap:hover',
            ]
        );
        $this->add_responsive_control('DateB_HBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DateBHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-date-wrap:hover',			
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*Datepicker End*/

        /*DropDown start*/
		$this->start_controls_section('Select_styling',
            [
                'label'=>esc_html__('DropDown','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control('selepad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('selemar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control('DDwidth',
			[
				'label'=>__('Width','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px','%'],
				'range'=>[
					'px'=>[
						'min'=>0,
						'max'=>1000,
						'step'=>5,
					],
					'%'=>[
						'min'=>0,
						'max'=>100,
					],
				],
				'default'=>[
					'unit'=>'%',
					'size'=>'',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-select'=>'width:{{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'SelectTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'
            ]
        );
        $this->start_controls_tabs('Select_tabs');
        $this->start_controls_tab('Select_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('seletxtColor',
            [
                'label' => __('Color','theplus'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'selebgType',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select,{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-sbar-dropdown-menu',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'seleNBorder',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'
            ]
        );
        $this->add_responsive_control('seleNbrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'seleNBshadow',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Select_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('seletxtFcolor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select:hover'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'seleFbgType',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'seleFBorder',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select:hover'
            ]
        );
        $this->add_responsive_control('seleHbrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'selFBshadow',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select:hover',			
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('DD_innerHeading',
			[
				'label'=>__('DropDown Inner','theplus'),
				'type'=>Controls_Manager::HEADING,
				'separator'=>'before',
			]
		);
        $this->add_control('DDenableimge',
			[
				'label'=>__('Show Backend Only','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus' ),
				'label_off'=>__('Hide','theplus' ),
				'return_value'=>'block',
				'default'=>'',
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter.tp-searchfilter-backend .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'=>'display:{{value}};',
                ],
			]
		);
        $this->start_controls_tabs('SelectInn_tabs');
        $this->start_controls_tab('SelectInn_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('selectInnCrN',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'selectInnBgCrN',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'selectInnBN',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'
            ]
        );
        $this->add_responsive_control('selectInnBrsN',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'selectInnBSdN',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('SelectInn_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('selectInnCrF',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu:hover'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'selectInnBgCrf',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'selectInnBf',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu:hover'
            ]
        );
        $this->add_responsive_control('selectInnBrsf',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'selectInnBSdf',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu:hover',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('SelectInn_Select',
            [
                'label'=>esc_html__('select','theplus')
            ]
        );
        $this->add_control('selectInnCrH',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-searchbar-li:hover'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'selectInnBgCrH',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-searchbar-li:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'selectInnBH',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-searchbar-li:hover'
            ]
        );
        $this->add_responsive_control('selectInnBrsH',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-searchbar-li:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'selectInnBSdH',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-searchbar-li:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('selectcount_Heading',
            [
                'label' => __('Counter','theplus'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control('select_counterimageWidth',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select.style-2 .tp-sbar-dropdown-menu .tp-dd-counttxt'=>'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control('select_countmargin',
            [
                'label'=> __('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select.style-2 .tp-sbar-dropdown-menu .tp-dd-counttxt'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'select_countTypo',
                'label'=>__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select.style-2 .tp-sbar-dropdown-menu .tp-dd-counttxt',
            ]
        );
        $this->start_controls_tabs('select_count_tabs');
        $this->start_controls_tab('select_count_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('select_countNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu .tp-dd-counttxt'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'select_countBgN',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu .tp-dd-counttxt',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'select_countTxtBN',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu .tp-dd-counttxt',
            ]
        );
        $this->add_responsive_control('select_countTxtBRsN',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu .tp-dd-counttxt'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'select_countBsdN',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu .tp-dd-counttxt',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('select_count_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('select_countHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu:hover .tp-dd-counttxt'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'select_countBgH',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu:hover .tp-dd-counttxt',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'select_countTxtBH',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu:hover .tp-dd-counttxt',
            ]
        );
        $this->add_responsive_control('select_countTxtBRsH',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu:hover .tp-dd-counttxt'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'select_countBsdH',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-select .tp-sbar-dropdown-menu:hover .tp-dd-counttxt',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('DDImage_Heading',
            [
                'label'=>__('Image','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('DDimageWidth',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('DDOffsetsH',
            [
                'label'=>esc_html__('Image Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('DDimg_tabs');
        $this->start_controls_tab('DDimg_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'DDimg_Nb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DDimg_Nbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg',
            ]
        );
        $this->add_responsive_control('DDbrsN',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('DDimg_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'DDimg_Hb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DDimg_Hbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg:hover',
            ]
        );
        $this->add_responsive_control('DDbrsH',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu .tp-dd-thumbimg:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /*DD Scroll Bar*/
        $this->add_control('DDScrollBarTab',
            [
                'label'=>__('Scroll Bar','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('DDScrollheight',
			[
				'label'=>__('Height','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px','%'],
				'range'=>[
					'px'=>[
						'min'=>0,
						'max'=>1000,
						'step'=>5,
					],
				],
				'default'=>[
					'unit'=>'px',
					'size'=>'',
				],
				'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'=>'max-height:{{SIZE}}{{UNIT}};',
                ],
			]
		);
        $this->start_controls_tabs('DDscrollC_style');
        $this->start_controls_tab('DDscrollC_Bar',
            [
                'label'=>esc_html__('Scrollbar','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DDScrollBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar',
            ]
        );
        $this->add_responsive_control('DDScrollWidth',
            [
                'type'=>Controls_Manager::SLIDER,
                'label'=>esc_html__('Width','theplus'),
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('DDscrollC_Tmb',
            [
                'label' => esc_html__('Thumb','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DDThumbBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar-thumb',
            ]
        );
        $this->add_responsive_control('DDThumbBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=> [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DDThumbBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('DDscrollC_Trk',
            [
                'label'=>esc_html__('Track','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'DDTrackBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar-track',
            ]
        );
        $this->add_responsive_control('DDTrackBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar-track'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'DDTrackBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-select .tp-sbar-dropdown-menu::-webkit-scrollbar-track',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*DropDown End*/

        /*Radio start*/
		$this->start_controls_section('Radio_styling',
            [
                'label' => esc_html__('Radio Button','theplus'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_control('radioSize',
			[
				'label'=>__('Size','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px','%'],
				'range'=>[
					'px'=>[
						'min'=>1,
						'max'=>100,
						'step'=>5,
					],
					'%'=>[
						'min'=>1,
						'max'=>100,
					],
				],
				'default'=>[
					'unit'=>'px',
					'size'=>'',
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-radio-icon'=>'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control('raleftOffset',
			[
				'label'=>__('Offset Left','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px','%'],
				'range'=>[
					'px'=>[
						'min'=>1,
						'max'=>100,
						'step'=>5,
					],
					'%'=>[
						'min'=>1,
						'max'=>100,
					],
				],
				'default'=>[
					'unit'=>'px',
					'size'=>'',
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-radio-icon'=>'margin-right:{{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs('Radio_tabs');
        $this->start_controls_tab('Radio_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('radioNCr',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-radio input[type=radio]+label .tp-radio-icon svg'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'radioBorder',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-radio-icon'
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Radio_Focus',
            [
                'label'=>esc_html__('Checked','theplus')
            ]
        );
        $this->add_control('radioHCr',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-radio input[type=radio]:checked+label .tp-radio-icon svg'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'radiocheBor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-radio input[type=radio]:checked+label .tp-radio-icon'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control('radioBradius',
			[
				'label'=>esc_html__('Border Radius','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>[ 'px', '%' ],
				'selectors'=>[
					'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-radio-icon'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
        $this->add_control('RadioIcon_Heading',
			[
				'label'=>__('Radio Icon','theplus'),
				'type'=>Controls_Manager::HEADING,
				'separator'=>'before'
			]
		);
        $this->add_control('radioIconSize',
			[
				'label'=>__('Size','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>[ 'px', '%' ],
				'range'=>[
					'px' => [
						'min'=>1,
						'max'=>100,
						'step'=>5,
					],
					'%' => [
						'min'=>1,
						'max'=>100,
					],
				],
				'default' => [
					'unit'=>'px',
					'size'=>'',
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-radio-icon .radioIcon'=>'width:{{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->start_controls_tabs( 'RDI_tabs' );
        $this->start_controls_tab( 'RDI_Normal',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('roIcHvrColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-radio input[type=radio]+label:hover .tp-radio-icon .radioIcon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('RDI_Focus',
            [
                'label' => esc_html__('Checked','theplus')
            ]
        );
        $this->add_control('rocheIconColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-radio input[type=radio]:checked+label .tp-radio-icon .radioIcon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('RadioLable_Heading',
            [
                'label'=>__('Label','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'rolabelTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-toggle-div .tp-radio .tp-rlabel'
            ]
        );
        $this->start_controls_tabs('RadioLable_tabs');
        $this->start_controls_tab('RadioLable_Normal',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('rolabelColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-toggle-div .tp-radio .tp-rlabel'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('RadioLable_Focus',
            [
                'label'=>esc_html__('Checked','theplus')
            ]
        );
        $this->add_control('rokedLaColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-toggle-div .tp-radio input[type=radio]:checked+label .tp-rlabel'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('Rdcount_Heading',
            [
                'label' => __('Counter','theplus'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control('Rd_countpad',
            [
                'label'=> __('Inner Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('Rd_countmargin',
            [
                'label'=> __('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'Rd_countTypo',
                'label'=>__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter',
            ]
        );
        $this->start_controls_tabs('Rd_count_tabs');
        $this->start_controls_tab('Rd_count_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('Rd_countNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Rd_countBgN',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Rd_countTxtBN',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter',
            ]
        );
        $this->add_responsive_control('Rd_countTxtBRsN',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Rd_countBsdN',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-radio.style-2 .tp-field-Counter',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Rd_count_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('Rd_countHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-radio.style-2:hover .tp-field-Counter'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Rd_countBgH',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-radio.style-2:hover .tp-field-Counter',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Rd_countTxtBH',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-radio.style-2:hover .tp-field-Counter',
            ]
        );
        $this->add_responsive_control('Rd_countTxtBRsH',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-radio.style-2:hover .tp-field-Counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Rd_countBsdH',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-radio.style-2:hover .tp-field-Counter',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('RadioTogIcon_Head',
            [
                'label'=>__('Toggle Icon','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('RadioTogsize',
            [
                'label'=>__('Size','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 2,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tog-plus'=>'font-size:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('radiotognalign',
            [
                'label'=>__('Position','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => -100,
                        'max' => 100,
                        'step' => 2,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-toggle'=>'right:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('radiotogplus_tabs');
        $this->start_controls_tab('radiotogplus_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('radiotogNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-toggle'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('radiotogplus_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('radiotogHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-toggle:hover'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('RdImage_Heading',
            [
                'label'=>__('Image','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('RdimageWidth',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('RdOffsetsH',
            [
                'label'=>esc_html__('Image Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Rdimg_tabs');
        $this->start_controls_tab('Rdimg_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Rdimg_Nb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Rdimg_Nbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg',
            ]
        );
        $this->add_responsive_control('RdbrsN',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Rdimg_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Rdimg_Hb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Rdimg_Hbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg:hover',
            ]
        );
        $this->add_responsive_control('RdbrsH',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-radio .tp-radio-thumbimg:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /*Ck Scroll Bar*/
        $this->add_control('rd_showmore_Heading',
            [
                'label'=>__('Scroll Bar','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->start_controls_tabs('rd_scrollC_style');
        $this->start_controls_tab('rd_scrollC_Bar',
            [
                'label'=>esc_html__('Scrollbar','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'rd_ScrollBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar',
            ]
        );
        $this->add_responsive_control('rd_ScrollWidth',
            [
                'type'=>Controls_Manager::SLIDER,
                'label'=>esc_html__('Width','theplus'),
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('rd_scrollC_Tmb',
            [
                'label' => esc_html__('Thumb','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'rd_ThumbBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar-thumb',
            ]
        );
        $this->add_responsive_control('rd_ThumbBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=> [
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'rd_ThumbBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('rd_scrollC_Trk',
            [
                'label'=>esc_html__('Track','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'rd_TrackBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->add_responsive_control('rd_TrackBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar-track'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'rd_TrackBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('RdBox_hadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_responsive_control('RdBPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('RdBMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('RdBox_tab');
        $this->start_controls_tab('RdBox_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'RdBox_Nbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RdBoxNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Rd_BoxN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio',
            ]
        );
        $this->add_responsive_control('Rd_NBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('RdB_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'RdB_Hbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RdBHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio:hover',			
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'RdB_BoxH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio:hover',
            ]
        );
        $this->add_responsive_control('RdB_HBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-wp-radio:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*Radio End*/

        /*Range start*/
        $this->start_controls_section('RangeSection',
            [
                'label'=>esc_html__('Range slider','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE			
            ]
        );  
        $this->add_control('RangePadding',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('RangeMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('RangeWid',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>500,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>500,
                    ],
                ],
                'default'=>[
                    'unit'=>'%',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .tp-range'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('Rangeatab');
        $this->start_controls_tab('Rangeatab_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('RangeLineNCr',
            [
                'label'=>__('Line Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-connects'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('RangeAcLineNCr',
            [
                'label'=>__('Active Line Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-connect'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('RangeBtnLineNCr',
            [
                'label'=>__('Button Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-handle'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_responsive_control('RangeNBrs',
            [
                'label'=>__('Button Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-handle'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Rangeatab_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('RangeLineHCr',
            [
                'label'=>__('Line Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-connects'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('RangeAcLineHCr',
            [
                'label'=>__('Active Line Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-connect'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('RangeBtnLineHCr',
            [
                'label'=>__('Button Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-handle'=>'background:{{VALUE}}',
                ]
            ]
        );
        $this->add_responsive_control('RangeHBrs',
            [
                'label'=>__('Button Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-handle'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('RangeTips_had',
            [
                'label'=>__('Tooltips Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('RangeTpPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('RangeTpMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('RangeTpWid',
            [
                'label'=>__('Position','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>-500,
                        'max'=>500,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>-300,
                        'max'=>300,
                    ],
                ],
                'default'=>[
                    'unit'=>'%',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'=>'bottom:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('RangeTptab');
        $this->start_controls_tab('RangeTptab_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        ); 
        $this->add_control('RangeTpNCr',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'RangeNTPBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'RangeNTPB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'
            ]
        );
        $this->add_responsive_control('RangeNTPBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RangeNTpshadow',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder .noUi-tooltip',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('RangeTptab_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('RangeTpHCr',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-tooltip'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'RangeHTPBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-tooltip',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'RangeHTPB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-tooltip'
            ]
        );
        $this->add_responsive_control('RangeHTPBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-tooltip'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RangeHTpshadow',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-range-silder:hover .noUi-tooltip',			
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Range End*/

		/*SearchInput start*/
		$this->start_controls_section('SearchInput_styling',
            [
                'label' => esc_html__('Search Input', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        ); 
        $this->add_responsive_control('SiPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('SiMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
			[
				'name'=>'SearchInputTypo',
				'label'=>esc_html__('Typography','theplus'),
				'scheme'=>Typography::TYPOGRAPHY_1,
				'selector'=>'{{WRAPPER}} .tp-search-wrap .tp-search-input',
			]
		);
        $this->start_controls_tabs('SI_tabs');
        $this->start_controls_tab('SI_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('textPlNCr',
            [
                'label'=>__('Placeholder Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input::placeholder'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:-ms-input-placeholder'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:-ms-input-placeholder'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('textNCr',
            [
                'label'=>__( 'Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input'=>'color: {{VALUE}}'
                ]
            ]
        );
        $this->add_control('iconNcolor',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'inbgType',
                'types'=>[ 'classic', 'gradient' ],
                'selector'=>'{{WRAPPER}} .tp-search-wrap .tp-search-input',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'inNBorder',
                'label'=>esc_html__( 'Border', 'theplus' ),
                'selector'=>'{{WRAPPER}} .tp-search-wrap .tp-search-input'
            ]
        );
        $this->add_responsive_control('inNBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-wrap .tp-search-input'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'inNBshadow',
                'selector'=>'{{WRAPPER}} .tp-search-wrap .tp-search-input',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('SI_Focus',
            [
                'label' => esc_html__('Focus','theplus')
            ]
        );  
        $this->add_control('textPlHCr',
            [
                'label'=>__('Placeholder Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus::placeholder'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus:-ms-input-placeholder'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus:-ms-input-placeholder'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('intxtFcolor',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('iconHFcolor',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus + .tp-search-icon'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'inFbgType',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-search-input:focus',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'inFBorder',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus'
            ]
        );
        $this->add_responsive_control('inhBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'inFBshadow',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-search-wrap .tp-search-input:focus',			
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('searchBox_hadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_responsive_control('searchBPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('searchBMar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('searchBox_tab');
        $this->start_controls_tab('searchBox_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'search_Nbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'searchBNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'search_BoxN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap',
            ]
        );
        $this->add_responsive_control('search_NBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('searchBox_Hover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'search_Hbg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap:hover',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'searchHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap:hover',			
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'search_BoxH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap:hover',
            ]
        );
        $this->add_responsive_control('search_HBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-search-wrap:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
		$this->end_controls_section();
		/*SearchInput end*/

        /*Tabs Button start*/
        $this->start_controls_section('TabsSection',
            [
                'label'=>esc_html__('Tab Button','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );      
        $this->add_responsive_control('TabInpadding',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing .tp-tabbing-wrapper'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('TabInmargin',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing .tp-tabbing-wrapper'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'TabTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper',
            ]
        );
        $this->start_controls_tabs('tabControl');
        $this->start_controls_tab('tab_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('TabNColor',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control( Group_Control_Background::get_type(),
            [
                'name'=>'TabNBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper',
            ]
        );
        $this->add_group_control( Group_Control_Border::get_type(),
            [
                'name'=>'TabNborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper',
            ]
        );
        $this->add_responsive_control('TabNbrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('CounterNTabhadding',
            [
                'label'=>__('Counter Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('TabshowColor',
            [
                'label'=>__('Count Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('TabshowBgColor',
            [
                'label'=>__('Count Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control( Group_Control_Background::get_type(),
            [
                'name'=>'CounterNBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter',
            ]
        );
        $this->add_group_control( Group_Control_Border::get_type(),
            [
                'name'=>'CounterNborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter',
            ]
        );
        $this->add_responsive_control('CounterNbrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('tab_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        ); 
        $this->add_control('TabHColor',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TabHBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TabHborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover',
            ]
        );
        $this->add_responsive_control('TabHbrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('CounterHTabhadding',
            [
                'label'=>__('Counter Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('counterHColor',
            [
                'label'=>__('Count Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tabbing-counter'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('counterHBgColor',
            [
                'label'=>__('Count Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tabbing-counter'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'counterHBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tabbing-counter',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'counterHborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tabbing-counter',
            ]
        );
        $this->add_responsive_control('CounterHbrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tabbing-counter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('tab_Active',
            [
                'label' => esc_html__('Active','theplus')
            ]
        );
        $this->add_control('TabAshowColor',
            [
                'label'=>__('Count Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active .tp-tabbing-counter'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('TabAshowBgColor',
            [
                'label'=>__('Count Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active .tp-tabbing-counter'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('TabAColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TabABg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TabAborder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active',
            ]
        );
        $this->add_responsive_control('TabAbrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper.active'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('TabImage_Heading',
            [
                'label'=>__('Image','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_control('TabimageWidth',
            [
                'label'=>__('Width','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing .tp-dy-tabbing-thumbimg'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('TabOffsetsH',
            [
                'label'=>esc_html__('Image Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing .tp-dy-tabbing-thumbimg'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Tabimg_tabs');
        $this->start_controls_tab('Tabimg_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Tabimg_Nb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing .tp-dy-tabbing-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Tabimg_Nbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing .tp-dy-tabbing-thumbimg',
            ]
        );
        $this->add_responsive_control('TabbrsN',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing .tp-dy-tabbing-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Tabimg_Focus',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Tabimg_Hb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing:hover .tp-dy-tabbing-thumbimg',
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Tabimg_Hbsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing:hover .tp-dy-tabbing-thumbimg',
            ]
        );
        $this->add_responsive_control('TabbrsH',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tabbing:hover .tp-dy-tabbing-thumbimg'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /*Tick Scroll Bar*/
        $this->add_control('TabTick_Heading',
            [
                'label'=>__('Tick Icon','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->start_controls_tabs('Tab_Tick_style');
        $this->start_controls_tab('Tab_Tick_Bar',
            [
                'label'=>esc_html__('Normal','theplus'),
            ]
        );
        $this->add_control('TickNColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tick-contener'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Tab_Tick_Tmb',
            [
                'label' => esc_html__('Hover','theplus'),
            ]
        );
        $this->add_control('TickHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper:hover .tp-tick-contener'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /*Tab Scroll Bar*/
        $this->add_control('Tab_showmore_Heading',
            [
                'label'=>__('Scroll Bar','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->start_controls_tabs('Tab_scrollC_style');
        $this->start_controls_tab('Tab_scrollC_Bar',
            [
                'label'=>esc_html__('Scrollbar','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Tab_ScrollBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar',
            ]
        );
        $this->add_responsive_control('Tab_ScrollWidth',
            [
                'type'=>Controls_Manager::SLIDER,
                'label'=>esc_html__('Width','theplus'),
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'render_type'=>'ui',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar'=>'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Tab_scrollC_Tmb',
            [
                'label' => esc_html__('Thumb','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Tab_ThumbBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar-thumb',
            ]
        );
        $this->add_responsive_control('Tab_ThumbBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=> [
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Tab_ThumbBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('Tab_scrollC_Trk',
            [
                'label'=>esc_html__('Track','theplus'),
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'Tab_TrackBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->add_responsive_control('Tab_TrackBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar-track'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'Tab_TrackBsw',
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-search-form .field-col .tp-tabbing.tp-normal-scroll::-webkit-scrollbar-track',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('BoxTabhadding',
            [
                'label'=>__('Box Options','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'TabCounterTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing-wrapper .tp-tabbing-counter',
            ]
        );
        $this->add_responsive_control('Tabpadding',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('Tabmargin',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TabBoxBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient',],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TabBoxB',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing',
            ]
        );
        $this->add_responsive_control('TabBoxBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-tabbing'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 
        $this->end_controls_section();
        /*Tab Button End*/

        /*Button Field Start*/
		$this->start_controls_section('ButtonField_styling',
            [
                'label'=>esc_html__('Woo Button Field','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control('BtnPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('Btnmar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('butnSize',
            [
                'label'=>__('Size','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>[ 'px', '%' ],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>100,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt'=>'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('Button_tabs');
        $this->start_controls_tab('ButtonB_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'butnBor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt'
            ]
        );
        $this->add_responsive_control('butnNBradius',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>100,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt'=>'border-radius:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'btnNSw',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox .tp-color-opt',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('ButtonB_Checked',
            [
                'label' => esc_html__('Checked','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'btnBorchebor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox input[type=radio]:checked+label .tp-color-opt'
            ]
        );
        $this->add_responsive_control('butnHBradius',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>100,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox input[type=radio]:checked+label .tp-color-opt'=>'border-radius:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'btnHSw',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-buttonBox input[type=radio]:checked+label .tp-color-opt',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Button Field Start*/

        /*Color Field Start*/
		$this->start_controls_section('ColorField_styling',
            [
                'label'=>esc_html__('Woo Color Field','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control('colorPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('colorMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('colorSize',
			[
				'label'=>__('Size','theplus'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>[ 'px', '%' ],
				'range'=>[
					'px'=>[
						'min'=>1,
						'max'=>100,
						'step'=>5,
					],
					'%'=>[
						'min'=>1,
						'max'=>100,
					],
				],
				'default'=>[
					'unit'=>'px',
					'size'=>'',
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-toggle-div .tp-colorBox .tp-color-opt'=>'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->start_controls_tabs('Color_tabs');
        $this->start_controls_tab('ColorB_Normal',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'colorBor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-toggle-div .tp-colorBox span.tp-color-opt'
            ]
        );
        $this->add_responsive_control('colorNBradius',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-colorBox span.tp-color-opt'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('ColorB_Checked',
            [
                'label' => esc_html__('Checked','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'Colorchebor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-toggle-div .tp-colorBox input[type=checkbox]:checked+label span.tp-color-opt'
            ]
        );
        $this->add_responsive_control('colorHBradius',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-toggle-div .tp-colorBox input[type=checkbox]:checked+label span.tp-color-opt'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('BoxCr_Heading',
            [
                'label'=> __('Box Option','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('wooCrBPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('wooCrBMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );    
        $this->add_group_control(Group_Control_Background::get_type(),
			[
				'name'=>'bCrBG',
				'label'=>__('Background','theplus'),
				'types'=>['classic','gradient','video'],
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color',
			]
		);
        $this->add_group_control(Group_Control_Border::get_type(),
			[
				'name'=>'bCrB',
				'label'=>__('Border','theplus'),
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color',
			]
		);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
			[
				'name'=>'bCrbsd',
				'label'=>__('Box Shadow','theplus'),
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-color',
			]
		);
        $this->end_controls_section();
        /*Color Field End*/

        /*Image Field Start*/
		$this->start_controls_section('ImageField_styling',
            [
                'label' => esc_html__('Woo Image Field','theplus'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control('imgPadding',
			[
				'label'=>esc_html__('Padding','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
        $this->add_responsive_control('imgmargin',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Image_tabs');
        $this->start_controls_tab('ImageB_Normal',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'imgBor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox .woo-img-tag'
            ]
        );
        $this->add_responsive_control('imgNBradius',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox .woo-img-tag'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'ImageNSw',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox .woo-img-tag',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('ImageB_Checked',
            [
                'label'=>esc_html__('Checked','theplus')
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'imgBorchebor',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox input[type=checkbox]:checked+label .woo-img-tag'
            ]
        );
        $this->add_responsive_control('imgHBradius',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox input[type=checkbox]:checked+label .woo-img-tag'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'ImageHSw',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-imgBox input[type=checkbox]:checked+label .woo-img-tag',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
            
        $this->add_control('BOxImg_Heading',
            [
                'label'=> __('Box Option','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('wooimgBPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-image'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('wooimgBMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-image'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );    
        $this->add_group_control(Group_Control_Background::get_type(),
			[
				'name'=>'bImgBG',
				'label'=>__('Background','theplus'),
				'types'=>['classic','gradient','video'],
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-image',
			]
		);
        $this->add_group_control(Group_Control_Border::get_type(),
			[
				'name'=>'bImgB',
				'label'=>__('Border','theplus'),
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-image',
			]
		);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
			[
				'name'=>'bImgbsd',
				'label'=>__('Box Shadow','theplus'),
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-woo-image',
			]
		);
        $this->end_controls_section();
        /*image Field Start*/

        /*Rating ⭐ Start*/
		$this->start_controls_section('Ratingsection',
            [
                'label' => esc_html__('Woo Rating','theplus'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control('starPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('starMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Startabs');
        $this->start_controls_tab('StarNormal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('StarNCr',
            [
                'label'=>__('Star Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    // '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .tp-star-rating label'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .tp-star-rating label~label'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('StarNBgCr',
            [
                'label'=>__('Star Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'StarNB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'
            ]
        );
        $this->add_responsive_control('StarNBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'StarNBSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating .tp-start-icon',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('StarHover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('StarHCr',
            [
                'label'=>__('Star Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-star-rating label:hover'=>'color:{{VALUE}}',
                    '{{WRAPPER}} .tp-search-filter .tp-star-rating label:hover~label'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('StarHBgCr',
            [
                'label'=>__('Star Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover .tp-start-icon'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'StarHB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover .tp-start-icon'
            ]
        );
        $this->add_responsive_control('StarHBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover .tp-start-icon'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'StarHBSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover .tp-start-icon',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control('RatingBoxHead',
            [
                'label'=>__('Box Option','theplus'),
                'type'=>Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control('Ratingalign',
            [
                'label'=>__('Rating Alignment','theplus'),
                'type'=>Controls_Manager::CHOOSE,
                'options'=>[
                    'left' => [
                        'title'=>esc_html__( 'Left','theplus'),
                        'icon'=>'eicon-text-align-left',
                    ],
                    'center' => [
                        'title'=>esc_html__( 'Center','theplus'),
                        'icon'=>'eicon-text-align-center',
                    ],
                    'right' => [
                        'title'=>esc_html__('Right', 'theplus'),
                        'icon'=>'eicon-text-align-right',
                    ],
                ],
                'default'=>'center',
                'toggle'=>true,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'=>'justify-content:{{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control('RatingPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('RatingMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->start_controls_tabs('Ratingtabs');
        $this->start_controls_tab('RatingNormal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('RatingNBgCr',
            [
                'label'=>__('Star Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'RatingNB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'
            ]
        );
        $this->add_responsive_control('RatingNBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RatingNBSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('RatingHover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('RatinghBgCr',
            [
                'label'=>__('Star Background Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover'=>'Background:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'RatingHB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover'
            ]
        );
        $this->add_responsive_control('RatingHBRs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'RatingHBSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-star-rating:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Rating ⭐ End*/

        /*Tooltip Field Start*/
        $this->start_controls_section('TooltipField_styling',
            [
                'label'=>esc_html__('Woo Tooltip','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,				
            ]
        );  
        $this->add_responsive_control('ToolPad',
            [
                'label'=>esc_html__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('ToolMar',
            [
                'label'=>esc_html__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control('ToolPositon',
            [
                'label'=>__('Top Offset','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>1,
                        'max'=>100,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>1,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip'=>'bottom:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('ToolTxtCr',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip'=>'color:{{VALUE}}',
                ]
            ]
        );
		$this->add_control('ToolArrowCr',
            [
                'label'=>__('Arrow Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-tooltip::after'=>'border-right-color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'ToolBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'ToolB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip'
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'ToolSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-div .tp-tooltip',
            ]
        );
        $this->end_controls_section();
        /*Tooltip Field End*/

        /*Filter Tag start*/
        $this->start_controls_section('FilterTag_styling',
            [
                'label'=>esc_html__('Filter Tag','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE			
            ]
        ); 
        $this->add_responsive_control('tagPadding',
			[
				'label'=>esc_html__('Padding','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'default'=>[
					'top'=>'',
					'right'=>'',
					'bottom'=>'',
					'left'=>'',
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-filter-tag'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
        $this->add_responsive_control('tagMar',
			[
				'label'=>esc_html__('Margin','theplus'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
                'separator'=>'after',
				'default'=>[
					'top'=>'',
					'right'=>'',
					'bottom'=>'',
					'left'=>'',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-filter-tag'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
        $this->add_group_control(Group_Control_Typography::get_type(),
			[
                'name'=>'Fttexttypo',
				'label'=>__('Typography','theplus'),				
				'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-filter-tag',
			]
		);
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'Fticonttypo',
                'label'=>__('Typography','theplus'),				
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-tag-link',
            ]
        );  
        $this->start_controls_tabs('FilterTag_tabs');
        $this->start_controls_tab('FilterTag_Normal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('tagColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-filter-tag'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('iconColor',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container .tp-tag-link i'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'tagBgtypr',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'FtNb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag',
            ]
        );
        $this->add_responsive_control('ftagNBBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'default'=>[
                    'top'=>'',
                    'right'=>'',
                    'bottom'=>'',
                    'left'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('FilterTag_Checked',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('tagHColor',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container:hover .tp-filter-tag'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('iconHcolor',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-container:hover .tp-tag-link i'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'tagHbgType',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'FtHb',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag:hover',
            ]
        );
        $this->add_responsive_control('ftagHBBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'default'=>[
                    'top'=>'',
                    'right'=>'',
                    'bottom'=>'',
                    'left'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap .tp-filter-tag:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Filter Tag End*/

        /*Filter Reset start*/
        $this->start_controls_section('filterReset_section',
            [
                'label'=>__('Filter Reset Button','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );  
        $this->add_responsive_control('FiterResetPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('FiterResetmar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'filterResetTypo',
                'label'=>__('Typography','theplus'),				
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset',
            ]
        );
        $this->start_controls_tabs('FRTab');
        $this->start_controls_tab('FRN',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );

        $this->add_control('FRnCR',
            [
                'label'=>__('Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('FRiconNCR',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset .tp-tag-link i'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'FRNBGCr',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'FRNB',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset',
            ]
        );
        $this->add_responsive_control('FRNBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'default'=>[
                    'top'=>'',
                    'right'=>'',
                    'bottom'=>'',
                    'left'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'FRNSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('FRH',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('FRiconHCR',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset:hover .tp-tag-link i'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'FRHBGCr',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'FRHB',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset:hover',
            ]
        );
        $this->add_responsive_control('FRHBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'default'=>[
                    'top'=>'',
                    'right'=>'',
                    'bottom'=>'',
                    'left'=>'',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'FRHSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-filter-tag-wrap span.tp-tag-reset:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Filter Reset End*/

        /*Filter TotalResult End*/
        $this->start_controls_section('totalresult_section',
            [
                'label'=>__('Total Result (Filter Meta)','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control('Totalalign',
			[
				'label'=>esc_html__('Text Alignment','theplus'),
				'type'=>Controls_Manager::CHOOSE,
				'options'=>[
					'flex-start'=>[
						'title'=>esc_html__('Left','theplus'),
						'icon'=>'fa fa-align-left',
					],
					'center'=>[
						'title'=>esc_html__( 'Center','theplus'),
						'icon'=>'fa fa-align-center',
					],
					'flex-end'=>[
						'title'=>esc_html__('Right','theplus'),
						'icon'=>'fa fa-align-right',
					],
				],
				'default'=>'left',
				'toggle'=>false,
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-search-form .tp-total-results-wrap'=>'justify-content:{{VALUE}}'
				]
			]
		);
        $this->add_responsive_control('TRtxtPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control('TRtxtmar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'TRtxttypo',
                'label'=>__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap',
            ]
        );
        $this->start_controls_tabs('TRTab');
        $this->start_controls_tab('TRN',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('TRNCR',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap .tp-total-results-txt'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TRNBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TRB',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap',
            ]
        );
        $this->add_responsive_control('TRNBRs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'], 
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'TRNBrsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('TRH',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('TRHCR',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap:hover .tp-total-results-txt'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TRHBg',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TRHB',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap:hover',
            ]
        );
        $this->add_responsive_control('TRHBrs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'], 
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-total-results-wrap'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'TRHBrSd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-total-results-wrap',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Filter TotalResult End*/

        /*Showmore start*/
        $this->start_controls_section('ShowmoreSection',
            [
                'label'=>esc_html__('Showmore / Showless','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE			
            ]
        ); 
        $this->add_control('showmorealign',
            [
                'label'=>esc_html__('ShowMore Alignment','theplus'),
                'type'=>Controls_Manager::CHOOSE,
                'options'=>[
                    'flex-start'=> [
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					],
                ],
                'default'=>'flex-start',
                'toggle'=>false,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-search-form .tp-tabbing-redmore'=>'justify-content:{{VALUE}}'
                ]
            ]
        );
        $this->add_control('ShowPadding',
            [
                'label'=>__('More Text Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('ShowMargin',
            [
                'label'=>__('More Text Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'MoreTypo',
                'label'=>esc_html__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore',
            ]
        );
        $this->start_controls_tabs('BasicTab');
        $this->start_controls_tab('BasicNormal',
            [
                'label' => esc_html__('Normal','theplus')
            ]
        );
        $this->add_control('ShowMANCr',
            [
                'label'=>__('ShowMore Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore.ShowMore'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('ShowlNCr',
            [
                'label'=>__('ShowLess Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore.Showless'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'showNBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'showNB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore'
            ]
        );
        $this->add_responsive_control('showNBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'showNsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore',			
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'BasicHover',
            [
                'label' => esc_html__('Hover','theplus')
            ]
        );
        $this->add_control('ShowMAHCr',
            [
                'label'=>__('ShowMore Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore.ShowMore:hover'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_control('ShowlHCr',
            [
                'label'=>__('ShowLess Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore.Showless:hover'=>'color:{{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'showHBg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'showHB',
                'label'=>esc_html__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore:hover'
            ]
        );
        $this->add_responsive_control('showHBrs',
            [
                'label'=>esc_html__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>[ 'px', '%' ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore:hover'=>'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'showHsd',
                'selector'=>'{{WRAPPER}} .tp-search-filter .field-col .tp-filter-readmore:hover',			
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Showmore End*/

        /*Filter button start*/
        $this->start_controls_section('FilterBtnsection',
            [
                'label'=>esc_html__('Filter Toggle Button','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'FilterBtn'=>'yes',
                ],
            ]
        );

        $this->add_control('BtnColumnSetting',
			[
				'label'=>__('Columns option','theplus'),
				'type'=>Controls_Manager::POPOVER_TOGGLE,
				'label_off'=>__('Default','theplus'),
				'label_on'=>__('Custom','theplus'),
				'return_value'=>'yes',
				'default'=>''
			]
		);
        $this->start_popover();
        $this->add_control('EnableBtnColumn',
			[
				'label'=>__('Enable','theplus'),
				'type'=>Controls_Manager::SWITCHER,
				'label_on'=>__('Show','theplus'),
				'label_off'=>__('Hide','theplus'),
				'return_value'=>'yes',
				'default'=>'',
			]
		);
		$this->add_control('BtnDesktop',
			[
				'label' => esc_html__( 'Desktop', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'options' => theplus_get_columns_list(),
                'condition'=>[
                    'EnableBtnColumn'=>'yes',
                ],
			]
		);
		$this->add_control('BtnTablet',
			[
				'label'=>esc_html__('Tablet','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>3,
				'options'=>theplus_get_columns_list(),
                'condition'=>[
                    'EnableBtnColumn'=>'yes',
                ],
			]
		);
		$this->add_control('BtnMobile',
			[
				'label'=>esc_html__('Mobile','theplus'),
				'type'=>Controls_Manager::SELECT,
				'default'=>3,
				'options'=>theplus_get_columns_list(),
                'condition'=>[
                    'EnableBtnColumn'=>'yes',
                ],
			]
		);
		$this->end_popover();

        $this->add_control('TogBtnalign',
            [
                'label'=>esc_html__('Button Alignment','theplus'),
                'type'=>Controls_Manager::CHOOSE,
                'options'=>[
                    'left'=>[
                        'title'=>esc_html__('Left','theplus'),
                        'icon'=>'fa fa-align-left',
                    ],
                    'center'=>[
                        'title'=>esc_html__( 'Center','theplus'),
                        'icon'=>'fa fa-align-center',
                    ],
                    'right'=>[
                        'title'=>esc_html__('Right','theplus'),
                        'icon'=>'fa fa-align-right',
                    ],
                ],
                'default'=>'right',
                'toggle'=>false,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-button-filter'=>'justify-content:{{VALUE}}'
                ],
                'condition'=>[
                    'FilterBtn'=>'yes',
                ],
            ]
        );
        $this->add_control('TogBtnalignRela',
			[
				'label'=>esc_html__('Button Alignment','theplus'),
				'type'=>Controls_Manager::CHOOSE,
				'options'=>[
					'start'=>[
						'title'=>esc_html__( 'Top', 'theplus' ),
						'icon'=>'fa fa-chevron-up',
					],
					'center'=>[
						'title'=>esc_html__( 'Center', 'theplus' ),
						'icon'=>'eicon-text-align-center',
					],
					'end'=>[
						'title'=>esc_html__( 'Bottom', 'theplus' ),
						'icon'=>'fa fa-chevron-down',
					],								
				],
				'selectors'=>[
					'{{WRAPPER}} .tp-search-filter .tp-button-filter'=>'align-items:{{VALUE}};',					
				],
				'condition'=>[
                    'FilterBtn'=>'yes',
                ],
				'toggle'=>true,
			]
		);
        $this->add_responsive_control('FBtnPad',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ],
            ]
        );
        $this->add_responsive_control('FBtnmar',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'separator'=>'after',
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'FBtntxt',
                'label'=>__('Typography','theplus'),
                'scheme'=>Typography::TYPOGRAPHY_1,
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button .tp-button-text',
                'condition'=>[
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_responsive_control('TogIconsize',
            [
                'label'=>__('Icon or Image Size','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button .tp-button-icon'=>'font-size:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button .tp-button-ImageTag'=>'width:{{SIZE}}{{UNIT}};',
                ],
                'condition'=>[
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->start_controls_tabs('FilterBtnTab');
        $this->start_controls_tab('ToggleBtnN',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        );  
        $this->add_control('FbtextNCr',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button .tp-button-text'=>'color:{{VALUE}}',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_control('FbIconNCr',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button .tp-button-icon'=>'color:{{VALUE}}',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                    'ToggleMedia'=>'icon'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TogNBG',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TogNBorder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_responsive_control('TogNBRs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'], 
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-button-filter .tp-toggle-button'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'TogNshadow',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('ToggleBtnH',
            [
                'label'=>esc_html__('Hover','theplus'),
            ]
        );
        $this->add_control('FbtextHCr',
            [
                'label'=>__('Text Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button:hover .tp-button-text'=>'color:{{VALUE}}',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_control('FbIconHCr',
            [
                'label'=>__('Icon Color','theplus'),
                'type'=>Controls_Manager::COLOR,
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-toggle-button:hover .tp-button-icon'=>'color:{{VALUE}}',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                    'ToggleMedia'=>'icon'
                ],
            ]
        );
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'TogHBG',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button:hover',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'TogHBorder',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button:hover',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->add_responsive_control('TogHBRs',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'], 
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-button-filter .tp-toggle-button:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'FilterBtn'=>'yes',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'TogHshadow',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter .tp-toggle-button:hover',
                'condition' => [
                    'FilterBtn'=>'yes',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control('TogMPadd',
            [
                'label'=>__('Media Padding','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px','%'],
                'range'=>[
                    'px'=>[
                        'min'=>0,
                        'max'=>1000,
                        'step'=>5,
                    ],
                    '%'=>[
                        'min'=>0,
                        'max'=>100,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>'',
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter .tp-button-filter .tp-toggle-button.start .tp-button-text'=>'padding-left:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tp-search-filter .tp-button-filter .tp-toggle-button.end .tp-button-text'=>'padding-right:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /*Filter Button End*/
        
        /*Background Option start*/
		$this->start_controls_section('BG_section',
            [
                'label'=>esc_html__('Background Option','theplus'),
                'tab'=>Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control('Bg_Padding',
            [
                'label'=>__('Padding','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control('Bg_Margin',
            [
                'label'=>__('Margin','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors' => [
                    '{{WRAPPER}} .tp-search-filter'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('BGControl');
        $this->start_controls_tab('BGo_Normal',
            [
                'label'=>esc_html__('Normal','theplus')
            ]
        ); 	
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'formBGN',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'formBN',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter',
            ]
        );
        $this->add_responsive_control('formBBrN',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'formNsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter',
            ]
        );
        $this->add_control('secbackdropshadown',
            [
                'label'=>esc_html__('Backdrop Filter','theplus'),
                'type'=>Controls_Manager::POPOVER_TOGGLE,
                'label_off'=>__('Default','theplus'),
                'label_on'=>__('Custom','theplus'),
                'return_value'=>'yes',
            ]
        );
        $this->add_control('secbackdropshadown_blur',
            [
                'label'=>esc_html__('Blur','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'max'=>100,
                        'min'=>1,
                        'step'=>1,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>10,
                ],
                'condition'=>[
                    'secbackdropshadown'=>'yes',
                ],
            ]
        );
        $this->add_control('secbackdropshadown_grayscale',
            [
                'label'=>esc_html__('Grayscale','theplus'),
                'type'=>Controls_Manager::SLIDER,
                'size_units'=>['px'],
                'range'=>[
                    'px'=>[
                        'max'=>1,
                        'min'=>0,
                        'step'=>0.1,
                    ],
                ],
                'default'=>[
                    'unit'=>'px',
                    'size'=>0,
                ],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter'=>'-webkit-backdrop-filter:grayscale({{secbackdropshadown_grayscale.SIZE}})  blur({{secbackdropshadown_blur.SIZE}}{{secbackdropshadown_blur.UNIT}}) !important;backdrop-filter:grayscale({{secbackdropshadown_grayscale.SIZE}})  blur({{secbackdropshadown_blur.SIZE}}{{secbackdropshadown_blur.UNIT}}) !important;',
                ],
                'condition'=>[
                    'secbackdropshadown'=>'yes',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('BGo_Hover',
            [
                'label'=>esc_html__('Hover','theplus')
            ]
        ); 
        $this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'formBGH',
                'label'=>__('Background','theplus'),
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-search-filter:hover',
            ]
        );
        $this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'formBH',
                'label'=>__('Border','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter:hover',
            ]
        );
        $this->add_responsive_control('formBBrH',
            [
                'label'=>__('Border Radius','theplus'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%','em'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-search-filter:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'formHsd',
                'label'=>__('Box Shadow','theplus'),
                'selector'=>'{{WRAPPER}} .tp-search-filter:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*Background Option End*/

        /*style end*/
	}

	protected function render() {
        $output='';
        $settings = $this->get_settings_for_display();
        $ElementerID = $this->get_unique_selector();
        $PageID = get_the_ID();
		$WidgetId = uniqid("wp-filter-");

        $desktop_class = 'tp-col-lg-'.$settings['desktop_column'];
        $tablet_class = 'tp-col-md-'.$settings['tablet_column'];
        $mobile_class = 'tp-col-sm-'.$settings['mobile_column'];
        $mobile_class .= ' tp-col-'.$settings['mobile_column'];

        $DefType = !empty($settings['TogBtnPos']) ? $settings['TogBtnPos'] : 'relative';

        $FieldArr=[];   
        $FilterField=$ResultTag=$FTR_txt='';
        $searchField = !empty($settings['searchField']) ? $settings['searchField'] : [];
        if(!empty($searchField)){
            foreach ($searchField as $index => $Filter) {
                $RepeaterClass = 'elementor-repeater-item-' .esc_attr($Filter['_id']);
                $FilterOption = isset($Filter['filteroption']) ? $Filter['filteroption'] :'';
                $Wp_Type = isset($Filter['WpFilterType']) ? $Filter['WpFilterType'] :'';
                $Woo_Type = isset($Filter['WooFilterType']) ? $Filter['WooFilterType'] :'';
                $Ex_Type = isset($Filter['ExFilterType']) ? $Filter['ExFilterType'] :'filter_tag';

                $ContentType = isset($Filter['ContentType']) ? $Filter['ContentType'] :'';
                $Title = isset($Filter['fieldTitle']) ? $Filter['fieldTitle'] : 'Category';
                $pAttr = !empty($Filter['pAttr']) ? $Filter['pAttr'] :'';
                $ShowCount = !empty($Filter['showCount']) ? 'yes' :'';
                $tooltip = !empty($Filter['tooltip']) ? 'yes' :'';
                $ShowChild = !empty($Filter['showChild']) ? 'yes' :'';
                $Titlelayout = ($Filter['Titlelayout'] == 'inline') ? 'tp-layout-inline' :'';

                if($FilterOption == 'extrafilter'){
                    if( $Ex_Type == 'filter_tag' ){
                        $ResultTag .= '<div class="tp-filter-tag-wrap"></div>';
                    }else if($Ex_Type == 'filter_reset'){
                        $TagRemovePos = ( !empty($Filter['FRemove_style']) ) ? $Filter['FRemove_style'] : '';
                        $ResultTag .= '<div class="tp-filter-removetag '.esc_attr($TagRemovePos).' hide"></div>';
                    }else if($Ex_Type == 'total_results'){
                        $FTR_txt = !empty($Filter['FTR_txt']) ? $Filter['FTR_txt'] : '';

                        $FilterField .= '<div class="field-col '.esc_attr($desktop_class).' '.esc_attr($tablet_class).' '.esc_attr($mobile_class).' ">';
                            $FilterField .= '<div class="tp-total-results-wrap">';
                                $FilterField .= '<span class="tp-total-resulttxt-org hide">'.esc_html($FTR_txt).'</span>';
                                $FilterField .= '<span class="tp-total-results-txt">'.esc_html($FTR_txt).'</span>';
                            $FilterField .= '</div>';
                        $FilterField .= '</div>';
                    }
                }else{
                    $FilterField .= '<div class="field-col '.esc_attr($Titlelayout).' '.esc_attr($RepeaterClass).' '.esc_attr($desktop_class).' '.esc_attr($tablet_class).' '.esc_attr($mobile_class).' ">';
                        $TaxonomyTT = isset($Filter['TaxonomyType']) ? $Filter['TaxonomyType'] : '';
                        if($ContentType == 'acf_conne'){
                            $acfKey='';
                            if(class_exists('acf')){
                                $acfKey = !empty($Filter['acfKey']) ? $Filter['acfKey'] : '';
                                $cusField = acf_get_field($acfKey);
                                $TaxonomyTT = !empty($cusField['name']) ? $cusField['name'] : '';
                                $CPKey = !empty($Filter['ColorPickerKey']) ? $Filter['ColorPickerKey'] : '';
                            }else{
                                $FilterField .= $this->Filter_ErrorShow('Install & Activate ACF Plugin to use this Option.');
                            }
                        }

                        $WooSorting = !empty($Filter['WooFiltersSort']) ? $Filter['WooFiltersSort'] : '';

                        if($FilterOption == 'wpfilter'){
                            if($Wp_Type == 'alphabet'){
                                $FilterField .= $this->tp_alphabet($Filter);
                                $FieldArr['alphabet'][] = array(
                                    'name' => 'alphabet',
                                    'id' => uniqid('alphabet'),
                                    'field' => 'alphabet',
                                    'type' => 'taxonomy',
                                );
                            }else if($Wp_Type == 'checkbox'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['checkBox'][] = array(
                                    'name' => $TaxonomyTT,
                                    'id' => uniqid($TaxonomyTT),
                                    'field' => 'checkBox',
                                    'type' => $ContentType,
                                );
                            }else if($Wp_Type == 'drop_down'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                if(!empty($WooSorting)){
                                    $FieldArr['select'][] = array(
                                        'name' => 'woo_SgDropDown',
                                        'id' => uniqid('woo_SgDropDown-'),
                                        'field' => 'woo_SgDropDown',
                                        'type' => $ContentType,
                                    );
                                }else{
                                    $FieldArr['select'][] = array(
                                        'name' => $TaxonomyTT,
                                        'id' => uniqid($TaxonomyTT),
                                        'field' => 'DropDown',
                                        'type' => $ContentType,
                                    );
                                }
                            }else if($Wp_Type == 'date'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['date'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $acfKey : 'tp-datepicker1',
                                    'id' => uniqid('date'),
                                    'field' => 'date',
                                    'type' => $ContentType,
                                );
                            }else if($Wp_Type == 'radio'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['radio'][] = array(
                                    'name'=>$TaxonomyTT,
                                    'id'=>uniqid($TaxonomyTT),
                                    'field'  => 'radio',
                                    'type' => $ContentType,
                                );
                            }else if($Wp_Type == 'search'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['search'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $acfKey : 'search',
                                    'id' => 'search',
                                    'field' => 'search',
                                    'type' => $ContentType,
                                );
                            }else if($Wp_Type == 'tabbing'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                if(!empty($WooSorting)){
                                    $FieldArr['tabbing'][] = array(
                                        'name' => 'woo_SgTabbing',
                                        'id' => uniqid('woo_SgTabbing-'),
                                        'field' => 'woo_SgTabbing',
                                        'type' => $ContentType,
                                    );
                                }else{
                                    $FieldArr['tabbing'][] = array(
                                        'name' => ($ContentType == 'acf_conne') ? $acfKey :  $TaxonomyTT,
                                        'id' => uniqid($TaxonomyTT),
                                        'field' => 'tabbing',
                                        'type' => $ContentType,
                                    );
                                }
                            }else if($Wp_Type == 'range'){
                                $FilterField .= $this->tp_filter_content($Filter);
                            }else if(empty($Wp_Type)){
                                $FilterField .= $this->Filter_ErrorShow('Select Source');
                            }
                        }else if($FilterOption == 'Woofilter'){
                            if($Woo_Type == 'button'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['button'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $acfKey : $pAttr,
                                    'id'=>uniqid($TaxonomyTT),
                                    'field' => 'button',
                                    'type' => $ContentType,
                                );
                            }else if($Woo_Type == 'color'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['color'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $CPKey : $pAttr,
                                    'id' => uniqid($TaxonomyTT),
                                    'field' => 'color',
                                    'type' => $ContentType,
                                );
                            }else if($Woo_Type == 'image'){
                                $FilterField .= $this->tp_filter_content($Filter);
                                $FieldArr['image'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $acfKey : $pAttr,
                                    'id' => uniqid($TaxonomyTT),
                                    'field' => 'image',
                                    'type' => $ContentType,
                                );
                            }else if($Woo_Type == 'rating'){
                                $FilterField .= $this->tp_filter_content($Filter, $TaxonomyTT);
                                $FieldArr['radio'][] = array(
                                    'name' => ($ContentType == 'acf_conne') ? $TaxonomyTT : 'rating',
                                    'id' => uniqid('rating'),
                                    'field' => 'rating',
                                    'type' => $ContentType,
                                );
                            }else if(empty($Woo_Type)){
                                $FilterField .= $this->Filter_ErrorShow('Select Source');
                            }
                        }
                    $FilterField .= '</div>';
                }
            }
        }

        $ErrorMsg = json_encode( array(
            'errormsg' => !empty($settings['ErrorMsg']) ? $settings['ErrorMsg'] : '',
        ), true);

        $JSData = json_encode( array(
            'URLParameter' => !empty($settings['URLParameter']) ? 1 : 0,
            'errormsg' => !empty($settings['ErrorMsg']) ? $settings['ErrorMsg'] : '',
            'security' => wp_create_nonce("theplus-searchfilter"),
            'FilterTitle' => !empty($settings['Ftagtitle']) ? 1 : 0,
        ), true);

        $Backclass = (\Elementor\Plugin::$instance->editor->is_edit_mode()) ? 'tp-searchfilter-backend' : '';

        $output .= '<div class="tp-search-filter '.esc_attr($Backclass).' '.esc_attr($WidgetId).'" data-id="'.esc_attr($WidgetId).'" data-basic= \''.$JSData.'\' data-errordata= \''.$ErrorMsg.'\'  data-connection="tp_list" onSubmit="return false;" >';
            $output .= '<div class="tp-filter-meta">'. $ResultTag .'</div>'; 
                    $output .= '<form class="tp-search-form" data-field=\''.json_encode($FieldArr).'\' >';
                        $output .= '<div class="tp-toggle-div">';
                            $output .= ($DefType == 'fix') ? $this->tp_filter_button($settings) : '';
                            $output .= '<div class="tp-row">';
                                $output .= $FilterField;
                                $output .= ($DefType == 'relative') ? $this->tp_filter_button($settings) : '';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</form>';
        $output .= '</div>';

		echo $output;
	}

    Protected function tp_filter_content($Repeater){
        $output='';
        $Uid = !empty($Repeater['_id']) ? $Repeater['_id'] : uniqid();
        $Filter_Option = isset($Repeater['filteroption']) ? $Repeater['filteroption'] :'';
        $Wp_Type = isset($Repeater['WpFilterType']) ? $Repeater['WpFilterType'] :'';
        $Woo_Type = isset($Repeater['WooFilterType']) ? $Repeater['WooFilterType'] :'';
        $ContentType = isset($Repeater['ContentType']) ? $Repeater['ContentType'] : '';
        $Title = isset($Repeater['fieldTitle']) ? $Repeater['fieldTitle'] : 'Category';
        $ShowCount = !empty($Repeater['showCount']) ? 'yes' :'';
        $ShowChild = !empty($Repeater['showChild']) ? 'yes' :'';
        $tooltip = !empty($Repeater['tooltip']) ? 'yes' :'';

        $attr_class='';
        if($Filter_Option == 'Woofilter'){
            $attr_class .= isset($Repeater['RDesktop_column']) ? ' tp-col-lg-'.esc_attr($Repeater['RDesktop_column']) : 'tp-col-lg-3';
            $attr_class .= isset($Repeater['RTablet_column']) ? ' tp-col-md-'.esc_attr($Repeater['RTablet_column']) : 'tp-col-md-4';
            $attr_class .= isset($Repeater['RMobile_column']) ? ' tp-col-sm-'.esc_attr($Repeater['RMobile_column']) : 'tp-col-md-4';
            $attr_class .= isset($Repeater['RMobile_column']) ? ' tp-col-'.esc_attr($Repeater['RMobile_column']) : 'tp-col-4';
        }
        
        if($ContentType == 'taxonomy'){
            $category=[];
            $Taxonomy=!empty($Repeater['TaxonomyType']) ? $Repeater['TaxonomyType'] : '';
            $pAttr=!empty($Repeater['pAttr']) ? $Repeater['pAttr'] :'';

            if($Wp_Type != 'search' && $Wp_Type != 'range' && $Woo_Type != 'rating'){
                if(!empty($Taxonomy)){
                    $attrSlug = ($Taxonomy != 'product_attr') ? $Taxonomy : $pAttr;	
                    $cat_args = [
                        'taxonomy'      => $attrSlug,
                        'parent'        => 0, 
                        'pad_counts'    => 0,
                        'show_count'    => 1,
                        'hierarchical'  => 1,
                        'hide_empty'    => 1,
                    ];
                    $tax_terms = get_terms($cat_args);

                    foreach ($tax_terms as $value) {
                        $ImageURL='';
                        $TermId = !empty($value->term_id) ? $value->term_id :'';

                        if( ($Taxonomy == 'category' || $Taxonomy == 'post_tag' || $Taxonomy == 'product_tag') && !empty($TermId) ){
                            $ImageURL = get_term_meta($TermId, 'tp_taxonomy_image', true);
                        }else if( ($Taxonomy == 'product_cat') && !empty($TermId)){
                            $GetImgID = get_term_meta($TermId, 'thumbnail_id', true);
                         
                            $GetImgurl = wp_get_attachment_image_src($GetImgID, 'tp-image-grid');
                            if(!empty($GetImgurl)){
                                $ImageURL = $GetImgurl[0];
                            }
                        }

                        $sub_args = array(
                            'taxonomy' => $attrSlug,
                            'parent' => $TermId,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hierarchical' => 1,
                            'hide_empty' => 1,
                            'pad_counts' => 0
                        );

                        $sub_categories = get_categories($sub_args);
                        $category[$TermId] = array(
                            "name" => !empty($value->name) ? $value->name : '',
                            "count" => !empty($value->count) ? $value->count : '',
                            'child' => $sub_categories,
                            'image' => (!empty($ImageURL)) ? $ImageURL : '',
                        );
                    }
                }else{
                    $output .= $this->Filter_ErrorShow('Select Taxonomy Option');
                } 
            }

            if($Filter_Option == 'wpfilter'){
                if($Wp_Type =='search'){
                    $output .= $this->tp_text_field($Uid,'searTxt',$Repeater);
                }else if($Wp_Type == 'checkbox' && !empty($category)){
                    $output .= $this->tp_checkbox($category, $Title, $Taxonomy, $ShowCount, $pAttr, $ShowChild, $Repeater);
                }else if($Wp_Type == 'drop_down' && !empty($category)){
                    $output .= $this->tp_drop_down($category, $Title, $Taxonomy, $ShowCount, $pAttr, $Repeater);
                }else if($Wp_Type == 'date'){
                    $output .= $this->tp_date($Title, 'tp-datepicker1', $Repeater);
                }else if($Wp_Type == 'tabbing' && !empty($category)){
                    $output .= $this->tp_tabbing_filter($category,$Taxonomy,$ShowCount,$Repeater);
                }else if($Wp_Type == 'radio' && !empty($category)){
                    $output .= $this->tp_radio($category, $Title, $Taxonomy, $ShowCount, $pAttr, $ShowChild, $Repeater);
                }else if($Wp_Type == 'range'){
                    $MaxPrice = !empty($Repeater['maxPrice']) ? $Repeater['maxPrice'] : 10000;
                    $MinPrice = !empty($Repeater['minPrice']) ? $Repeater['minPrice'] : 0;
                    $Steps = !empty($Repeater['steps']) ? $Repeater['steps'] : 100;
                    $output .= $this->tp_range($Title, $MaxPrice, $MinPrice, $Steps, 'price', $Repeater);
                }
            }else if($Filter_Option == 'Woofilter'){
                if($Woo_Type == 'button' && !empty($category)){
                    $output .= $this->tp_button_field($category, $Taxonomy, $ShowCount, $pAttr, $tooltip, $attr_class, $Repeater);
                }else if($Woo_Type == 'color' && !empty($category)){
                    $output .= $this->tp_color_field($category, $Taxonomy, $ShowCount, $pAttr, $tooltip, $attr_class, $Repeater);
                }else if($Woo_Type == 'image'){
                    $output .= $this->tp_image_field($category, $Taxonomy, $ShowCount, $pAttr, $tooltip, $attr_class, $Repeater);
                }else if($Woo_Type == 'rating'){
                    $output .= $this->tp_rating($Repeater,'rating');
                }
            }
        }else if($ContentType == 'acf_conne'){
            $acfArr=[];
            if(class_exists('ACF')){
                $acfKey=!empty($Repeater['acfKey']) ? $Repeater['acfKey'] : '';
                $cusField=acf_get_field($acfKey);
                $cusType=!empty($cusField['type']) ? $cusField['type'] : '';
                $cusName=!empty($cusField['name']) ? $cusField['name'] : '';

                if(!empty($cusField['choices'])){
                    foreach($cusField['choices'] as $value => $data){
                        $acfArr[$value] = array(
                            "name" => $data
                        );
                    }
                }

                if($Filter_Option == 'wpfilter'){
                    if($Wp_Type =='search'){
                        $output .= $this->tp_text_field($Uid, $acfKey, $Repeater);
                    }else if($Wp_Type == 'checkbox'){
                        $output .= $this->tp_checkbox($acfArr, $Title, $cusName,'','','', $Repeater);
                    }else if($Wp_Type == 'date'){
                        $output .= $this->tp_date($Title, $acfKey, $Repeater);
                    }else if($Wp_Type == 'drop_down'){
                        $output .= $this->tp_drop_down($acfArr, $Title, $cusName,'','', $Repeater);
                    }else if($Wp_Type == 'radio'){
                        $output .= $this->tp_radio($acfArr, $Title, $cusName,'','','', $Repeater);
                    }else if($Wp_Type == 'range'){
                        $output .=  $this->tp_range($Title, $cusField['max'], $cusField['min'], $cusField['step'], $cusName, $Repeater);
                    }else if($Wp_Type == 'tabbing'){
                        $data=[];
                        global $post,$wpdb;
                        $PubliStatus = 'publish';
                        $PrepareQ = $wpdb->prepare("SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_status=%s", $PubliStatus);
                            $GetResult = $wpdb->get_results($PrepareQ);
                            if(is_array($GetResult)){
                                $data=[];
                                foreach ($GetResult as $key => $one) {
                                    $AcfCrName = get_field($acfKey, $one->ID);
                                    if(!empty($AcfCrName)){
                                        $array2 = explode("|", $AcfCrName);
                                        
                                        foreach ($array2 as $key => $two) {
                                            $value = str_replace(' ', '-', ltrim(rtrim($two)));
                                            $data[$value] = array('name' => $value);
                                        }
                                    }
                                }
                            }
                        $output .= $this->tp_tabbing_filter($data,$acfKey,$ShowCount,$Repeater);
                    }
                }else if($Filter_Option == 'Woofilter'){
                    if($Woo_Type =='rating'){
                        $output .= $this->tp_rating($Repeater, $cusName);
                    }else if($Woo_Type == 'color'){
                        $CPKey = !empty($Repeater['ColorPickerKey']) ? $Repeater['ColorPickerKey'] : '';
                        global $post,$wpdb;
                            $PrepareQ = $wpdb->prepare("SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_status='publish'");
                            $GetResult = $wpdb->get_results($PrepareQ);
                            if(is_array($GetResult)){
                                $data=[];
                                foreach ($GetResult as $key => $one) {
                                    $AcfCrName = get_field($acfKey, $one->ID);
                                    $ColorCode = get_field($CPKey, $one->ID);

                                    $ColorName = str_replace(' ', '-', $AcfCrName);
                                    $i=0;
                                    if(!empty($ColorName)){
                                        $count[$ColorName]++;
                                    }
                                    if(!empty($ColorName) && !empty($ColorCode) ){
                                        $data[$ColorName] = array(
                                            'name' => $ColorName,
                                            'code' => $ColorCode,
                                            'count' => $count
                                        );
                                    }
                                }
                            }
                        $output .= $this->tp_color_field($data, $Taxonomy, $ShowCount, $CPKey, $tooltip, $attr_class, $Repeater);
                    }else if($Woo_Type == 'image'){
                        $data=[];
                        global $post,$wpdb;
                            $PrepareQ = $wpdb->prepare("SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_status='publish'");
                            $GetResult = $wpdb->get_results($PrepareQ);
                            if(is_array($GetResult)){
                                foreach ($GetResult as $key => $one) {
                                    $AcfCrName = get_field($acfKey, $one->ID);
                                    if( !empty($AcfCrName) ){
                                        $data[$AcfCrName['ID']] = array(
                                            'name' => $AcfCrName['ID'],
                                            'title' => $AcfCrName['title'],
                                            'url' => $AcfCrName['sizes']['thumbnail'],
                                        );
                                    }
                                }
                            }
                        $output .= $this->tp_image_field($data, $Taxonomy, $ShowCount, $acfKey, $tooltip, $attr_class, $Repeater);
                    }else if($Woo_Type == 'button'){
                        $data=[];
                        global $post,$wpdb;
                            $PrepareQ = $wpdb->prepare("SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_status='publish'");
                            $GetResult = $wpdb->get_results($PrepareQ);
                            if(is_array($GetResult)){
                                foreach ($GetResult as $key => $one) {
                                    $Name = get_field($acfKey, $one->ID);
                                    if(!empty($Name)){
                                        $data[$Name] = array(
                                            'name' => $Name,
                                        );
                                    }
                                }
                            }
                        $output .= $this->tp_button_field($data, $Taxonomy, $ShowCount, $acfKey, $tooltip, $attr_class, $Repeater);
                    }
                }
            }
        }else{
            $output .= $this->Filter_ErrorShow('Select Type');
        }

        return $output;
    }

    Protected function tp_filter_title($Repeater){
        $output='';
        $HeadingOn = !empty($Repeater['headingOn']) ? $Repeater['headingOn'] : '';
        
        if(!empty($HeadingOn)){
            $title = !empty($Repeater['fieldTitle']) ? $Repeater['fieldTitle'] : '';
            $DiableToggle = !empty($Repeater['Toggdisable']) ? true : false;
            $showIcon = !empty($Repeater['showIcon']) ? true : false;
            $Titlelayout = ($Repeater['Titlelayout'] == 'inline') ? 'tp-title-inline' :'';

            $DataValue = json_encode(array(
                'ToggleOn' => $DiableToggle,
                'DefaultValue' => !empty($Repeater['ToggDef']) ? true : false,
            ),true);
            
            $output .= '<div class="tp-field-title '.esc_attr($Titlelayout).'" data-ShowData='.esc_attr($DataValue).' >';

                if(!empty($showIcon) && !empty($Repeater['Iconlib']) && !empty($Repeater['Iconlib']['value'])){
                    ob_start();
                        \Elementor\Icons_Manager::render_icon($Repeater['Iconlib'],['aria-hidden'=>'true']);
                        $Icon = ob_get_contents();
                    ob_end_clean();	
                    $output .= '<span class="tp-title-icon">'.$Icon.'</span>';
                }
                $output .= '<span class="tp-title-text">'.esc_html($title).'</span>';
                if(!empty($DiableToggle)){
                    $output .= '<span class="tp-title-toggle">';
                        $output .= '<i class="fas fa-angle-up tp-toggle-up" aria-hidden="true"></i>';
                        $output .= '<i class="fas fa-angle-down tp-toggle-down" aria-hidden="true"></i>';
                    $output .= '</span>';
                }
            $output .= '</div>';
        }

        return $output;
    }

    protected function tp_text_field($WidgetId,$key,$Repeater){
        $op='';
        $Placeholder = !empty($Repeater['placeholder']) ? $Repeater['placeholder'] :'';

        $GFilter=[];
		if(!empty($Repeater['GenericFilter'])){
			$GFilter = array(
                'GFEnable'=> 1,
                'GFSType' => !empty($Repeater['SearchMatch']) ? $Repeater['SearchMatch'] : 'otheroption',
				'GFTitle' => !empty($Repeater['sintitle']) ? 1 : 0,
				'GFContent' => !empty($Repeater['sincontent']) ? 1 : 0,
				'GFName' => !empty($Repeater['sinname']) ? 1 : 0,
				'GFExcerpt' => !empty($Repeater['sinexcerpt']) ? 1 : 0,
				'GFCategory' => !empty($Repeater['sincategory']) ? 1 : 0,
				'GFTags' => !empty($Repeater['sinTags']) ? 1 : 0,
			);
		}else{
            $GFilter = array('GFEnable'=> 0);
        }
            $GFarray = json_encode($GFilter, true);

        $op .= $this->tp_filter_title($Repeater);
        $op .= '<div class="tp-search-wrap">';
            $op .= '<input id="search" class="form-control tp-search-input" type="text" name="search" placeholder="'.esc_attr($Placeholder).'" data-key="'.esc_attr($key).'" data-genericfilter='.$GFarray.'  data-title="search" />';
            $op .= '<i class="fas fa-search tp-search-icon" aria-hidden="true"></i>';
        $op .= '</div>';
        return $op;
    }

    protected function tp_drop_down( $data, $label, $name, $showCnt, $attr, $Repeater ){
        $output='';
        $output .= $this->tp_filter_title($Repeater);

        $WooSorting = !empty($Repeater['WooFiltersSort']) ? $Repeater['WooFiltersSort'] : '';
        $DefaultTitle = !empty($Repeater['DDtitle']) ? $Repeater['DDtitle'] : '';
        $ImageON = !empty($Repeater['Imageshow']) ? $Repeater['Imageshow'] : '';
        $layout_style = !empty($Repeater['layout_style']) ? $Repeater['layout_style'] : 'style-1';

        if(!empty($WooSorting)){
            $WooFiltersSelect = !empty($Repeater['WooFiltersSelect']) ? $Repeater['WooFiltersSelect'] : [];

            $WooName=[''=> $DefaultTitle ];
            if(!empty($WooFiltersSelect)){
                foreach ($WooFiltersSelect as $val) {
                    if($val == "featured"){ $WooName[$val] = 'Featured';
                    }else if($val == "on_sale"){ $WooName[$val] = 'On sale';
                    }else if($val == "top_sales"){ $WooName[$val] = 'Top Sales';
                    }else if($val == "instock"){ $WooName[$val] = 'In Stock';
                    }else if($val == "outofstock"){ $WooName[$val] = 'Out of Stock';
                    }
                }
            }

            $output .= '<div class="tp-select tp-woo-sorting">';
                $output .= '<div class="tp-select-dropdown">';
                    $output .= '<span>'.esc_html__($DefaultTitle).'</span>';
                    $output .= '<i class="fas fa-chevron-down tp-dd-icon"></i>';
                $output .= '</div>';
                $output .= '<input type="hidden" name="woo_SgDropDown" id="woo_SgDropDown" data-txtval="">';

                $output .= '<ul class="tp-sbar-dropdown-menu">';
                    foreach($WooName as $key => $item){
                        $output .= '<li id="'.esc_attr($key).'" class="tp-searchbar-li" >';
                            $output .= '<div class="tp-dd-labletxt" >'.esc_html__($item).'</div>';
                        $output .= '</li>';
                    }
                $output .= '</ul>';
            $output .= '</div>';
        }else{
            $output .= '<div class="tp-select '.esc_attr($layout_style).'">';
                $output .= '<div class="tp-select-dropdown">';
                    $output .= '<span>'.esc_html__($DefaultTitle).'</span>';
                    $output .= '<i class="fas fa-chevron-down tp-dd-icon"></i>';
                $output .= '</div>';
                $output .= '<input type="hidden" name="'.esc_attr($name).'" id="'.esc_attr($name).'" data-title="" data-txtval="">';

                $output .= '<ul class="tp-sbar-dropdown-menu">';
                $output .= '<li id="" class="tp-searchbar-li" >'.esc_html__($DefaultTitle).'</li>';
                    foreach($data as $value => $label){
                        $DataName = !empty($label['name']) ? $label['name'] : '';
                        $DataCount = !empty($label['count']) ? $label['count'] : '';
                        $DataImage = !empty($label['image']) ? $label['image'] : '';

                        $output .= '<li id="'.esc_attr($value).'" class="tp-searchbar-li" >';
                            if(!empty($ImageON) && !empty($DataImage)){
                                $output .= '<div class="tp-dd-lableImg" ><img src="'.esc_html($DataImage).'" class="tp-dd-thumbimg" /></div>';   
                            }

                            if($layout_style == 'style-1'){
                                $output .= '<div class="tp-dd-labletxt" >'.esc_html($DataName).'</div>';
                                $output .= !empty($showCnt) ? '<div class="tp-dd-counttxt" >('.esc_html($DataCount).')</div>' : '';
                            }else if($layout_style == 'style-2'){
                                $output .= '<div class="tp-dd-contener" >';
                                    $output .= '<div class="tp-dd-labletxt" >'.esc_html($DataName).'</div>';
                                    $output .= !empty($showCnt) ? '<div class="tp-dd-counttxt" >'.esc_html($DataCount).'</div>' : '';
                                $output .= '</div>';
                            }

                        $output .= '</li>';
                    }
                $output .= '</ul>';
            $output .= '</div>';
        }

        return $output;
    }

    protected function tp_checkbox( $data, $label, $name, $showCnt, $attr, $showchild, $Repeater ){
        $output = '';
        $output .= '<input name="checkhidden" type="hidden" value="'.esc_attr($name).'">';
        $output .= $this->tp_filter_title($Repeater);

        $Taxonomy=!empty($Repeater['TaxonomyType']) ? $Repeater['TaxonomyType'] : '';
        $ImageON=!empty($Repeater['Imageshow']) ? $Repeater['Imageshow'] : '';
        $layout_style = !empty($Repeater['layout_style']) ? $Repeater['layout_style'] : 'style-1';

        $output .= '<div class="tp-wp-checkBox tb-checkBox-data">';
          
            foreach( $data as $value => $label ){
                $DataName = !empty($label['name']) ? $label['name'] : '';
                $DataCount = !empty($label['count']) ? $label['count'] : '';
                $Datachild = !empty($label['child']) ? $label['child'] : '';
                $DataImage = !empty($label['image']) ? $label['image'] : '';

                $output .= '<div class="tp-checkBox '.esc_attr($layout_style).'">';

                    $output .= '<div class="tp-group">';
                        $output .= '<div class="tp-group-one">';
                            $output .= '<input type="checkbox" name="'.esc_attr($name).'" value="'.esc_attr($value).'" id="tp-'.esc_attr($value).'" data-title="'.esc_attr($DataName).'" />';
                            $output .= '<label for="tp-'.esc_attr($value).'" class="tp-lable">';
                                $output .= '<span class="tp-check-icon">';
                                    $output .= '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="check" class="checkbox-icon svg-inline--fa fa-check fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">';
                                        $output .= '<path fill="currentColor" d="M435.848 83.466L172.804 346.51l-96.652-96.652c-4.686-4.686-12.284-4.686-16.971 0l-28.284 28.284c-4.686 4.686-4.686 12.284 0 16.971l133.421 133.421c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-28.284-28.284c-4.686-4.686-12.284-4.686-16.97 0z"></path>';
                                    $output .= '</svg>';
                                $output .= '</span>';

                                if(!empty($DataImage) && !empty($ImageON)){
                                    $output .= '<span class="tp-checkbox-Img"><img src="'.esc_html($DataImage).'" class="tp-checkbox-thumbimg" /></span>';
                                }

                                $output .= '<div class="tp-field-content">';
                                    $output .= '<span class="tp-field-label">'.$DataName.'</span>';
                                    $output .= ($layout_style == 'style-1' && !empty($showCnt)) ? '<span class="tp-field-Counter">('.$DataCount.')</span>' : '';
                                $output .= '</div>';
                            $output .= '</label>';
                        $output .= '</div>';

                        $output .= '<div class="tp-group-two">';
                            if(!empty($Datachild) && !empty($showchild)){
                                $output .= '<span class="tp-toggle">';
                                    $output .= '<i class="tog-plus fa fa-plus" aria-hidden="true"></i>';
                                    $output .= '<i class="tog-minus fa fa-minus" aria-hidden="true"></i>';
                                $output .= '</span>';
                            }
                            $output .= ($layout_style == 'style-2' && !empty($showCnt)) ? '<span class="tp-field-Counter">'.$DataCount.'</span>' : '';
                        $output .= '</div>';
                    $output .= '</div>';

                    if(!empty($Datachild) && !empty($showchild)){
                        $output .= '<div class="tp-child-taxo">';
                            foreach( $Datachild as $child ){
                                $ImageURL='';
                                $TermId = !empty($child->term_id) ? $child->term_id : '';
                                $ChildName = !empty($child->name) ? $child->name : '';
                                $ChildCount = !empty($child->category_count) ? $child->category_count : '';

                                if( ($Taxonomy == 'product_cat') && !empty($TermId)){
                                    $GetImgID = get_term_meta($TermId, 'thumbnail_id', true);
                                    $GetImgurl = wp_get_attachment_image_src($GetImgID, 'tp-image-grid');
                                    if(!empty($GetImgurl)){
                                        $ImageURL = $GetImgurl[0];
                                    }
                                }

                                $output .= '<div class="tp-child-checkbox">';
                                    $output .= '<input type="checkbox" name="'.esc_attr($name).'" value="'.esc_attr($TermId).'" id="tp-'.esc_attr($TermId).'" data-title="'.esc_attr($ChildName).'" />';
                                    $output .= '<label for="tp-'.esc_attr($TermId).'">';
                                        $output .= '<span class="tp-check-icon">';
                                            $output .= '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="check" class="checkbox-icon svg-inline--fa fa-check fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">';
                                                $output .= '<path fill="currentColor" d="M435.848 83.466L172.804 346.51l-96.652-96.652c-4.686-4.686-12.284-4.686-16.971 0l-28.284 28.284c-4.686 4.686-4.686 12.284 0 16.971l133.421 133.421c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-28.284-28.284c-4.686-4.686-12.284-4.686-16.97 0z"></path>';
                                            $output .= '</svg>';
                                        $output .= '</span>';
                                        if(!empty($ImageURL) && !empty($ImageON)){
                                            $output .= '<span class="tp-checkbox-Img"><img src="'.esc_html($ImageURL).'" class="tp-checkbox-thumbimg" /></span>';
                                        }

                                        $output .= '<div class="tp-field-content">';
                                            $output .= '<span class="tp-field-label">'.$ChildName.'</span>';
                                                if($layout_style == 'style-1'){
                                                    $output .= '<span class="tp-field-Counter">('.$ChildCount.')</span>';
                                                }else if($layout_style == 'style-2'){
                                                    $output .= '<span class="tp-field-Counter">'.$ChildCount.'</span>';
                                                }
                                        $output .= '</div>';
                                        // $output .= (!empty($showCnt) ? '<span class="tp-clabel">'.$ChildName.'('.$ChildCount.')</span>' : '<span class="tp-clabel">'.$ChildName ).'</span></label>';

                                $output .= '</div>';
                            }
                        $output .= '</div>';
                    }
                $output .= '</div>';
            }

        $output .= $this->tp_showmore_content($Repeater, 'checkbox');
        $output .= '</div>';

        return $output;
    }

    protected function tp_radio( $data, $label, $name, $showCnt, $attr, $showchild, $Repeater ){
        $output = '';
        $output .= '<input name="radiohidden" type="hidden" value="'.esc_attr($name).'">';
        $output .= $this->tp_filter_title($Repeater);
        $Taxonomy=!empty($Repeater['TaxonomyType']) ? $Repeater['TaxonomyType'] : '';
        $ImageON = !empty($Repeater['Imageshow']) ? $Repeater['Imageshow'] : '';
        $layout_style = !empty($Repeater['layout_style']) ? $Repeater['layout_style'] : 'style-1';

        $output .= '<div class="tp-wp-radio tb-checkBox-data">';
            foreach($data as $value => $label){
                $RadioName = !empty($label['name']) ? $label['name'] : '';
                $RadioCount = !empty($label['count']) ? $label['count'] : '';
                $DataImage = !empty($label['image']) ? $label['image'] : '';

                $output .= '<div class="tp-radio '.esc_attr($layout_style).'">';

                    $output .= '<div class="tp-group">';
                        $output .= '<div class="tp-group-one">';
                            $output .= '<input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($value).'" id="tp-'.esc_attr($value).'" data-title="'.esc_attr($RadioName).'" />';
                            $output .= '<label for="tp-'.esc_attr($value).'" class="tp-lable">';
                                $output .= '<span class="tp-radio-icon">';
                                    $output .= '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="scrubber" class="radioIcon svg-inline--fa fa-scrubber fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 312c-35.3 0-64-28.7-64-64s28.7-64 64-64 64 28.7 64 64-28.7 64-64 64z"></path></svg>';
                                $output .= '</span>';
                                
                                if(!empty($DataImage) && !empty($ImageON)){
                                    $output .= '<span class="tp-radio-Img"><img src="'.esc_html($DataImage).'" class="tp-radio-thumbimg" /></span>';
                                }

                                $output .= '<div class="tp-field-content">';
                                    $output .= '<span class="tp-field-label">'.$RadioName.'</span>';
                                    $output .= ($layout_style == 'style-1' && !empty($RadioCount)) ? '<span class="tp-field-Counter">('.$RadioCount.')</span>' : '';
                                $output .= '</div>';

                            $output .= '</label>';
                        $output .= '</div>';

                        $output .= '<div class="tp-group-two">';
                            if(!empty($label['child']) && !empty($showchild)){
                                $output .= '<span class="tp-toggle"><i class="tog-plus fa fa-plus" aria-hidden="true"></i><i class="tog-minus fa fa-minus" aria-hidden="true"></i></span>';
                            }
                            $output .= ($layout_style == 'style-2' && !empty($showCnt)) ? '<span class="tp-field-Counter">'.$RadioCount.'</span>' : '';
                        $output .= '</div>';
                    $output .= '</div>';

                        if(!empty($label['child']) && !empty($showchild)){
                            $output .= '<div class="tp-child-taxo">';
                                foreach( $label['child'] as $child ){
                                    $ChildTermId = !empty($child->term_id) ? $child->term_id : '';
                                    $childName = !empty($child->name) ? $child->name : '';
                                    $CategoryCount = !empty($child->category_count) ?$child->category_count : 0;

                                    $ImageURL='';
                                    if( ($Taxonomy == 'product_cat') && !empty($ChildTermId)){
                                        $GetImgID = get_term_meta($ChildTermId, 'thumbnail_id', true);
                                        $GetImgurl = wp_get_attachment_image_src($GetImgID, 'tp-image-grid');
                                        if(!empty($GetImgurl)){
                                            $ImageURL = $GetImgurl[0];
                                        }
                                    }

                                    $output .= '<div class="tp-child-checkbox">';
                                        $output .= '<input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($ChildTermId).'" id="tp-'.esc_attr($ChildTermId).'" data-title="'.esc_attr($childName).'" />';
                                        $output .= '<label for="tp-'.esc_attr($ChildTermId).'" >';
                                            $output .= '<span class="tp-radio-icon">';
                                                $output .= '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="scrubber" class="radioIcon svg-inline--fa fa-scrubber fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 312c-35.3 0-64-28.7-64-64s28.7-64 64-64 64 28.7 64 64-28.7 64-64 64z"></path></svg>';
                                            $output .= '</span>';
                                            if(!empty($ImageURL) && !empty($ImageON)){
                                                $output .= '<span class="tp-radio-Img"><img src="'.esc_html($ImageURL).'" class="tp-radio-thumbimg" /></span>';
                                            }

                                            $output .= '<div class="tp-field-content">';
                                                    $output .= '<span class="tp-field-label">'.esc_html($childName).'</span>';
                                                if( $layout_style == 'style-1' && !empty($showCnt) ){
                                                    $output .= '<span class="tp-field-Counter">('.esc_html($CategoryCount).')</span>';
                                                }else if( $layout_style == 'style-2' && !empty($showCnt) ){
                                                    $output .= '<span class="tp-field-Counter">'.esc_html($CategoryCount).'</span>';
                                                }
                                            $output .= '</div>';
                                    $output .= '</div>';
                                }
                            $output .= '</div>';
                        }
                $output .= '</div>';
            }
            $output .= $this->tp_showmore_content($Repeater, 'radio');
        $output .= '</div>';

        return $output;
    }

    protected function tp_range($label,$max,$min,$step,$key,$Repeater){
        $sildAttr=[];
        $sildAttr['maxValue'] = $max;
        $sildAttr['minValue'] = $min;
        $sildAttr['stepValue'] = (int)$step;
        $sildAttr['type'] = isset($Repeater['ContentType']) ? $Repeater['ContentType'] :'';
        $sildAttr['field'] = 'range';
        $sildAttr['name'] = $key;
        $op='';
        
        $op .= $this->tp_filter_title($Repeater);
        $op .= '<div class="tp-range-silder">';
            $op .= '<div class="tp-range" data-sliderattr=\''.json_encode($sildAttr).'\' ></div>';
        $op .= '</div>';

        return $op;
    }

    protected function tp_date($title,$key,$Repeater){
        $lableOn = !empty($Repeater['lableDisable']) ? 1:0;
        $lableOne = (!empty($lableOn) && !empty($Repeater['lableOne_date'])) ? $Repeater['lableOne_date'] : '';
        $lableTwo = (!empty($lableOn) && !empty($Repeater['lableTwo_date'])) ? $Repeater['lableTwo_date'] : '';
        $lableStyle = (!empty($lableOn) && !empty($Repeater['lableStyleDate'])) ? $Repeater['lableStyleDate'] : 'default';

        $DateStyle='';
        if(!empty($lableOn) && $lableStyle == 'inline'){
            $DateStyle = "tp-date-inline";
        }

        $op='';
        $op .= $this->tp_filter_title($Repeater);
        $op .= '<div class="tp-date-wrap '.esc_attr($DateStyle).'">';
            $op .= '<div class="tp-date">';
                if(!empty($lableOn) && !empty($lableOne)){
                    $op .= '<label for="tp-date1">'.esc_html($lableOne).'</label>';
                }
                $op .= '<input id="'.esc_attr($key).'" name="tp-date" type="date" class="tp-datepicker1" date-key="'.esc_attr($key).'">';
            $op .= '</div>';
            $op .= '<div class="tp-date1">';
                if(!empty($lableOn) && !empty($lableTwo)){
                    $op .= '<label for="tp-date2">'.esc_html($lableTwo).'</label>';
                }
                $op .= '<input id="'.esc_attr($key).'" name="tp-date" type="date" class="tp-datepicker1">';
            $op .= '</div>';
        $op .= '</div>';
        
        return $op;
    }

    protected function tp_rating($Repeater,$name){
        $op='';
        $op .= $this->tp_filter_title($Repeater);
        //$op .= '<input name="rating-hidden" type="hidden" value="'.$name.'">';
        $op .= '<div class="tp-star-rating">';

            for ($i=1; $i<=5; $i++) {
                $value = 6-$i;
                $op .= '<input type="radio" name="'.$name.'" class="stars-'.esc_attr($value).'" id="'.esc_attr($value).'-stars" value="'.esc_attr($value).'" data-title="'.esc_attr($value).'" />';
                $op .= '<label for="'.esc_attr($value).'-stars" class="star tp-start-icon">&#9733;</label>';
            }

        $op .= '</div>';
        return $op;
    }

    protected function tp_alphabet($Repeater){
        $output='';
        $AlphabetType = !empty($Repeater['AlphabetType']) ? $Repeater['AlphabetType'] : array('alphabet');
        $Number = array(0,1,2,3,4,5,6,7,8,9);
        $character = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        $AlphaBet=[];
        if( count($AlphabetType) == 2 ){
            $AlphaBet = array_merge($character, $Number);
        }else{
            foreach ($AlphabetType as $Num) {
                if( $Num == 'alphabet' ){
                    $AlphaBet = $character;
                }else if( $Num == 'number' ){
                    $AlphaBet = $Number;
                }
            }
        }

        $output .= $this->tp_filter_title($Repeater);
        $output .= '<div class="tp-alphabet-wrapper">';
            foreach ($AlphaBet as $key => $value) {
                $output .= '<div class="tp-alphabet-content">
                                <label class="tp-alphabet-item" >
                                    <input type="checkbox" class="tp-alphabet-input" id="tp-'.esc_attr($value).'" name="alphabet" value="'.esc_attr($value).'" data-title="'.esc_attr($value).'">
                                    <span class="tp-alphabet-button">'.esc_html($value).'</span>
                                </label>
                            </div>';
            }
        $output .= '</div>'; 

        return $output;
    }

    protected function tp_tabbing_filter($data, $name, $showCnt, $Repeater){
        $output='';
        $WooSorting = !empty($Repeater['WooFiltersSort']) ? $Repeater['WooFiltersSort'] : '';
        $TabbingMedia = !empty($Repeater['TabbingContent']) ? $Repeater['TabbingContent'] : '';
        $ImageON=!empty($Repeater['Imageshow']) ? $Repeater['Imageshow'] : '';
        $TickIcon=!empty($Repeater['showtickIcon']) ? 'tp-tick-enable' : '';
        $output .= $this->tp_filter_title($Repeater);

        if(!empty($WooSorting)){
            $WooFiltersSelect = !empty($Repeater['WooFiltersSelect']) ? $Repeater['WooFiltersSelect'] : [];
            $WooName=[];
            if(!empty($WooFiltersSelect)){
                foreach ($WooFiltersSelect as $val) {
                    if($val == "featured"){ $WooName[$val] = 'Featured';
                    }else if($val == "on_sale"){ $WooName[$val] = 'On sale';
                    }else if($val == "top_sales"){ $WooName[$val] = 'Top Sales';
                    }else if($val == "instock"){ $WooName[$val] = 'In Stock';
                    }else if($val == "outofstock"){ $WooName[$val] = 'Out of Stock';
                    }
                }
            }

            $output .= '<div class="tp-tabbing tp-wootab-sorting '.esc_attr($TickIcon).'">';
                foreach ($WooName as $key => $value) {
                    $output .= '<div class="tp-tabbing-wrapper">';
                        $output .= '<label class="tp-tabbing-item">';
                            if($TabbingMedia == 'icon'){
                                if( !empty($Repeater['TabbingIconlib']) ){
                                    ob_start();
                                        \Elementor\Icons_Manager::render_icon( $Repeater['TabbingIconlib'], [ 'aria-hidden' => 'true' ]);
                                        $list_img = ob_get_contents();
                                    ob_end_clean();

                                    $output .= '<span class="tp-tabbing-media">'.$list_img.'</span>';
                                }
                            }else if($TabbingMedia == 'image'){
                                $Image = !empty($Repeater['TabbingImage']) ? $Repeater['TabbingImage']['url'] : THEPLUS_ASSETS_URL.'images/tp-placeholder.jpg';
                                $output .= '<span class="tp-tabbing-media"><img src="'.$Image.'" class="tp-tabbing-image" alt="atl"></span>';
                            }

                            $output .= '<input type="checkbox" class="tp-tabbing-input" id="woo_SgTabbing" name="woo_SgTabbing" value="'.esc_attr($key).'" data-title="'.esc_attr($value).'">';
                            $output .= '<span class="tp-tabbing-button">'.esc_html($value).'</span>';
                        $output .= '</label>';
                    $output .= '</div>';
                }

                $output .= $this->tp_showmore_content($Repeater, 'tabbing');
            $output .= '</div>';

            
        }else{
            $output .= '<div class="tp-tabbing '.esc_attr($TickIcon).'">';
                foreach ($data as $key => $value) {
                    $DataName = !empty($value['name']) ? $value['name'] : '';
                    $Count = !empty($value['count']) ? $value['count'] : '';
                    $DataImage = !empty($value['image']) ? $value['image'] : '';

                    $output .= '<div class="tp-tabbing-wrapper">';
                        $output .= '<label class="tp-tabbing-item">';
                            $output .= '<input type="checkbox" class="tp-tabbing-input" id="tp-'.esc_attr($key).'" name="'.esc_attr($name).'" value="'.esc_attr($key).'" data-title="'.esc_attr($DataName).'">';
                            if($TabbingMedia == 'icon'){
                                if( !empty($Repeater['TabbingIconlib']) ){
                                    ob_start();
                                        \Elementor\Icons_Manager::render_icon( $Repeater['TabbingIconlib'], [ 'aria-hidden' => 'true' ]);
                                        $list_img = ob_get_contents();
                                    ob_end_clean();

                                    $output .= '<span class="tp-tabbing-media">'.$list_img.'</span>';
                                }
                            }else if($TabbingMedia == 'image'){
                                $Image = !empty($Repeater['TabbingImage']) ? $Repeater['TabbingImage']['url'] : THEPLUS_ASSETS_URL.'images/tp-placeholder.jpg';
                                $output .= '<span class="tp-dy-tabbing-Img"><img src="'.esc_url($Image).'" class="tp-dy-tabbing-thumbimg" alt="atl"></span>';
                            }

                            if(!empty($DataImage) && !empty($ImageON)){
                                $output .= '<span class="tp-dy-tabbing-Img"><img src="'.esc_url($DataImage).'" class="tp-dy-tabbing-thumbimg" /></span>';
                            }

                                $output .= '<span class="tp-tabbing-button">'.esc_html($DataName).'</span>';
                            if(!empty($showCnt)){
                                $output .= '<span class="tp-tabbing-counter">'.esc_html($Count).'</span>';
                            }
                        $output .= '</label>';
                    $output .= '</div>';
                }

                $output .= $this->tp_showmore_content($Repeater, 'tabbing');
            $output .= '</div>';
        }
        return $output;
    }

    protected function tp_color_field($data, $name, $showCnt, $attr, $tooltip, $class, $Repeater){
        $ContentType = isset($Repeater['ContentType']) ? $Repeater['ContentType'] : '';
        $op = '';
        $op .= $this->tp_filter_title($Repeater);
        // $op .= '<input name="checkhidden" type="hidden" value="'.$attr.'" />';
        $op .= '<div class="tp-woo-color tp-row">';

            foreach($data as $value => $label){
                $DataName = !empty($label['name']) ? $label['name'] : '';
                $inputvalue = $value;
                if($ContentType == 'taxonomy'){
                    $color = get_term_meta( $value, 'product_attribute_color', true );
                }else if($ContentType == 'acf_conne'){
                    $color = $label['code'];
                    $inputvalue = $label['code'];
                    $value = str_replace( array( '#' ), '', $label['code']);
                }

                $op .= '<div class="tp-colorBox '.esc_attr($class).'">';
                    $op .= '<input type="checkbox" name="'.esc_attr($attr).'" value="'.esc_attr($inputvalue).'" id="tp-'.esc_attr($value).'" data-title="'.esc_attr($DataName).'" />';

                    $op .= '<label for="tp-'.esc_attr($value).'"> ';
                        $op .= '<div class="tp-color-wrap">';
                            $op .= '<span style="background-color:'.$color.'" class="tp-color-opt"></span>';
                                if(!empty($tooltip)){
                                    if(!empty($showCnt)){
                                        $op .= '<span class="tp-tooltip">'.esc_html($DataName).'('.esc_html($label['count']).') </span>';
                                    }else{
                                        $op .= '<span class="tp-tooltip">'.esc_html(ucwords($DataName)).' </span>';
                                    }
                                }
                        $op .= '</div>';
                    $op .= '</label>';

                $op .= '</div>';
            }
        $op .= '</div>';

        return $op;
    }  

    protected function tp_button_field( $data, $name, $showCnt, $attr, $tooltip, $class, $Repeater ){
        $op = '';
        $op .= $this->tp_filter_title($Repeater);
        // $op .= '<input name="checkhidden" type="hidden" value="'.$attr.'" />';        
        $op .= '<div class="tp-woo-button tp-row">';

        foreach($data as $value => $label){
            $Name = !empty($label['name']) ? $label['name'] : '';

            // $btnText = get_term_meta($value, 'product_attribute_button', true);

            $op .= '<div class="tp-buttonBox '.esc_attr($class).'">';
                $op .= '<input type="radio" name="'.esc_attr($attr).'" value="'.esc_attr($value).'" id="tp-'.esc_attr($value).'" data-title="'.esc_attr($Name).'" />';
                $op .= '<label for="tp-'.esc_attr($value).'"> ';
                    $op .= '<div class="tp-color-wrap">';
                        $op .= '<span class="tp-color-opt">'.esc_attr($Name).'</span>';
                        if( !empty($tooltip) ){
                            if( !empty($showCnt) ){
                                $op .= '<span class="tp-tooltip">'.$Name.'('.$label['count'].') </span>';
                            }else{
                                $op .= '<span class="tp-tooltip">'.$Name.' </span>';
                            }
                        }
                    $op .= '</div>';
                $op .= '</label>';
            $op .= '</div>';
        }
        $op .= '</div>';

        return $op;
    }

    protected function tp_image_field( $data, $name, $showCnt, $attr, $tooltip, $class, $Repeater ){
        $ContentType = isset($Repeater['ContentType']) ? $Repeater['ContentType'] : '';
        $op = '';
        $op .= $this->tp_filter_title($Repeater);
        // $op .= '<input name="checkhidden" type="hidden" value="'.$attr.'" />';
        $op .= '<div class="tp-woo-image tp-row">';
            foreach($data as $value => $label){
                $DataTitle = $DataName = !empty($label['name']) ? $label['name'] : '';
                if($ContentType == 'taxonomy'){
                    $attachment_id = absint( get_term_meta( $value, 'product_attribute_image', true ) );
                    $imageSrc = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                    $image = !empty($imageSrc[0]) ? $imageSrc[0] : THEPLUS_ASSETS_URL.'images/tp-placeholder.jpg';
                }else if($ContentType == 'acf_conne'){
                    $image = !empty($label['url']) ? $label['url'] : THEPLUS_ASSETS_URL.'images/tp-placeholder.jpg';
                    $DataTitle = !empty($label['title']) ? $label['title'] : '';
                }

                $op .= '<div class="tp-imgBox tp-col '.esc_attr($class).'">';
                    $op .= '<input type="checkbox" name="'.esc_attr($attr).'" value="'.esc_attr($value).'" id="tp-'.esc_attr($value).'" data-title="'.esc_attr($DataTitle).'" />';

                    $op .= '<label for="tp-'.esc_attr($value).'"> ';
                        $op .= '<div class="tp-img-wrap">';
                            $op .= '<span class="tp-img-opt">';
                                $op .= '<img src="'.esc_url($image).'" class="woo-img-tag" alt="'.esc_html__('pro_img','theplus').'" >';
                            $op .= '</span>';
                            if(!empty($tooltip)){
                                if(!empty($showCnt)){
                                    $op .= '<span class="tp-tooltip">'.$DataTitle.'('.$label['count'].') </span>';
                                }else{
                                    $op .= '<span class="tp-tooltip">'.$DataTitle.' </span>';
                                }
                            }
                        $op .= '</div>';
                    $op .= '</label>';
                $op .= '</div>';
                
            }
        $op .= '</div>';
        return $op;
    }

    Protected function tp_showmore_content($Repeater, $value){
        $ShowMore = !empty($Repeater['ShowMore']) ? $Repeater['ShowMore'] : false;
        $Moretxt = !empty($Repeater['showmoretxt']) ? $Repeater['showmoretxt'] : '';
        $ScrollOn = !empty($Repeater['scrollOn']) ? true : false;

        $op='';
        if(!empty($ShowMore)){
            $ShowMoreData = json_encode(array(
                'className' => $value,
                'ShowOn' => $ShowMore,
                'ShowValue' => !empty($Repeater['MoreDefault']) ? (int)$Repeater['MoreDefault'] : 3,
                'ShowMoretxt' => $Moretxt,
                'Showlesstxt' => !empty($Repeater['showlesstxt']) ? $Repeater['showlesstxt'] : '',
                'ScrollclassName' => 'tp-normal-scroll',
                'scrollOn' => $ScrollOn,
                'scrollheight' => (!empty($Repeater['height_scroll'])) ? (int)$Repeater['height_scroll']['size'] : 150,
            ), true);

            $op .= '<div class="tp-tabbing-redmore">';
                $op .= '<a class="tp-filter-readmore ShowMore" data-ShowMore="'.esc_attr($ShowMoreData).'" >'.esc_html($Moretxt).'</a>';
            $op .= '</div>';
        }

        return $op;
    }

    Protected function tp_filter_button($settings){
        $FilterBtn = !empty($settings['FilterBtn']) ? $settings['FilterBtn'] : false;
        $DefNumber = !empty($settings['TogBtnNum']) ? $settings['TogBtnNum'] : 3;
        $TextBtn = !empty($settings['TogBtnTitle']) ? $settings['TogBtnTitle'] : '';        
        $TogBtnTitleLess = !empty($settings['TogBtnTitleLess']) ? $settings['TogBtnTitleLess'] : '';
        $MediaBtn = !empty($settings['ToggleMedia']) ? $settings['ToggleMedia'] : '';
        $MediaPos = !empty($settings['TogMPosition']) ? $settings['TogMPosition'] : 'start';
        $IconBtn = (!empty($settings['ToggleBtnIcon']) && !empty($settings['ToggleBtnIcon']['value'])) ? $settings['ToggleBtnIcon']['value'] : 'fas fa-sliders-h';
        $ImageBtn = (!empty($settings['Toggleimage']) && !empty($settings['Toggleimage']['url'])) ? $settings['Toggleimage']['url'] : THEPLUS_ASSETS_URL .'images/placeholder-grid.jpg';
        
        $Btncolumn='';
        $BtnColumnOn = !empty($settings['EnableBtnColumn']) ? 1 : 0;
        if(!empty($BtnColumnOn)){
            $BtnDesktop = !empty($settings['BtnDesktop']) ? $settings['BtnDesktop'] : 6;
            $BtnTablet = !empty($settings['BtnTablet']) ? $settings['BtnTablet'] : 6;
            $BtnMobile = !empty($settings['BtnMobile']) ? $settings['BtnMobile'] : 6;
            $Btncolumn = $this->tp_search_column($BtnDesktop, $BtnTablet, $BtnMobile);
        }

        $BtnValue = json_encode( array( 'Number'=>$DefNumber, 'showmore'=>$TextBtn, 'showless'=>$TogBtnTitleLess, ), true);

        $GetMedia='';
        if($MediaBtn == 'icon' && !empty($settings['ToggleBtnIcon'])){
            ob_start();
                \Elementor\Icons_Manager::render_icon($settings['ToggleBtnIcon'],['aria-hidden'=>'true']);
                $Icon = ob_get_contents();
            ob_end_clean();	
            $GetMedia = '<span class="tp-button-icon">'.$Icon.'</span>';
        }else if($MediaBtn == 'image' && !empty($ImageBtn)){
            $GetMedia = '<span class="tp-button-Image"><img src="'.esc_url($ImageBtn).'" class="tp-button-ImageTag" ></span>';
        }

        $op='';
        if(!empty($FilterBtn)){
            $op .= '<div class="tp-button-filter '.esc_attr($Btncolumn).'" data-Button-Filter= \''.$BtnValue.'\'>';
                $op .= '<button class="tp-toggle-button '.esc_attr($MediaPos).'">';
                    $op .= ($MediaPos == 'start') ? $GetMedia : '';
                    $op .= (!empty($TextBtn)) ? '<span class="tp-button-text">'.esc_html($TextBtn).'</span>' : '';
                    $op .= ($MediaPos == 'end') ? $GetMedia : '';
                $op .= '</button>';
            $op .= '</div>';
        }

        return $op;
    }

    Protected function Filter_ErrorShow($Massage){
        return "<div class='Sf-Error-Handal'> {$Massage} </div>";
    }

    protected function tp_search_column($Desktop, $Tablet, $Mobile){
		$column = 'tp-col-lg-'.esc_attr($Desktop);
		$column .= ' tp-col-md-'.esc_attr($Tablet);
		$column .= ' tp-col-sm-'.esc_attr($Mobile);
		$column .= ' tp-col-'.esc_attr($Mobile);

		return $column;
	}

    protected function content_template() {
       
    }
}