// Cache infos
const VERSION_APP = "V2.1"
const STATIC_CACHE_URLS = ["./index.html"];

// PWA Installation
self.addEventListener("install", event => {
    console.log("Service Worker installing version : " + VERSION_APP);
    self.skipWaiting();
    event.waitUntil(
        caches.open(VERSION_APP).then(cache => cache.addAll(STATIC_CACHE_URLS))
    );
});

self.addEventListener("activate", event => {
    console.log("Service Worker Activation version :" + VERSION_APP);
    clients.claim();
    // delete any unexpected caches
    event.waitUntil(
        caches
            .keys()
            .then(keys => keys.filter(key => key !== VERSION_APP))
            .then(keys =>
                Promise.all(
                    keys.map(key => {
                        console.log(`Deleting cache ${key}`);
                        return caches.delete(key);
                    })
                )
            )
    );
})

self.addEventListener("fetch", event => {
    // console.log(`Request : ${event.request.url} & ${event.request.mode}`);
    if (event.request.mode == "navigate") {

        event.respondWith(
            (async () => {
                try {
                    const preloadResponse = await event.preloadResponse
                    if (preloadResponse) {
                        return preloadResponse
                    }
                    return await fetch(event.request)
                } catch (error) {
                    const cache = await caches.open(VERSION_APP)
                    return await cache.match("/index.html")
                }
            })()
        )
    }

});

self.addEventListener('push', event => {
    console.log(event);
    // console.log(JSON.parse(event.data.text()));
    // payload
    // {"title":"Title","body":"body testing push notif","url":"/" }
    const data = JSON.parse(event.data.text())
    const options = {
        body: data.body,
        icon: 'src/img/icon512x512.png',
        image: data.image,
        data: {
            notifURL: data.url
        }
    };
    event.waitUntil(self.registration.showNotification(data.title, options));
});
self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.notifURL)
    );
});