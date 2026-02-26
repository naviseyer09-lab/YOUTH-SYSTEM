// Simple mobile nav toggle
document.addEventListener('DOMContentLoaded', function(){
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('main-nav');
  if(!toggle || !nav) return;

  function setMenuState(open){
    nav.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  toggle.addEventListener('click', function(){
    var open = !nav.classList.contains('open');
    setMenuState(open);
  });

  document.addEventListener('click', function(event){
    if(!nav.classList.contains('open')) return;
    if(nav.contains(event.target) || toggle.contains(event.target)) return;
    setMenuState(false);
  });

  document.addEventListener('keydown', function(event){
    if(event.key === 'Escape'){
      setMenuState(false);
    }
  });
});
// Dashboard sidebar toggle for mobile
document.addEventListener('DOMContentLoaded', function(){
  var sidebar = document.querySelector('.sidebar');
  if(!sidebar) return;

  // Check if we're on mobile
  var mediaQuery = window.matchMedia('(max-width: 900px)');
  
  function handleMediaChange(e) {
    if(e.matches) {
      // Mobile view - sidebar should scroll horizontally
      sidebar.style.maxHeight = '300px';
    } else {
      // Desktop view - sidebar fixed height off
      sidebar.style.maxHeight = 'none';
    }
  }

  // Initial check
  handleMediaChange(mediaQuery);
  
  // Listen for changes
  mediaQuery.addListener(handleMediaChange);
});