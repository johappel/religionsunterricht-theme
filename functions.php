<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'ct-main-styles' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

add_filter("theme_mod_blog_archive_order", 'theme_mod_filter_grid');

add_filter("theme_mod_categories_archive_order", 'theme_mod_filter_grid');

function theme_mod_filter_grid($default){

    $default = [
	    [
		    'id' => 'post_meta',
		    'enabled' => true,
		    'meta_elements' => [
			    [
				    'id' => 'categories',
				    'enabled' => false,
			    ],
			    [
				    'id' => 'klassenstufe',
				    'enabled' => true,
			    ],

			    [
				    'id' => 'themen',
				    'enabled' => true,
			    ],
		    ],
	    ],

	    [
		    'id' => 'title',
		    'enabled' => true,
	    ],

	    [
		    'id' => 'featured_image',
		    'enabled' => true,
	    ],

	    [
		    'id' => 'excerpt',
		    'enabled' => true,
	    ],

	    [
		    'id' => 'read_more',
		    'enabled' => false,
	    ],

	    [
		    'id' => 'post_meta',
		    'enabled' => true,
		    'meta_elements' => [
			    [
				    'id' => 'author',
				    'enabled' => false,
			    ],

			    [
				    'id' => 'autoren',
				    'enabled' => true,
			    ],

			    [
				    'id' => 'post_date',
				    'enabled' => true,
			    ],

			    [
				    'id' => 'comments',
				    'enabled' => true,
			    ],
		    ],
	    ],
    ];

    return $default;

}

function blocksy_get_categories_list($between = '', $is_category = true, $taxonomieName ='') {

    global $post;


	$prefix = blocksy_manager()->screen->get_prefix();

	if($taxonomieName !== ''){
		$tax = array($taxonomieName);
    }else{
		switch($prefix){

			case "single_blog_post":
				$tax = ['klassenstufe', 'themen', 'lizenz'];
				$taxonomieName = 'ct';

				break;
			case "blog":
				$tax = array($taxonomieName);
				break;
			case 'categories':
				$tax = ['klassenstufe', 'themen', 'lizenz'];
				break;
			case 'digitale_werkzeuge':
				$tax = ['digitale_werkzeuge'];
				break;
			default:

				//var_dump($prefix);

				$tax = get_taxonomies([
					'public'   => true,
					'_builtin' => false

				]);
		}
    }






	$post_type = get_post_type($post);
	$metatax = array();

	if (in_array($post_type ,['post','netzwerke', 'digitale_werkzeuge', ])) {

		$taxonomies = array_values(array_diff(
			get_object_taxonomies($post_type),
			['post_format']
		));


		if (count($taxonomies) > 0) {
			foreach ($taxonomies as $single_taxonomy) {

				if($single_taxonomy !== 'autoren'){
					$between ='';

				}

				if(  in_array(  $single_taxonomy , $tax )){

				    $before = $after = '';

                    if(!$between){
                        $between = '</span><span  class="meta-'.$single_taxonomy.'">';
                        $before = '<span class="meta-'.$single_taxonomy.'">';
                        $after = "</span>";

                    }else{
                        $between = str_replace('category', $single_taxonomy, $between);
                        $before = str_replace('category', $single_taxonomy, $before);
                        $between = str_replace('post_tag', $single_taxonomy, $between);
                        $before = str_replace('post_tag', $single_taxonomy, $before);
                    }
                    if($between == ', '){
                        $between = '</span>, <span  class="meta-'.$single_taxonomy.'">';
                        $before = '<span class="meta-'.$single_taxonomy.'">';
                        $after = "</span>";
                    }



                    if($single_taxonomy === 'autoren'){
                        $between = '</span>, <span  class="meta-'.$single_taxonomy.'">';
                        $before = '<span class="meta-by">Von</span><span class="meta-'.$single_taxonomy.'">';
                        $after = "</span>";
                    }




					$metatax[] = get_the_term_list($post,$single_taxonomy,$before,$between,$after);

				}
				if(  in_array(  $single_taxonomy , ['klassenstufe'] )){
					//$metatax[] = '<br />';
				}

			}

			return implode("\n", $metatax);

		} else {
			return '';
		}


	}

}


