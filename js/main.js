// Alloyé — shared page behavior (mobile menu, accordions)

document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const mainNav = document.getElementById("main-nav");
  const navBackdrop = document.getElementById("nav-backdrop");
  const navClose = document.getElementById("nav-close");

  if (menuToggle && mainNav) {
    function closeNav() {
      mainNav.classList.remove("open");
      if (navBackdrop) navBackdrop.classList.remove("open");
    }

    menuToggle.addEventListener("click", function () {
      mainNav.classList.toggle("open");
      if (navBackdrop) navBackdrop.classList.toggle("open");
    });

    if (navClose) navClose.addEventListener("click", closeNav);
    if (navBackdrop) navBackdrop.addEventListener("click", closeNav);

    mainNav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", closeNav);
    });
  }

  document.querySelectorAll(".accordion-head").forEach(function (head) {
    head.addEventListener("click", function () {
      head.parentElement.classList.toggle("open");
    });
  });

  const newsletterForm = document.querySelector(".newsletter-form");
  if (newsletterForm) {
    newsletterForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const input = newsletterForm.querySelector("input");
      const button = newsletterForm.querySelector("button");
      if (!input.value) return;

      button.disabled = true;
      try {
        const res = await fetch("subscribe.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: input.value })
        });
        const result = await res.json();
        if (!res.ok || !result.success) throw new Error(result.error || "Failed");

        newsletterForm.innerHTML =
          '<p style="opacity:0.85;">Thank you for subscribing.</p>';
      } catch (err) {
        button.disabled = false;
        button.textContent = "Try Again";
      }
    });
  }

  // Search dropdown (anchored under the search icon)
  const searchToggles = document.querySelectorAll(".search-toggle");
  if (searchToggles.length) {
    const overlay = document.createElement("div");
    overlay.className = "search-overlay";
    overlay.id = "search-overlay";
    overlay.innerHTML = `
      <div class="search-overlay-box">
        <form id="search-form">
          <input type="text" id="search-input" placeholder="Search products..." autocomplete="off" />
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>
    `;
    document.body.appendChild(overlay);

    const searchInput = overlay.querySelector("#search-input");
    let activeToggle = null;

    function positionOverlay(toggle) {
      const rect = toggle.getBoundingClientRect();
      const boxWidth = 320;
      let left = rect.right - boxWidth;
      left = Math.max(12, Math.min(left, window.innerWidth - boxWidth - 12));
      overlay.style.top = rect.bottom + 10 + "px";
      overlay.style.left = left + "px";
    }

    function openSearch(toggle) {
      activeToggle = toggle;
      positionOverlay(toggle);
      overlay.classList.add("open");
      searchInput.value = "";
      setTimeout(() => searchInput.focus(), 50);
    }

    function closeSearch() {
      overlay.classList.remove("open");
      activeToggle = null;
    }

    searchToggles.forEach(function (toggle) {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        if (overlay.classList.contains("open")) {
          closeSearch();
        } else {
          openSearch(toggle);
        }
      });
    });

    document.addEventListener("click", function (e) {
      if (
        overlay.classList.contains("open") &&
        !overlay.contains(e.target) &&
        !e.target.closest(".search-toggle")
      ) {
        closeSearch();
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") closeSearch();
    });

    window.addEventListener("resize", function () {
      if (overlay.classList.contains("open") && activeToggle) {
        positionOverlay(activeToggle);
      }
    });

    overlay.querySelector("#search-form").addEventListener("submit", function (e) {
      e.preventDefault();
      const query = searchInput.value.trim();
      if (query) {
        window.location.href = "shop.html?search=" + encodeURIComponent(query);
      }
    });
  }
});
