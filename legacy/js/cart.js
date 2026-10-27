export function loadCart() {
  return JSON.parse(localStorage.getItem("fujeira_cart")) || [];
}

export function saveCart(cart) {
  localStorage.setItem("fujeira_cart", JSON.stringify(cart));
  updateCartCount();
}

export function updateCartCount() {
  const cart = loadCart();
  const total = cart.reduce((acc, p) => acc + p.cantidad, 0);
  const badge = document.getElementById("cartCount");
  if (badge) badge.textContent = total;
}

export function showToast(text = "Agregado al carrito") {
  const toastEl = document.getElementById("toastAdded");
  if (!toastEl) return;

  const body = toastEl.querySelector(".toast-body");
  if (body) body.textContent = text;

  bootstrap.Toast.getOrCreateInstance(toastEl).show();
}

window.showToast = showToast;

export function addToCart(item) {
  const cart = loadCart();

  const existing = cart.find(p =>
    p.codigo === item.codigo &&
    p.talle === item.talle &&
    p.marco === item.marco
  );

  if (existing) {
    existing.cantidad++;
  } else {
    cart.push(item);
  }

  saveCart(cart);
  showToast("✔️ Agregado al carrito");
}

export function changeQty(index, amount) {
  const cart = loadCart();
  cart[index].cantidad += amount;

  if (cart[index].cantidad <= 0) {
    cart.splice(index, 1);
  }

  saveCart(cart);
  renderCart();
}

export function removeFromCart(index) {
  const cart = loadCart();
  cart.splice(index, 1);
  saveCart(cart);
  renderCart();
  showToast("Producto eliminado");

  if (cart.length === 0) {
    window.resetTalleSeleccionado?.();
  }
}

export function renderCart() {
  const cart = loadCart();
  const container = document.getElementById("cartItems");
  const totalEl = document.getElementById("cartTotal");
  const checkout = document.getElementById("checkoutOptions");

  if (!container || !totalEl) return;

  if (cart.length === 0) {
    container.innerHTML = `
      <p class="text-center mt-3 opacity-75">
        Tu carrito está vacío.
      </p>
    `;
    totalEl.textContent = "$0";
    checkout?.classList.add("d-none");
    updateCartCount();
    return;
  }

  checkout?.classList.remove("d-none");

  container.innerHTML = cart.map((item, index) => `
    <div class="cart-item">

      <img src="${item.img}" class="cart-item-img" alt="${item.nombre}">

      <div class="cart-item-info">
        <p class="cart-item-name">${item.nombre}</p>
        ${
          item.preventa
            ? `<p class="cart-item-preventa-label mb-1">Preventa</p>`
            : ""
        }
        <p class="cart-item-size">
        ${
        item.marco
      ? `Marco: ${item.marco}`
      : item.talle
        ? `Talle: ${item.talle}`
        : ""
        }
        </p>
        <p class="cart-item-price">
          $${Number(item.precio).toLocaleString("es-AR")}
        </p>

        <div class="cart-qty">
          <button class="qty-btn" onclick="changeQty(${index}, -1)">−</button>
          <span>${item.cantidad}</span>
          <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
        </div>
      </div>

      <button class="cart-remove" onclick="removeFromCart(${index})">
        <i class="bi bi-trash"></i>
      </button>

    </div>
  `).join("");

  const total = cart.reduce(
    (acc, p) => acc + Number(p.precio) * p.cantidad,
    0
  );

  totalEl.textContent = `$${total.toLocaleString("es-AR")}`;
  updateCartCount();
}

window.changeQty = changeQty;
window.removeFromCart = removeFromCart;
window.renderCart = renderCart;

document.addEventListener("DOMContentLoaded", () => {
  const offcanvas = document.getElementById("cartOffcanvas");
  if (!offcanvas) return;

  offcanvas.addEventListener("shown.bs.offcanvas", () => {
    renderCart();
  });
});
