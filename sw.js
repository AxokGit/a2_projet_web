importScripts(
    'https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js'
);
if (workbox) {
    console.log(`Super ! Workbox est chargé 🎉`);
  
    workbox.routing.registerRoute(
        /\.(?:html|js|css|png|jpg|jpeg|svg|gif)$/,
        new workbox.strategies.StaleWhileRevalidate()
    );
}

//Installation du service worker
self.addEventListener('install', (e) => {
    console.log('[Service Worker] Installation');
    var cacheName = 'CTS_v2';
    var appShellFiles = [
      'index.php',
      'assets/css/index.css',
      'assets/js/index.js',
      'assets/vendors/jquery/jquery-3.6.0.min.js'
    ];

    e.waitUntil(
        caches.open(cacheName).then((cache) => {
            console.log('[Service Worker] Mise en cache globale: app shell et contenu')
            return cache.addAll(appShellFiles);
    }))
});

//fetch event afin de répondre quand on est en mode hors ligne.
self.addEventListener('fetch', (e) => {
    e.respondWith(
        caches.open('ma_sauvegarde').then(function(cache) {
            return cache.match(e.request).then(function (response) {
                return response || fetch(e.request).then(function(response) {
                    cache.put(e.request, response.clone());
                    return response;
                });
            });
        })
    );
});
/*
self.addEventListener('fetch', (e) => {
    e.respondWith(
      caches.match(e.request).then((r) => {
            console.log('[Service Worker] Récupération de la ressource: '+e.request.url);
        return r || fetch(e.request).then((response) => {
                  return caches.open(cacheName).then((cache) => {
            console.log('[Service Worker] Mise en cache de la nouvelle ressource: '+e.request.url);
            cache.put(e.request, response.clone());
            return response;
          });
        });
      })
    );
  });*/