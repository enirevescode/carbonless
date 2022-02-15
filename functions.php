<?php

 ///// Retirer les éléments inutiles de wp /////


 function sgtheme_remove_menu_pages() {
	//remove_menu_page( 'tools.php' );
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'sgtheme_remove_menu_pages' );

// constante pour la version.
define('sgtheme_version', '3.1.1');
//chargement de scripts
//chargement dans le front-end
function sgtheme_scripts()
{
//==========================================
	//======= chargement des styles =========
//==========================================

	wp_enqueue_style(
		'sgtheme_bootstrap-style',
		get_template_directory_uri() . '/css/bootstrap.min.css',
		array(), sgtheme_version, 'all');
	//lien avec style
	wp_enqueue_style(
		'parent-style',
		get_template_directory_uri() . '/style.css',
		array('sgtheme_bootstrap-style'), sgtheme_version, 'all');
	
// chargement des scripts
// le menu hamburger
	wp_enqueue_script('popper-js', get_template_directory_uri() . '/js/popper.min.js', array('jquery'), sgtheme_version, true);
	
	wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery', 'popper-js'), sgtheme_version, true);

	wp_enqueue_script('sgtheme_script', get_template_directory_uri() . '/js/sgtheme.js', array('jquery', 'bootstrap-js'), sgtheme_version, true);
}

add_action('wp_enqueue_scripts', 'sgtheme_scripts');

// prise en charge des images mises en avant
add_theme_support('post-thumbnails');

// chargement dans l'admin. de wp
function sgtheme_admin_scripts()
{

// chargement des styles dans l'admin
	wp_enqueue_style('sgtheme_bootstrap-style', get_template_directory_uri() . '/css/bootstrap.min.css', array(), sgtheme_version);
}
add_action('admin_init', 'sgtheme_admin_scripts');

/**********************************************/
///////////////// UTILITAIRES
/**********************************************/
function sgtheme_setup()
{
// support vignettes= images à la une
	add_theme_support('post-thumbnails');

// taille image card smartphone
	add_image_size('smartph', 358, 200, true);
	add_image_size('slider', 1030, 650, true );

// retirer la version de wp pour + de sécurité
	remove_action('wp_head', 'wp_generator');
// enlève les guillemets à la française
//remove_filter ('the_content', 'wptexturize');

// support du titre pour seo
	add_theme_support('title-tag');

// Register Custom Navigation Walker
	require_once('includes/bootstrap_5_wordpress-navbar_walker_main.php');
}
// activation de l'onglet 'menu'...
	register_nav_menu('main-menu', 'Main menu');


add_action('after_setup_theme', 'sgtheme_setup');

/******************************************************/
//Affichage date + Catégories utiles pour les articles
/******************************************************/

//ce modèle d'affichage 29 novembre 2016 

// function sgtheme_give_me_meta_01($date1, $date2, $cat, $tags)
// {
// 	$chaine = 'publié le <time class="entry-date" datetime="';
// 	$chaine .= $date1;
// 	$chaine .= '">';
// 	$chaine .= $date2;
// 	$chaine .= '</time> dans la catégorie ';
// 	$chaine .= $cat;
// 	$chaine .= ' avec les étiquettes: ' . $tags;

// 	return $chaine;
// }

/*****************************************************/
// modifie le texte d'excerpt dans 1 article tronqué
/****************************************************/
// function sgtheme_excerpt_more($more)
// {
// 	return ' <a class="more-link" href=" ' . get_permalink() . '">' . '[lire la suite]' . '</a>';
// }
// add_filter('excerpt_more', 'sgtheme_excerpt_more');

/***********************************************************/
// faire apparaître les widgets et sidebares ds le dasboard
/***********************************************************/

// function sgtheme_widgets_init(){
// 	register_sidebar(array(
// 		'name' => 				'Footer Widget Zone',
// 		'description' => 		'Widget affichés dans le footer : 4 au max',
// 		'id' => 					'Widgetizd-footer',
// 		'before_widget' => 	'<div id="%1$s" class="col-xs-12 col-sm-6 col-md-3 %2$s"><div class="inside-widget">',//on rend responsive
// 		'after_widget' => 	'</div></div>',
// 		'before_title' => 	'<h2 class="h3 text-center">',
// 		'after_title' => 		'</h2>',
// 	));
// }
// add_action('widgets_init', 'sgtheme_widgets_init');


