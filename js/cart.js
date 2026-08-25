// Alloyé — Cart & Wishlist (saved in the browser's localStorage)

const CART_KEY = "alloye_cart";
const WISHLIST_KEY = "alloye_wishlist";

function getCart() {
  return JSON.parse(localStorage.getItem(CART_KEY) || "[]");
}

function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function addToCart(id, qty, style) {
  qty = qty || 1;
  style = style || null;
  const cart = getCart();
  const existing = cart.find((item) => item.id === id && item.style === style);
  if (existing) {
    existing.qty += qty;
  } else {
    cart.push({ id: id, qty: qty, style: style });
  }
  saveCart(cart);
  updateHeaderCounts();

  const product = getProductById(id);
  if (product && typeof trackPixelEvent === "function") {
    trackPixelEvent("AddToCart", {
      content_name: product.name,
      content_ids: [product.id],
      content_type: "product",
      value: product.price * qty,
      currency: "BDT"
    });
  }
}

function removeFromCart(id, style) {
  style = style || null;
  const cart = getCart().filter((item) => !(item.id === id && item.style === style));
  saveCart(cart);
  updateHeaderCounts();
}

function updateCartQty(id, qty, style) {
  style = style || null;
  const cart = getCart();
  const item = cart.find((i) => i.id === id && i.style === style);
  if (item) {
    item.qty = Math.max(1, qty);
    saveCart(cart);
  }
  updateHeaderCounts();
}

function getCartCount() {
  return getCart().reduce((sum, item) => sum + item.qty, 0);
}

function getCartTotal() {
  return getCart().reduce((sum, item) => {
    const product = getProductById(item.id);
    return product ? sum + product.price * item.qty : sum;
  }, 0);
}

function getWishlist() {
  return JSON.parse(localStorage.getItem(WISHLIST_KEY) || "[]");
}

function saveWishlist(list) {
  localStorage.setItem(WISHLIST_KEY, JSON.stringify(list));
}

function isWishlisted(id) {
  return getWishlist().includes(id);
}

function toggleWishlist(id) {
  let list = getWishlist();
  let active;
  if (list.includes(id)) {
    list = list.filter((i) => i !== id);
    active = false;
  } else {
    list.push(id);
    active = true;
  }
  saveWishlist(list);
  updateHeaderCounts();
  return active;
}

function getWishlistCount() {
  return getWishlist().length;
}

function updateHeaderCounts() {
  const cartCountEl = document.getElementById("cart-count");
  const wishlistCountEl = document.getElementById("wishlist-count");
  if (cartCountEl) cartCountEl.textContent = getCartCount();
  if (wishlistCountEl) wishlistCountEl.textContent = getWishlistCount();
}

document.addEventListener("DOMContentLoaded", updateHeaderCounts);
