<?php
	use yii\helpers\Html;
	use yii\widgets\Menu;
	use kartik\cmenu\ContextMenu;
	use kartik\nav\NavX;
	use yii\bootstrap\NavBar;
	use host33\multilevelverticalmenu\MultilevelVerticalMenu;
  use kartik\sidenav\SideNav;
  use kartik\icons\Icon;
  
    Icon::map($this);

    $menuItems = [];
    $index = 'index.php?';
?>

<!-- div class="list-group">
  <a href="#" class="list-group-item active">
    Cras justo odio
  </a>
  <a href=<? $index  . 'r=hola/hola'?> class="list-group-item"><span class="fa fa-user-plus fa-2x"></span> Registro Basico</a> <a href="#" class="list-group-item">Morbi leo risus</a>
  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
  <a href="#" class="list-group-item">Vestibulum at eros</a>
</div> -->


<!-- <div class="list-group">
	<div class="list-group-item">
		<a href="index.php?r=hola/hola"><span class="fa fa-user-plus fa-2x"></span> Registro Basico</a>
	</div>
</div> -->

<?/*= 
	ContextMenu::begin([
    'items' => [
        ['label' => 'Action', 'url' => '#'],
        ['label' => 'Another action', 'url' => '#'],
        ['label' => 'Something else here', 'url' => '#'],
        '<li class="divider"></li>',
        ['label' => 'Separated link', 'url' => '#'],
    ],
    'encodeLabels' => false,
]); 
// fill in any content within your target container
ContextMenu::end();*/
?>


<?= 
	/*NavX::widget([
    'options' => ['class' => 'nav nav-stacked', 'height' => '12px']
    'items' => [
        ['label' => '<li>Action</li>', 'url' => '#'],
        ['label' => '<li>Submenu</li>',  'items' => [
            ['label' => 'Action', 'url' => '#'],
            ['label' => 'Another action', 'url' => '#'],
            ['label' => 'Something else here', 'url' => '#'],
        ]],
        ['label' => '<li>Something else here</li>', 'url' => '#'],
        ['label' => '<li>Separated link</li>', 'url' => '#'],
    ],
    'encodeLabels' => false
]);*/


//['brandLabel' => 'NavBar Test']
//  NavBar::begin(['class' => 'navbar-default navbar-static-side']);
//  Nav::widget([
//     'items' => [
//         ['label' => 'Home', 'url' => ['/site/index']],
//         ['label' => 'About', 'url' => ['/site/about']],
//     ],
// ]);
// NavBar::end();

/*
 MultilevelVerticalMenu::widget(
array(
"menu"=>array(
  array("url"=>array(),
               "label"=>"Products",
          array("url"=>array(
                       "route"=>"/product/create"),
                       "label"=>"Create product",),
          array("url"=>array(
                      "route"=>"/product/list"),
                      "label"=>"Product List",),
          array("url"=>array(),
                       "label"=>"View Products",
          array("url"=>array(
                       "route"=>"/product/show",
                       "params"=>array("id"=>3),
                       "htmlOptions"=>array("title"=>"title")),
                       "label"=>"Product 3"),
            array("url"=>array(),
                         "label"=>"Product 4",
                array("url"=>array(
                             "route"=>"/product/show",
                             "params"=>array("id"=>5)),
                             "label"=>"Product 5")))),
          array("url"=>array(
                       "route"=>"/event/create"),
                       "label"=>"Sales"),
          array("url"=>array(
                       "route"=>"/event/create"),
                       "label"=>"Extensions",
                       "visible"=>false),
          array("url"=>array(),
                       "label"=>"Documentation",
              array("url"=>array(
                           "link"=>"http://www.yiiframework.com",
                           "htmlOptions"=>array("target"=>"_BLANK")),
                           "label"=>"Yii Framework"),
          array("url"=>array(),
                       "label"=>"Clothes",
          array("url"=>array(
                       "route"=>"/product/men",
                       "params"=>array("id"=>3),
                       "htmlOptions"=>array("title"=>"title")),
                       "label"=>"Men"),
            array("url"=>array(),
                         "label"=>"Women",
                array("url"=>array(
                             "route"=>"/product/scarves",
                             "params"=>array("id"=>5)),
                             "label"=>"Scarves"))),
              array("url"=>array(
                           "route"=>"site/menuDoc"),
                           "label"=>"Disabled Link",
                           "disabled"=>true),
                )
          ),
    "transition" => 1 // To choose between 1,2,3,4 and 5. 
)
);
*/

