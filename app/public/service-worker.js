// Service Worker untuk PWA Perpustakaan Keliling
const CACHE_NAME = 'perpustakaan-v2';
const urlsToCache = [
  '/',
  '/logo.png',
  '/favicon.png'
];

// Install Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Cache opened');
        return cache.addAll(urlsToCache);
      })
  );
  self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch - Network First, fallback to Cache
self.addEventListener('fetch', event => {
  // Skip caching untuk POST, PUT, DELETE requests
  if (event.request.method !== 'GET') {
    return;
  }
  
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Clone response karena response hanya bisa digunakan sekali
        const responseToCache = response.clone();
        
        // Simpan response baru ke cache
        caches.open(CACHE_NAME)
          .then(cache => {
            cache.put(event.request, responseToCache);
          });
        
        return response;
      })
      .catch(() => {
        // Jika network gagal, ambil dari cache
        return caches.match(event.request)
          .then(response => {
            if (response) {
              return response;
            }
            
            // Jika tidak ada di cache, return offline page
            if (event.request.destination === 'document') {
              return caches.match('/offline.html');
            }
          });
      })
  );
});
