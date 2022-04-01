const staticCacheName = "site_static_v1";
const dynamicCacheName = "site_dynamic_v1";
const assets = [
    './',
    './index.php',
    './fallback.php',
    './manifest/manifest.json',
    'http://ctsstatic.fr/assets/css/index.css',
    'http://ctsstatic.fr/assets/css/a_propos.css',
    'http://ctsstatic.fr/assets/images/fond_cesi_large.png',
    'http://ctsstatic.fr/assets/vendors/fontawesome/css/all.min.css',
    'http://ctsstatic.fr/assets/js/index.js',
    'http://ctsstatic.fr/assets/js/nav_bar.js',
    'http://ctsstatic.fr/assets/css/nav_bar.css',
    'http://ctsstatic.fr/assets/images/logo.png',
    'http://ctsstatic.fr/assets/images/logo144.png',
    'http://ctsstatic.fr/assets/images/logo_petit.png',
    'http://ctsstatic.fr/assets/vendors/jquery/jquery-3.6.0.min.js',
    'http://ctsstatic.fr/assets/js/sha1.min.js',
    'http://ctsstatic.fr/assets/fonts/Fredoka-Regular.ttf',
];

self.addEventListener('install', evt => { //Event à l'installation
    //console.log("installed");
    evt.waitUntil( //Ne s'arrete pas tant que le cache n'est complet
        caches.open(staticCacheName).then(cache => {
            console.log("Caching assets"); 
            cache.addAll(assets);
        })
    )
});

self.addEventListener('activate', evt => {
    //console.log("activated");
    evt.waitUntil(
        caches.keys().then(keys => {
            //console.log(keys);
            return Promise.all(keys
                .filter(key => key !== staticCacheName && key !== dynamicCacheName)
                .map(key => caches.delete(key)))
        })
    );
});

self.addEventListener('fetch', evt => {
    evt.respondWith(
        caches.match(evt.request).then(cacheRes => {
            console.log(evt.request);
            if(evt.request.url.indexOf("favoris") > -1){
                return fetch(evt.request).then(fetchRes => {
                    return caches.open(dynamicCacheName).then(cache => {
                        cache.put(evt.request.url, fetchRes.clone());
                        return cacheRes;
                    })
                }).catch(() => {
                    console.log(evt.request.url)
                    return cacheRes;
                });
            } else {
                return cacheRes || fetch(evt.request).then(fetchRes => {
                    return caches.open(dynamicCacheName).then(cache => {
                        return fetchRes;
                    })
                });
            }
            
        }).catch(() => {
            console.log("Erreur d'accès à :", evt.request.url)
            return caches.match('/fallback.php');
        })
    );
});