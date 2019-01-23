importScripts("https://storage.googleapis.com/workbox-cdn/releases/3.0.0/workbox-sw.js")

workbox.setConfig({
    debug: false
});

workbox.routing.registerRoute(
    new RegExp('.*(?:googleapis|gstatic)\.com.*$'),
    workbox.strategies.staleWhileRevalidate()
)

workbox.routing.registerRoute(
    new RegExp('.*\.js'),
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'js-cache',
    })
)

workbox.routing.registerRoute(
    new RegExp('.*\.css'),
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'css-cache',
    })
)

workbox.routing.registerRoute(
    new RegExp('.*\.woff2'),
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'font-cache',
    })
)

workbox.routing.registerRoute(
    /\.(?:png|gif|jpg|jpeg|svg|ico)$/,
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'image-cache',
    })
)

workbox.routing.registerRoute(
    new RegExp('(?:article|tags)\/.*'),
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'page-cache',
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 10,
                maxAgeSeconds: 7 * 24 * 60 * 60
            })
        ]
    })
)

workbox.routing.registerRoute(
    ['/', '/offline'],
    workbox.strategies.staleWhileRevalidate({
        cacheName: 'page-cache',
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 10,
                maxAgeSeconds: 7 * 24 * 60 * 60
            })
        ]
    })
)

workbox.routing.setCatchHandler(({
    url,
    event,
    params
}) => {
    return caches.match('/offline')
})