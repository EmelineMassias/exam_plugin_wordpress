<?php
/*
Plugin Name: ACF Additional Form
Description: Ajout de balises dans ACF
Version: 1.0
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    //Le plugin woocommerce est activé
    echo '<h1 style="color:blue; text-align:center;"> Le Plugin Woocommerce est activé </h1>';
} else {
    echo " <h1 style='color:red; text-align:center;'> Attention, le Plugin Woocommerce n'est pas activé </h1>";
}
if (in_array('advanced-custom-fields/acf.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    //plugin acf est activé
    echo '<h1 style="color:blue; text-align:center;"> Le Plugin ACF est activé </h1>';
} else {
    echo "<h1 style='color:red; text-align:center;'> Attention, le Plugin ACF n'est pas activé </h1>";
}





add_action('acf/init', 'my_acf_init');
function my_acf_init()
{

    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(
            array(
                'key' => 'group_1',
                'title' => 'Informations Spécifiques',
                'fields' => array(
                    array(
                        'key' => 'field_1',
                        'label' => "Date de l'événement",
                        'name' => 'sub_date',
                        'type' => 'date_picker',
                    ),
                    array(
                        'key' => 'field_2',
                        'label' => "Heure de l'événement ",
                        'name' => 'sub_time',
                        'type' => 'time_picker',
                    ),
                    array(
                        'key' => 'field_3',
                        'label' => 'Description',
                        'name' => 'sub_description',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_4',
                        'label' => 'Informations privées',
                        'name' => 'sub_info',
                        'type' => 'text',
                    )
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'product',
                        ),
                    ),
                ),
            )
        );

    endif;
}

add_action('init', 'acf_init_shortcode');

function acf_init_shortcode()
{
    add_shortcode('acf', 'acf_add_info_shortcode');
}


function acf_add_info_shortcode($attribut, )
{
    $info = '<div class="style">
    <h4>Informations spécifiques du concert</h4><br>
    <p><u><b>Date(s) :</b></u> ' . get_field('sub_date') . ' </p>
    <p><u><b>Heure :</b></u> ' . get_field('sub_time') . ' </p>
    <p><u><b>Description :</b></u> ' . get_field('sub_description') . ' </p>
    <p><u><b>Informations complémentaires :</b></u> ' . get_field('sub_info') . ' </p>
    <div id="compte_a_rebours" style="color: white; font-weight: bolder; font-size: x-large; "></div>
    </div>';

    return $info;
}
//Ajout du fichier CSS et JS
add_action('wp_enqueue_scripts', 'acf_stylesheet');
function acf_stylesheet()
{
    wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'acf_script');
function acf_script()
{
    wp_enqueue_script('script', plugins_url('js/script.js', __FILE__), array('jquery'), '', true);
}


// Create function to check if client bought a product from array A
function check_is_category_A_customer()
{
    global $woocommerce;
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
    $billing_email = $current_user->email();

    if (wc_customer_bought_product($billing_email, $user_id, '2258')) {
        return true;
    } else if (wc_customer_bought_product($billing_email, $user_id, '2253')) {
        return true;
    } else if (wc_customer_bought_product($billing_email, $user_id, '2242')) {
        return true;
    }

    return false;
}

// Create shortcode to display menu for customers cat A
add_shortcode('bought', 'check_cat_bought_A');
function check_cat_bought_A($atts, $content = null)
{
    if (check_is_category_A_customer()) {
        $content = '<div class="style">
        <p><u><b>Informations complémentaires :</b></u> ' . get_field('sub_info') . ' </p>
        </div>';
        return $content;
    }
}