/*
$type = SideNav::TYPE_DEFAULT;
$heading = 'Menu';
SideNav::widget([
    'type' => $type,
    'encodeLabels' => false,
    'heading' => $heading,
    'items' => [
        // Important: you need to specify url as 'controller/action',
        // not just as 'controller' even if default action is used.
        ['label' => 'Home', 'icon' => 'home', 'url' => u('home', $type), 'active' => ($item == 'home')],
        // 'Products' menu item will be selected as long as the route is 'product/index'
        ['label' => 'Books', 'icon' => 'book', 'items' => [
            ['label' => '<span class="pull-right badge">10</span> New Arrivals', 'url' => u('new-arrivals', $type), 'active' => ($item == 'new-arrivals')],
            ['label' => '<span class="pull-right badge">5</span> Most Popular', 'url' => u('most-popular', $type), 'active' => ($item == 'most-popular')],
            ['label' => 'Read Online', 'icon' => 'cloud', 'items' => [
                ['label' => 'Online 1', 'url' => u('online-1', $type), 'active' => ($item == 'online-1')],
                ['label' => 'Online 2', 'url' => u('online-2', $type), 'active' => ($item == 'online-2')]
            ]],
        ]],
        ['label' => '<span class="pull-right badge">3</span> Categories', 'icon' => 'tags', 'items' => [
            ['label' => 'Fiction', 'url' => u('fiction', $type), 'active' => ($item == 'fiction')],
            ['label' => 'Historical', 'url' => u('historical', $type), 'active' => ($item == 'historical')],
            ['label' => '<span class="pull-right badge">2</span> Announcements', 'icon' => 'bullhorn', 'items' => [
                ['label' => 'Event 1', 'url' => u('event-1', $type), 'active' => ($item == 'event-1')],
                ['label' => 'Event 2', 'url' => u('event-2', $type), 'active' => ($item == 'event-2')]
            ]],
        ]],
        ['label' => 'Profile', 'icon' => 'user', 'url' => u('profile', $type), 'active' => ($item == 'profile')],
    ],
]);    
*/
?>

<div class="col-xs-5">
<?=
  SideNav::widget([
    'type' => SideNav::TYPE_DEFAULT,
    'encodeLabels' => false,
    'heading' => 'Menu',
    'items' => [
        // Important: you need to specify url as 'controller/action',
        // not just as 'controller' even if default action is used.
        ['label' => 'Home', 'icon' => 'home', 'url' => "#"],
        // 'Products' menu item will be selected as long as the route is 'product/index'
        ['label' => 'Books', 'icon' => 'book', 'items' => [
            ['label' => 'New Arrivals', 'url' => "#"],
            ['label' => 'Most Popular', 'url' => "#"],
            ['label' => 'Read Online', 'icon' => 'cloud', 'items' => [
                ['label' => 'Online 1', 'url' => "#"],
                ['label' => 'Online 2', 'url' => "#"]
            ]],
        ]],
        ['label' => 'Categories', 'icon' => 'tags', 'items' => [
            ['label' => 'Fiction', 'url' => "#"],
            ['label' => 'Historical', 'url' => "#"],
            ['label' => 'Announcements', 'icon' => 'bullhorn', 'items' => [
                ['label' => 'Event 1', 'url' => "#"],
                ['label' => 'Event 2', 'url' => "#"]
            ]],
        ]],
        ['label' => 'Profile', 'icon' => 'user', 'url' => "#"],
    ],
]);      
?>
</div>