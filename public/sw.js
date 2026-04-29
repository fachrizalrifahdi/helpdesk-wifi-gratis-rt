const CACHE_NAME = 'wifi-rt-v3';
const ASSETS = [
    '/manifest.json',
    '/icon.svg',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    // Skip caching for storage, dashboard, and non-GET requests
    if (
        event.request.method !== 'GET' || 
        event.request.url.includes('/storage/') || 
        event.request.url.includes('/dashboard') ||
        event.request.url.includes('/logout')
    ) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