function my_single_template($single) {
// Récupération de la variable
	global $post;

// Récupération du chemin vers les fichiers de style du thème
// ajout du dossier "/single" où se trouve les modèles spécifiques à chaque catégorie
	$single_path = get_stylesheet_directory() . '/single';
 
// boucle traversant toutes les catégories de l'article actuel
	foreach((array)get_the_category() as $cat) :

		if(file_exists($single_path . '/single-' . $cat->slug . '.php'))
		return $single_path . '/single-' . $cat->slug . '.php';
	  
	endforeach;
}

// Enregistrement du filtre
add_filter('single_template', 'my_single_template');

// Déclaration un bloc Gutenberg avec ACF
// function capitaine_register_acf_block_types() {

// 	capitaine_register_acf_block_types( array(
// 		 'name'              => 'plugin',
// 		 'title'             => 'Extension',
// 		 'description'       => "Présentation d'une extension WordPress",
// 		 'render_template'   => 'blocks/plugin.php',
// 		 'category'          => 'formatting', 
// 		 'icon'              => 'admin-plugins', 
// 		 'keywords'          => array( 'plugin', 'extension', 'add-on' ),
// 		 'enqueue_assets'    => function() {
// 			 wp_enqueue_style( 'capitaine-blocks', get_template_directory_uri() . '/css/blocks.css' );
// 		 }
// 	) );
// }

// add_action( 'acf/init', 'capitaine_register_acf_block_types' );

//include 1 fichier de fonctions mis à coté pr alléger le fichier functions principal.
// include('includes/build-options-page.php'); //contient la fonction 

/**********************************************/
///Custom Post Type slider ds galerie photos //
/**********************************************/

function sgtheme_slider_init () {
	$labels = array(
		'name' => 'Carousel',
		'singular_name' => 'Image',
		'add_new' => 'Ajoutez 1 image',
		'edit_item' => 'Modifiez',
		'new_item' => 'Nouveau',
		'all_items' => 'Voir liste',
		'view_item' => 'Voir l\' élément',
		'search_item' => 'Cherchez 1 image',
		'not_found' => 'Aucun élément trouvé',
		'not_found_in_trash' => 'Aucun élément dans la corbeille',
		'menu_name' => 'Slider'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'rewite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierachical' => false,
		'menu_position' => 20,
		'menu_icon' => get_stylesheet_directory_uri(  ) . '/assets/picture-icon1.png',
		'publicly_queryable' => false,
		'show_in_nav_menus' => true,
		'exlude_from_search' => true,
		'supports' => array ('title', 'page-attributes', 'thumbnail')
	);
register_post_type('sgtheme_slider', $args);
} //end function sgdelcio_slider_init

add_action('init', 'sgtheme_slider_init');

/*******************************************************/
////AJOUT IMAGE ET RANG D'APPARITION DS LE CPT SLIDER///
/*****************************************************/

add_filter('manage_edit-sgtheme_slider_columns', 'sgtheme_col_change'); // cela ajout des champs de noms de colonnes.
function sgtheme_col_change($columns) {
	$columns['sgtheme_slider_image_order'] = "ordre";
	$columns['sgtheme_slider_image'] = "image affichée";

	return $columns;
}
	add_action('manage_sgtheme_slider_posts_custom_column', 'sgtheme_content_show', 10,2);
function sgtheme_content_show ($column, $post_id) {
		global $post;
		if($column == 'sgtheme_slider_image') {
			echo the_post_thumbnail(array(100,100));
			}
		if($column == 'sgtheme_slider_image_order') {
			echo $post->menu_order;
		}
}

/************************************************************/
///tri auto sur l'ordre ds la colonne admin pr le CPT SLIDER/
/**********************************************************/

function sgtheme_change_slides_order($query) {
	global $post_type, $pagenow;

	if($pagenow == 'edit.php' && $post_type == 'sgtheme_slider') {
		$query-> query_vars['orderby'] = 'menu_order';
		$query-> query_vars['order'] = 'asc';
	}
}
add_action('pre_get_posts', 'sgtheme_change_slides_order');