function blocksy_post_meta($post_meta_descriptor = null, $args = []) {


    $prefix = blocksy_manager()->screen->get_prefix();
    //echo var_dump($prefix);
    //echo var_dump($post_meta_descriptor);



	switch ($prefix){
        case "single_blog_post":
	        if($post_meta_descriptor[0]['id'] === 'author'){
		        $post_meta_descriptor[0]['enabled'] = false;
	            $post_meta_descriptor[2]['id'] = 'autoren';
		        $post_meta_descriptor[2]['has_author_avatar']= 'no';
		        $args['meta_type'] = 'icons';
		        $args["meta_divider"] = "none";

	        }
	        if($post_meta_descriptor[0]['id'] === 'categories') {
		        $post_meta_descriptor[2]['id'] = 'lizenz';
		        $post_meta_descriptor[2]['enabled']           = true;
	        }
            break;
        case "netzwerke_single":
        case "digitale_werkzeuge_single":
	        if($post_meta_descriptor[0]['id'] === 'author'){
		        $post_meta_descriptor[0]['enabled'] = false;

	        }
            break;
		case "blog":
		case "categories":
			if($post_meta_descriptor[0]['id'] === 'author') {
				$post_meta_descriptor[0]['id']                = 'author';
				$post_meta_descriptor[0]['enabled']           = false;
				$post_meta_descriptor[1]['id']                = 'autoren';
				$post_meta_descriptor[1]['enabled']           = true;
			}
		if($post_meta_descriptor[0]['id'] === 'categories') {
			$args["meta_divider"] = "none";
			$args["meta_type"] = "icons";

		}
			break;
        case "netzwerke_archive":
        case "digitale_werkzeuge_archive":

            if($post_meta_descriptor[0]['id'] === 'categories') {

			    $post_meta_descriptor[0]['enabled']           = false;
			    $post_meta_descriptor[2]['enabled']           = false;
			    $post_meta_descriptor[1]['enabled']           = false;

		    }

	        if($post_meta_descriptor[0]['id'] === 'author') {
		        $post_meta_descriptor[0]['id']                = 'author';
		        $post_meta_descriptor[0]['enabled']           = false;
		        $post_meta_descriptor[1]['meta_divider'] = '<br>';
		        $post_meta_descriptor[2]['id']                = 'werkzeug_kategorie';
		        $post_meta_descriptor[2]['enabled']           = true;
                $post_meta_descriptor[3]['id']                = 'katekorie_netzwerke';
		        $post_meta_descriptor[3]['enabled']           = true;

	        }
            break;
    }




    if (! $post_meta_descriptor) {
        $post_meta_descriptor = blocksy_post_meta_defaults([
            [
                'id' => 'author',
                'enabled' => true,
            ],
            [
                'id' => 'autoren',
                'enabled' => true,
            ],
            [
                'id' => 'comments',
                'enabled' => true,
            ],

            [
                'id' => 'post_date',
                'enabled' => true,
            ],

            [
                'id' => 'updated_date',
                'enabled' => true,
            ],

            [
                'id' => 'categories',
                'enabled' => true,
            ],
            [
                'id' => 'klassenstufe',
                'enabled' => true
            ],
            [
                'id' => 'themen',
                'enabled' => true
            ],
	        [
		        'id' => 'lizenz',
		        'enabled' => true
	        ],
	        [
                'id' => 'tags',
                'enabled' => true,
            ],
        ]);
    }

    $args = wp_parse_args(
        $args,
        [
            'class' => '',
            'meta_type' => 'simple',
            'meta_divider' => 'none',

            'force_icons' => false,

            'attr' => []
        ]
    );

    $has_any_enabled_element = false;

	foreach ($post_meta_descriptor as $index => $single_meta) {


        global $post;


        if (get_post_type($post) === 'page') {
            if ($single_meta['id'] === 'comments') {
                $post_meta_descriptor[$index]['enabled'] = false;
            }

            if (
                $single_meta['id'] === 'categories'
                &&
                $single_meta['enabled']
            ) {
                $post_meta_descriptor[$index]['enabled'] = in_array(
                    'category',
                    get_object_taxonomies('page')
                );
            }

            if (
                $single_meta['id'] === 'tags'
                &&
                $single_meta['enabled']
            ) {
                $post_meta_descriptor[$index]['enabled'] = in_array(
                    'post_tag',
                    get_object_taxonomies('page')
                );
            }
        }




        if (
            ($single_meta['id'] === 'author' || $single_meta['id'] === 'autoren')
            &&
            ! isset($single_meta['label'])
        ) {
            $post_meta_descriptor[$index]['label'] = __('By', 'blocksy');
        }

        if (
            (
                $single_meta['id'] === 'post_date'
                ||
                $single_meta['id'] === 'updated_date'
            ) && ! isset($single_meta['label'])
        ) {
            $post_meta_descriptor[$index]['label'] = __('On', 'blocksy');
        }

        if (
            (

                $single_meta['id'] === 'categories'
                ||
                $single_meta['id'] === 'tags'
                ||
                $single_meta['id'] === 'klassenstufe'
                ||
                $single_meta['id'] === 'themen'



            ) && ! isset($single_meta['label'])
        ) {
            $post_meta_descriptor[$index]['label'] = __('In', 'blocksy');
        }

        if ($post_meta_descriptor[$index]['enabled']) {
            $has_any_enabled_element = true;
        }
    }



    if (! $has_any_enabled_element) {
        return '';
    }

    $default_date_format = get_option('date_format', '');

    if (! empty($args['class'])) {
        $args['class'] = ' ' . $args['class'];
    }

    // Author ID
    global $post;
    $user_id = $post->post_author;

    global $authordata;

    if (! $authordata) {
        $authordata = get_userdata($user_id);
    }

    $container_attr = array_merge([
        'class' => 'entry-meta' . $args['class'],
        'data-type' => $args['meta_type'] . ':' . $args['meta_divider']
    ], $args['attr']);


	ob_start();

    foreach ($post_meta_descriptor as $single_meta) {
        if (! $single_meta['enabled']) {
            continue;
        }

        if (
            $single_meta['id'] === 'author'
            &&
            get_the_author()
        ) { ?><li class="meta-author" <?php echo blocksy_schema_org_definitions('author_name') ?>><?php
            if ($single_meta['has_author_avatar'] === 'yes') {
                echo blocksy_simple_image(
                    get_avatar_url(
                        get_the_author_meta('ID'),
                        [
                            'size' => intval($single_meta['avatar_size']) * 2
                        ]
                    ),
                    [
                        'tag_name' => 'a',
                        'suffix' => 'static',
                        'ratio_blocks' => false,
                        'html_atts' => [
                            'href' => get_author_posts_url(get_the_author_meta('ID')),
                        ],
                        'img_atts' => [
                            'width' => intval($single_meta['avatar_size']),
                            'height' => intval($single_meta['avatar_size']),
                            'style' => 'height:' . intval($single_meta['avatar_size']) . 'px',
                        ],
                    ]
                );
            }

            if ($args['meta_type'] === 'label') {
                echo '<span>' . esc_html($single_meta['label']) . '</span>';
            }

            if ($args['meta_type'] === 'icons' || $args['force_icons']) { ?>
                <svg width="13" height="13" viewBox="0 0 15 15">
                    <path d="M13.6,1.4c-1.9-1.9-4.9-1.9-6.8,0L2.2,6C2.1,6.1,2,6.3,2,6.5V12l-1.8,1.8c-0.3,0.3-0.3,0.7,0,1C0.3,14.9,0.5,15,0.7,15s0.3-0.1,0.5-0.2L3,13h5.5c0.2,0,0.4-0.1,0.5-0.2l2.7-2.7c0,0,0,0,0,0l1.9-1.9C15.5,6.3,15.5,3.3,13.6,1.4z M8.2,11.6H4.4l1.4-1.4h3.9L8.2,11.6z M12.6,7.2L11,8.9H7.1l3.6-3.6c0.3-0.3,0.3-0.7,0-1C10.4,4,10,4,9.7,4.3L5,9.1c0,0,0,0,0,0l-1.6,1.6V6.8l4.4-4.4c1.3-1.3,3.5-1.3,4.8,0C14,3.7,14,5.9,12.6,7.2C12.6,7.2,12.6,7.2,12.6,7.2z"/>
                </svg>
            <?php }

            global $authordata;

            echo blocksy_html_tag('a', array_merge([
                'class' => 'ct-meta-element-author',
                'href' => esc_url(get_author_posts_url($authordata->ID, $authordata->user_nicename)),
                /* translators: %s: Author's display name. */
                'title' => esc_attr(sprintf(__('Posts by %s', 'blocksy'), get_the_author())),
                'rel' => 'author',
            ], blocksy_schema_org_definitions('author_link', [
                'array' => true
            ]), (
            $args['meta_type'] === 'label' ? [
                'data-label' => __( 'By', 'blocksy' )
            ] : []
            )), get_the_author());

            ?></li><?php }



        if ($single_meta['id'] === 'post_date') {
            ?><li class="meta-date" <?php echo blocksy_schema_org_definitions('publish_date') ?>><?php
            if ($args['meta_type'] === 'icons' || $args['force_icons']) {
                ?><svg width="13" height="13" viewBox="0 0 15 15">
                    <path d="M7.5,0C3.4,0,0,3.4,0,7.5S3.4,15,7.5,15S15,11.6,15,7.5S11.6,0,7.5,0z M7.5,13.6c-3.4,0-6.1-2.8-6.1-6.1c0-3.4,2.8-6.1,6.1-6.1c3.4,0,6.1,2.8,6.1,6.1C13.6,10.9,10.9,13.6,7.5,13.6z M10.8,9.2c-0.1,0.2-0.4,0.4-0.6,0.4c-0.1,0-0.2,0-0.3-0.1L7.2,8.1C7,8,6.8,7.8,6.8,7.5V4c0-0.4,0.3-0.7,0.7-0.7S8.2,3.6,8.2,4v3.1l2.4,1.2C10.9,8.4,11,8.8,10.8,9.2z"/>
                </svg><?php
            }

            if ($args['meta_type'] === 'label') {
                echo '<span>' . esc_html($single_meta['label']) . '</span>';
            }

            $date_format = $single_meta['date_format'];

            if ($single_meta['date_format_source'] === 'default') {
                $date_format = $default_date_format;
            }

            echo blocksy_html_tag(
                'time',
                array_merge([
                    'class' => 'ct-meta-element-date',
                    'datetime' => get_the_date('c')
                ], (
                ($args['meta_type'] === 'label') ? ([
                    'data-label' => __( 'On', 'blocksy' )
                ]) : []
                ), (
                is_customize_preview() ? [
                    'data-default-format' => $default_date_format,
                    'data-date' => get_the_date('c')
                ] : []
                )),
                esc_html(get_the_date($date_format))
            );
            ?></li><?php }

        if ($single_meta['id'] === 'updated_date') {
            ?><li class="meta-updated-date" <?php echo blocksy_schema_org_definitions('modified_date') ?>><?php
            if ($args['meta_type'] === 'icons' || $args['force_icons']) {
                ?><svg width="13" height="13" viewBox="0 0 15 15">
                    <path d="M7.5,0C3.4,0,0,3.4,0,7.5S3.4,15,7.5,15S15,11.6,15,7.5S11.6,0,7.5,0z M7.5,13.6c-3.4,0-6.1-2.8-6.1-6.1c0-3.4,2.8-6.1,6.1-6.1c3.4,0,6.1,2.8,6.1,6.1C13.6,10.9,10.9,13.6,7.5,13.6z M8.2,4v3.5C8.2,7.8,8,8,7.8,8.1L5.1,9.5C5,9.5,4.9,9.5,4.8,9.5c-0.3,0-0.5-0.1-0.6-0.4C4,8.8,4.1,8.4,4.5,8.3l2.4-1.2V4c0-0.4,0.3-0.7,0.7-0.7S8.2,3.6,8.2,4z"/>
                </svg><?php
            }

            if ($args['meta_type'] === 'label') {
                echo '<span>' . esc_html($single_meta['label']) . '</span>';
            }

            $date_format = $single_meta['date_format'];

            if ($single_meta['date_format_source'] === 'default') {
                $date_format = $default_date_format;
            }

            $proper_updated_date = intval(get_the_modified_date('U')) < intval(
                get_the_date('U')
            ) ? get_the_date($date_format) : get_the_modified_date($date_format);

            $proper_updated_date_initial = intval(get_the_modified_date('U')) < intval(
                get_the_date('U')
            ) ? get_the_date('c') : get_the_modified_date('c');


            echo blocksy_html_tag(
                'time',

                array_merge([
                    'class' => 'ct-meta-element-date',
                    'datetime' => $proper_updated_date_initial
                ], (
                $args['meta_type'] === 'label' ? [
                    'data-label' => __( 'On', 'blocksy' )
                ] : []
                ), (
                is_customize_preview() ? [
                    'data-default-format' => $default_date_format,
                    'data-date' => $proper_updated_date_initial
                ] : []
                )),

                esc_html($proper_updated_date)
            );
            ?></li><?php }

        if ($single_meta['id'] === 'comments' && get_comments_number() > 0) {
            ?><li class="meta-comments"><?php
            if ($args['meta_type'] === 'icons' || $args['force_icons']) {
                ?><svg width="13" height="13" viewBox="0 0 15 15">
                    <path d="M13.7,14.8L10.9,12H2.2C1,12,0,11,0,9.8l0-7.5C0,1,1,0,2.2,0l10.5,0C14,0,15,1,15,2.2v12c0,0.3-0.2,0.6-0.5,0.7c-0.1,0-0.2,0.1-0.3,0.1C14.1,15,13.9,14.9,13.7,14.8zM2.2,1.5c-0.4,0-0.8,0.3-0.8,0.8v7.5c0,0.4,0.3,0.8,0.8,0.8h9c0.2,0,0.4,0.1,0.5,0.2l1.7,1.7V2.2c0-0.4-0.3-0.8-0.8-0.8H2.2z"/>
                </svg><?php
            }

            ?><a href="<?php echo esc_attr(get_permalink()); ?>#comments"><?php
            // translators: text for one review
            $singular_text = __('1 Comment', 'blocksy');
            // translators: % refers to the number of comments, when more than 1
            $plural_text = __('% Comments', 'blocksy');

            if ( get_post_type() === 'product' ) {
                // translators: text for one review
                $singular_text = __('1 Review', 'blocksy');
                // translators: % refers to the number of reviews, when more than 1
                $plural_text = __('% Reviews', 'blocksy');
            }

            if ($args['meta_type'] === 'icons' && !$args['force_icons']) {
                $singular_text = '1';
                $plural_text = '%';
            }

            echo wp_kses_post(get_comments_number_text(
                '',
                $singular_text,
                $plural_text
            ));
            ?></a></li><?php }

        if ($single_meta['id'] === 'categories' && blocksy_get_categories_list()) {
            if (! isset($single_meta['style'])) {
                $single_meta['style'] = 'simple';
            }

            $divider = '';

            if ($single_meta['style'] === 'simple') {
                $divider = ', ';
            }

            if ($single_meta['style'] === 'underline') {
                $divider = ' / ';
            }

            echo '<li class="meta-categories" data-type="' . esc_attr($single_meta['style']) . '">';

            if ($args['meta_type'] === 'icons' || $args['force_icons']) {
                echo '<svg width="13" height="13" viewBox="0 0 15 15"><path d="M14.4,1.2H0.6C0.3,1.2,0,1.5,0,1.9V5c0,0.3,0.3,0.6,0.6,0.6h0.6v7.5c0,0.3,0.3,0.6,0.6,0.6h11.2c0.3,0,0.6-0.3,0.6-0.6V5.6h0.6C14.7,5.6,15,5.3,15,5V1.9C15,1.5,14.7,1.2,14.4,1.2z M12.5,12.5h-10V5.6h10V12.5z M13.8,4.4H1.2V2.5h12.5V4.4z M5.6,7.5c0-0.3,0.3-0.6,0.6-0.6h2.5c0.3,0,0.6,0.3,0.6,0.6S9.1,8.1,8.8,8.1H6.2C5.9,8.1,5.6,7.8,5.6,7.5z"/></svg>';
            }

            if ($args['meta_type'] === 'label') {
                echo '<span>' . esc_html($single_meta['label']) . '</span>';
            }

            echo wp_kses_post(blocksy_get_categories_list($divider));

            echo '</li>';
        }

        if (
		    in_array( $single_meta['id'] , get_taxonomies([
			    'public'   => true,
			    '_builtin' => false

		    ]))   &&
               blocksy_get_categories_list('', false, $single_meta['id'])
        ) {

            if (
                $args['meta_type'] === 'icons'
                ||
                ! isset($single_meta['style'])
            ) {
                $single_meta['style'] = 'simple';
            }

            $divider = '';

            if ($single_meta['style'] === 'simple') {
                $divider = ', ';
            }

            if ($single_meta['style'] === 'underline') {
                $divider = '/';
            }
            echo '<li class="meta-tags" data-type="' . esc_attr($single_meta['style']) . '">';

            if ($args['meta_type'] === 'icons' || $args['force_icons'] ) {
                echo '<svg width="13" height="13" viewBox="0 0 15 15"><path d="M5.7,14.4L0.6,9.3c0,0,0,0,0,0c-0.8-0.8-0.8-2.2,0-3l6.1-6.1C6.8,0.1,7,0,7.2,0l7.1,0C14.7,0,15,0.3,15,0.7v7.1c0,0.2-0.1,0.4-0.2,0.5l-6.1,6.1c-0.4,0.4-1,0.6-1.5,0.6C6.7,15,6.1,14.8,5.7,14.4zM13.6,1.4H7.5L1.6,7.3c-0.3,0.3-0.3,0.7,0,1l5.1,5.1c0.3,0.3,0.7,0.3,1,0l5.9-5.9V1.4zM1.1,8.8L1.1,8.8L1.1,8.8zM10.7,5c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7h0c-0.4,0-0.7,0.3-0.7,0.7C10,4.6,10.4,5,10.7,5z"/></svg>';
            }

            if ($args['meta_type'] === 'label') {
                echo '<span>' . esc_html($single_meta['label'], 'blocksy') . '</span>';
            }
            echo wp_kses_post(blocksy_get_categories_list($divider, false, $single_meta['id']));

            echo '</li>';
        }
    }

    $to_return = ob_get_contents();

    ob_end_clean();

    if (empty(trim($to_return))) {
        return '';
    }

    ob_start();


    echo '<ul ' . blocksy_attr_to_html($container_attr) . ' ' . blocksy_schema_org_definitions('blog') . '>';

    /**
     * Note to code reviewers: This line doesn't need to be escaped.
     * Var $to_return used here has the value escaped properly.
     */
    echo trim(preg_replace('/\s\s+/', ' ', apply_filters(
        'blocksy:post-meta:items',
        $to_return,
        $post_meta_descriptor,
        $args
    )));

    echo '</ul>';

    return ob_get_clean();
}

