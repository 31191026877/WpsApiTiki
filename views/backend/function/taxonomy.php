<?php 
Taxonomy::addPost('post',
	array(
		'labels' => array(
            'name'          => 'Bài viết',
            'singular_name' => 'Bài viết',
        ),
        'public' => true,
        'capabilities' => array(
            'view'      => 'view_posts',
            'add'       => 'add_posts',
            'edit'      => 'edit_posts',
            'delete'    => 'delete_posts',
        ),
	)
);

Taxonomy::addCategory('post_categories', 'post',
	array(
        'labels' => array(
            'name'          => 'Chuyên mục bài viết',
            'singular_name' => 'Chuyên mục',
        ),
        'public' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'parent' => true,
        'capabilities' => array(
            'edit'      => 'manage_categories',
            'delete'    => 'delete_categories',
        ),
    )
);