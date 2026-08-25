// Alloyé — Meta (Facebook) Pixel
//
// 1. Go to business.facebook.com/events_manager, create a pixel, copy its ID.
// 2. Paste it below, replacing "YOUR_PIXEL_ID_HERE".
// 3. That's it — every page already calls this file, and cart/checkout/purchase
//    events below will start sending automatically once a real ID is set.

const META_PIXEL_ID = "1571468511290117";

!function (f, b, e, v, n, t, s) {
  if (f.fbq) return;
  n = f.fbq = function () {
    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
  };
  if (!f._fbq) f._fbq = n;
  n.push = n;
  n.loaded = true;
  n.version = "2.0";
  n.queue = [];
  t = b.createElement(e);
  t.async = true;
  t.src = v;
  s = b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t, s);
}(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");

if (META_PIXEL_ID && META_PIXEL_ID !== "YOUR_PIXEL_ID_HERE") {
  fbq("init", META_PIXEL_ID);
  fbq("track", "PageView");
}

// Safe wrapper — every tracking call in the site goes through this,
// so nothing errors out while the Pixel ID is still a placeholder.
function trackPixelEvent(eventName, data) {
  if (typeof fbq === "function" && META_PIXEL_ID !== "YOUR_PIXEL_ID_HERE") {
    fbq("track", eventName, data || {});
  }
}
