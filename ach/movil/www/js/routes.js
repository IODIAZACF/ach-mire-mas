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
    path: '/menu_cliente/',
    url: './menu_cliente/menu.html?' + timestamp,
  },
  {
    path: '/lista_precios_productos/',
    url: './lista_precios/m_productos.html?' + timestamp,
  },
  {
    path: '/catalogo_lineas/',
    url: './catalogo_productos/m_lineas.html?' + timestamp,
  },
  {
    path: '/catalogo_productos/',
    url: './catalogo_productos/m_productos.html?' + timestamp,
  },
  {
    path: '/carrito/',
    url: './catalogo_productos/carrito.html?' + timestamp,
  },
  {
    path: '/ficha_producto/',
    url: './catalogo/ficha_producto.html?' + timestamp,
  },
  {
    path: '/pedidos/',
    url: './pedidos/d_pedidos.html?' + timestamp,
  },
  {
    path: '/m_servicios/',
    url: './pedidos/m_servicios.html?' + timestamp,
  },
  {
    path: '/m_productos/',
    url: './pedidos/m_productos.html?' + timestamp,
  },
  {
    path: '/m_clientes/',
    url: './maestros/m_clientes.html?' + timestamp,
  },
  {
    path: '/f_clientes/',
    url: './maestros/f_clientes.html?'  + timestamp,
  },
  {
    path: '/m_documentos/',
    url: './historial/m_documentos.html?'  + timestamp,
  },
  {
    path: '/d_documentos/',
    url: './historial/d_documentos.html?'  + timestamp,
  },
  {
    path: '/d_cxc/',
    url: './cxc/d_cxc.html?'  + timestamp,
  },
    {
    path: '/d_documentado/',
    url: './cxc/d_documentado/.html?'  + timestamp,
  },
  {
    path: '/construccion/',
    url: './pages/construccion.html?'  + timestamp,
  },
  {
    path: '/mapa/',
    url: './mapa/mapa.html?' + timestamp,
  },
  {
    path: '/mi_ubicacion/',
    url: './mapa/mi_ubicacion.html?' + timestamp,
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