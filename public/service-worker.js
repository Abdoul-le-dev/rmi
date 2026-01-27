const CACHE_NAME = 'laravel-pwa-v3';
const OFFLINE_URL = '/offline';


self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.add(OFFLINE_URL))
  );
});


self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(k => k !== CACHE_NAME)
            .map(k => caches.delete(k))
      )
    )
  );
});


self.addEventListener('fetch', event => {
  const request = event.request;
  const url = new URL(request.url);

  // On ignore TOUT ce qui n'est pas http/https
  if (!url.protocol.startsWith('http')) return;

  //  Admin jamais caché
  if (url.pathname.startsWith('/admin')) return;

  //  API jamais cachée
  if (url.pathname.startsWith('/api')) return;

  // Assets statiques
  if (
    request.destination === 'style' ||
    request.destination === 'script' ||
    request.destination === 'image' ||
    request.destination === 'font'
  ) {
    event.respondWith(cacheFirstSafe(request));
    return;
  }

  // HTML
  if (request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(networkFirstSafe(request));
  }
});



async function cacheFirstSafe(request) {
  const cache = await caches.open(CACHE_NAME);
  const cached = await cache.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return cached;
  }
}

async function networkFirstSafe(request) {
  const cache = await caches.open(CACHE_NAME);

  try {
    const response = await fetch(request);
    if (response.ok) {
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return await cache.match(request) || await cache.match(OFFLINE_URL);
  }
}
