const cacheName = 'almocai-v3';

var assets = [
  // Base
  '/almocai/assets/css/almocai.css',
  '/almocai/assets/js/almocai.min.js',
  '/almocai/offline.html',

  // Imagens
  '/almocai/assets/img/logo-verde.svg',
  '/almocai/assets/img/logo-branco.svg',

  // Entrar
  '/almocai/assets/img/entrar/fundo.png',
  '/almocai/assets/img/entrar/redefinir.jpg',
  '/almocai/assets/img/entrar/recuperar.jpg'
]; // list of urls to be cached

self.addEventListener('install', event => {
  console.log('Attempting to install service worker and cache static assets');
  event.waitUntil(
    caches.open(cacheName)
    .then(cache => {
      return cache.addAll(assets);
    })
  );
});


self.addEventListener('activate', event => {
  console.log('Activating new service worker...');

  const cacheWhitelist = [cacheName];

  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});


// cache responses of provided urls
cacheAssets(assets)
  .then(() => {
      console.log('All assets cached')
});

// Lixeira Inteligente
// this.addEventListener("fetch", event => {
//   event.respondWith(
//     caches.match(event.request)
//       .then(response => {
//         return response || fetch(event.request);
//       })
//       .catch(() => {
//         return caches.match('offline.html');
//       })
//   )
// });

self.addEventListener('fetch', event => {
  var request = event.request;
  // check if request
  // if (request.url.indexOf('localhost') > -1) {
  if (request.url.indexOf('fabricadetecnologias.ifc-riodosul.edu.br') > -1) {
    // contentful asset detected
    event.respondWith(
      caches.match(event.request)
      .then(function(response) {
        // return from cache, otherwise fetch from network
        return response || fetch(request);
      })
      .catch(error => {
        console.log('Error, ', error);
        return caches.match('/almocai/offline.html');
      })
    );
  }
  // otherwise: ignore event
});

// all urls will be added to cache
function cacheAssets( assets ) {
  return new Promise( function (resolve, reject) {
    // open cache
    caches.open('assets')
      .then(cache => {
        // the API does all the magic for us
        cache.addAll(assets)
          .then(() => {
            console.log('all assets added to cache')
            resolve()
          })
          .catch(err => {
            console.log('error when syncing assets', err)
            reject()
          })
      }).catch(err => {
        console.log('error when opening cache', err)
        reject()
      })
  });
}