function blocksy_entry_excerpt($length = 40, $class = 'entry-excerpt', $post_id = null) {

    global $post;


	$has_native_excerpt = $post->post_excerpt;

	$excerpt = trim(get_metadata('post',$post->ID,'excerpt',true),$length);

	if (! $excerpt && $has_native_excerpt) {
		$excerpt = get_the_excerpt($post_id);
	}

	if (! $excerpt) {

		ob_start();
		blocksy_trim_excerpt(get_the_excerpt($post_id), $length);
		$excerpt = trim(ob_get_clean());

	}

	ob_start();

	?>

    <div class="<?php echo esc_attr($class) ?>">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wp_kses_post($excerpt);
		?>
    </div>

	<?php

	return ob_get_clean();
}
add_filter('the_content','add_meta_ecxerpt');
function add_meta_ecxerpt($content){

    global $post;

    if($post->post_type !== 'post'){
        return $content;
    }

    $excerpt = trim(get_metadata('post',$post->ID,'excerpt',true));
    if(!$excerpt){
        return $content;
    }

	$excerpt = wp_kses_post('<p class="intro_excerpt">' . $excerpt . '</p>');



    return $excerpt.$content;
}
function fwp_home_custom_query( $query ) {

   //var_dump($query); die();

}
add_filter( 'pre_get_posts', 'fwp_home_custom_query' );

add_action('blocksy:hero:before', 'show_before_title');
function show_before_title (){
    global $post;
	if($post->post_name === 'erweiterte-suche'){
	    echo '<div style="display:none;">';
    }

}
add_action('blocksy:hero:after', 'show_after_title');
function show_after_title (){
	global $post;
	if($post->post_name === 'erweiterte-suche') {
		echo '</div>';
	}

}
