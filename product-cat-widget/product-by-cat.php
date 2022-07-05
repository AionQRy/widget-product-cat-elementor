<?php
namespace Elementor;

class product_by_cat extends Widget_Base {

    public function get_name() {
		return 'product_by_cat';
	}

	public function get_title() {
		return __( 'Product by Category' );
	}

	public function get_icon() {
		return 'eicon-post-list';
    }


   public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_enqueue_style( 'product-by-cat', plugin_dir_url( __DIR__  ) . '../css/deskspace/product-by-cat.css','1.1.0');
  }

   public function get_style_depends() {
    //  wp_register_style( 'product-by-cat', plugin_dir_url( __DIR__  ) . 'css/deskspace/product-by-cat.css','1.1.0');
     return [ 'product-by-cat' ];
   }




	protected function _register_controls() {
		$mine = array();
    $categories = get_terms(array(
            'taxonomy' => 'product_cat', 
			'orderby'   => 'name',
			'order'     => 'ASC'
		));

		foreach ($categories as $category ) {
	     $mine[$category->term_id] = $category->name;
		}

			$this->start_controls_section(
				'content_section',
				[
					'label' => __( 'Content', 'post-plus' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

        // Post categories.
		$this->add_control(
			'category',
			[
        'label' => '<i class="fa fa-folder"></i> ' . __( 'Category', 'yp-core' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => 'none',
        'options'   => $mine,
				'multiple' => false,
			]
		);

    $this->add_control(
        'per_posts',
        [
          'label' => __( 'Posts Per Page', 'yp-core' ),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'placeholder' => '0',
          'min' => 1,
          'max' => 12,
          'step' => 1,
          'default' => 1,
        ]
      );

      $this->add_control(
          'post_offset',
          [
            'label' => __( 'Offset', 'yp-core' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'placeholder' => '0',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'default' => 0,
          ]
        );
      
        $this->add_control(
          'column',
          [
            'type' => \Elementor\Controls_Manager::SELECT,
            'label' => esc_html__( 'Column', 'plugin-name' ),
            'options' => [
              '1' => esc_html__( '1', 'yp-core' ),
              '2' => esc_html__( '2', 'yp-core' ),
              '3' => esc_html__( '3', 'yp-core' ),
              '4' => esc_html__( '4', 'yp-core' ),
            ],
            'default' => '1',
          ]
        );

        $this->end_controls_section();
		}

	protected function render() {
    $settings = $this->get_settings_for_display();
    $offset = $settings['post_offset'];
    if ($offet == '') {
      $offet = 0;
    }
    $num_posts = $settings['per_posts'];
    if ($num_posts == '') {
        $num_posts = 1;
    }
    $cat_x = $settings['category'];
    if ($cat_x == '') {
        $cat_x = 1;
    }
    $column   = $settings['column'];
    switch ($column ) {
      case 1:
        $num_column = 1;
        $num_column_tablet = 1;
        $num_column_mobile = 1;
        $c_class = 'c_1_class';
      break;
      case 2:
        $num_column = 2;
        $num_column_tablet = 3;
        $num_column_mobile = 1;
        $c_class = 'c_2_class';
      break;
      case 3:
        $num_column = 3;
        $num_column_tablet = 3;
        $num_column_mobile = 1;
        $c_class = 'c_3_class';
      break;      
      default:
        $num_column = 4;
        $num_column_tablet = 3;
        $num_column_mobile = 1;
        $c_class = 'c_4_class';
        break;
    }

    $term_all = get_term_by('id', $settings['category'], 'product_cat');
    $term_link = get_term_link( $term_all->term_id , 'product_cat' );
    ?>
    <div class="main-post-grid_product main-post-grid_product_v3">
        <div class="post-grid_bar_product">
          <div class="grid-title">
            <h3><?php echo $term_all->name; ?><span><?php echo esc_html__( 'by Deskspace', 'yp-core' ); ?></span></h3>
          </div>
          <div class="btn-url">
              <a href="<?php echo $term_link; ?>"><?php echo esc_html__( 'All Category', 'yp-core' ); ?><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
          </div>
        </div>
    </div>
    <?php
    $args = array(
    'post_type' => array( 'product'),
    'tax_query'         => array(
           array(
               'taxonomy'  => 'product_cat',
               'field'     => 'term_id',
               'terms'     => $cat_x
           )
         ),
    'posts_per_page'  => $settings['per_posts'],
    'offset'    => $offset,
    'orderby'    => 'date',
    'order'    => 'DESC'
    );
    query_posts( $args );
    ?>


    <div class="vc_posts card style-1 v1 post-grid_product">
        <div class="product-wrapper <?php echo $c_class; ?>" style="grid-template-columns: repeat(<?php echo $num_column; ?>, 1fr);">
      <?php if ( have_posts()) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <?php $term = get_the_terms(get_the_ID(), 'product_cat'); ?>

                <?php echo get_template_part(  'woocommerce/content', 'product' ); ?>


        <?php endwhile; ?>
        <?php endif; ?>
        <?php wp_reset_query(); ?>


      </div>
    </div>

    <style>
/*ipad pro (large tablet)*/
@media (max-width: 1024px) and (min-width: 992px){
  .post-grid_product .product-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_tablet; ?>, 1fr) !important;
}
}
/*ipad (tablet)*/
@media (max-width: 991.98px) {
  .post-grid_product .product-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_tablet; ?>, 1fr) !important;
}
  
}
/*iphone8 (smartphone)*/
@media (max-width: 575.98px) {
  .post-grid_product .product-wrapper {
    grid-template-columns: repeat(<?php echo $num_column_mobile; ?>, 1fr) !important;
}
}
/*iphone5 (small smartphone)*/
@media (max-width: 360px) {
}
 </style>
		<?php
    }

	protected function _content_template() {}


}
