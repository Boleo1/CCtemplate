<footer>
        <x-nav.footernavigation :links="[
        ['url' => '/', 'label' => 'Home', 'class' => 'nav-link'],
        ['url' => '/about', 'label' => 'About Us', 'class' => 'nav-link'],
        ['url' => '/events', 'label' => 'Events', 'class' => 'nav-link'],
        ['url' => '/calendar', 'label' => 'Calendar', 'class' => 'nav-link'],
        ['url' => '/contact', 'label' => 'Contact', 'class' => 'nav-link']
      ]" class="footer-nav"/> 
  <div class="footerContent">
    <p>&copy; {{ date('Y') }} Boleo Tech Solutions. All rights reserved.</p>
  </div>

</footer>