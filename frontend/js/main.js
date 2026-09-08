// Mobile nav toggle
const toggle = document.querySelector(".navbar__toggle");
const links = document.querySelector(".navbar__links");

if (toggle && links) {
  toggle.addEventListener("click", () => {
    const isOpen = links.style.display === "flex";
    links.style.display = isOpen ? "none" : "flex";
    links.style.flexDirection = "column";
    links.style.gap = "16px";
    toggle.setAttribute("aria-expanded", String(!isOpen));
  });
}

//search
const searchForm = document.getElementById("search");

if (searchForm) {
  searchForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const data = Object.fromEntries(new FormData(searchForm).entries());
    const params = new URLSearchParams(data);
    window.location.href = `hotels.html?${params.toString()}`;
  });
}
