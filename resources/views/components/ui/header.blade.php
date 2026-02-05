<header>
  <x-nav.navigation :links="[
    ['url' => '/', 'label' => 'Home', 'class' => 'nav-link'],
    ['url' => '/about', 'label' => 'About Us', 'class' => 'nav-link'],

    [
      'url' => '/events',
      'label' => 'Events',
      'class' => 'nav-link',
      'children' => [
        ['url' => '/events', 'label' => 'Upcoming', 'class' => 'nav-link', 'route' => 'events.index'],
        ['url' => '/events/past', 'label' => 'Past', 'class' => 'nav-link', 'route' => 'events.past'],
      ],
    ],

    ['url' => '/calendar', 'label' => 'Calendar', 'class' => 'nav-link'],
    ['url' => '/contact', 'label' => 'Contact', 'class' => 'nav-link']
  ]" class="header-nav" />
</header>
