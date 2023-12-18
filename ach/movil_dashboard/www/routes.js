var timestamp   = Date.now();

routes = [
 {
    path: '/blank/',
    url: './pages/blank.html?'  + timestamp,
  },
  {
    path: '/login/',
    url: './login/login.html?'   + timestamp,
  },
  {
    path: '/menu_principal/',
    url: './menu_principal/menu_principal.html?' + timestamp,
  },
  {
    path: '/m_formularios/',
    url: './formularios/m_formularios.html?' + timestamp,
  },
  {
    path: '/d_formularios/',
    url: './formularios/d_formularios.html?' + timestamp,
  },
  {
    path: '/graficos/',
    url: './graficos/graficos.html?' + timestamp,
  },
  {
    path: '/construccion/',
    url: './pages/construccion.html?'  + timestamp,
  },
  {
    path: '/about/',
    url: './pages/about.html',
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
  
];