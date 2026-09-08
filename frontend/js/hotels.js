//mock
const HOTELS = [
  {
    id: 1,
    name: "Aurelia Grand",
    location: "Downtown, Cairo",
    price: 250,
    rating: 5,
    type: "hotel",
    amenities: ["breakfast", "wifi", "pool"],
    image:
      "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop",
  },
  {
    id: 2,
    name: "The Palm Residency",
    location: "Marina, Alexandria",
    price: 180,
    rating: 4,
    type: "resort",
    amenities: ["wifi", "parking"],
    image:
      "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1200&auto=format&fit=crop",
  },
  {
    id: 3,
    name: "Juniper Suites",
    location: "New Cairo",
    price: 210,
    rating: 4,
    type: "hotel",
    amenities: ["breakfast", "wifi"],
    image:
      "https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1200&auto=format&fit=crop",
  },
  {
    id: 4,
    name: "Nile View Apartments",
    location: "Zamalek, Cairo",
    price: 140,
    rating: 3,
    type: "apartment",
    amenities: ["wifi", "parking"],
    image:
      "https://images.unsplash.com/photo-1615529162924-f8605388461d?q=80&w=1200&auto=format&fit=crop",
  },
  {
    id: 5,
    name: "Oasis Family Resort",
    location: "Hurghada",
    price: 320,
    rating: 5,
    type: "resort",
    amenities: ["breakfast", "pool", "wifi", "parking"],
    image:
      "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1200&auto=format&fit=crop",
  },
  {
    id: 6,
    name: "Presidential Heights",
    location: "Downtown, Cairo",
    price: 400,
    rating: 5,
    type: "hotel",
    amenities: ["breakfast", "pool", "wifi"],
    image:
      "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1200&auto=format&fit=crop",
  },
];

const grid = document.getElementById("hotelGrid");
const resultCount = document.getElementById("resultCount");
const emptyState = document.getElementById("emptyState");
const priceRange = document.getElementById("priceRange");
const priceValue = document.getElementById("priceValue");
const sortSelect = document.getElementById("sort");
const clearButton = document.getElementById("clearFilters");
const destinationFilter = document.getElementById("destinationFilter");
const resultsContext = document.getElementById("resultsContext");

function amenityLabel(key) {
  const labels = {
    breakfast: "Breakfast",
    wifi: "Wifi",
    pool: "Pool",
    parking: "Parking",
  };
  return labels[key] || key;
}

function renderCard(hotel) {
  const stars = "★".repeat(hotel.rating) + "☆".repeat(5 - hotel.rating);
  const amenitiesHtml = hotel.amenities
    .slice(0, 3)
    .map((a) => `<span>${amenityLabel(a)}</span>`)
    .join("");

  return `
    <article class="hotel-card">
      <div class="hotel-card__image">
        <img src="${hotel.image}" alt="${hotel.name}">
      </div>
      <div class="hotel-card__body">
        <div class="hotel-card__top">
          <div>
            <h3 class="hotel-card__name">${hotel.name}</h3>
            <p class="hotel-card__location">${hotel.location}</p>
          </div>
          <div class="hotel-card__price">
            <strong>$${hotel.price}</strong>
            <span>/ night</span>
          </div>
        </div>
        <p class="hotel-card__stars">${stars}</p>
        <div class="hotel-card__amenities">${amenitiesHtml}</div>
        <a class="hotel-card__cta" href="hotel-details.html?id=${hotel.id}">View Hotel</a>
      </div>
    </article>
  `;
}

function getActiveFilters() {
  const maxPrice = Number(priceRange.value);
  const ratings = Array.from(
    document.querySelectorAll('input[name="rating"]:checked'),
  ).map((el) => Number(el.value));
  const amenities = Array.from(
    document.querySelectorAll('input[name="amenity"]:checked'),
  ).map((el) => el.value);
  const types = Array.from(
    document.querySelectorAll('input[name="type"]:checked'),
  ).map((el) => el.value);
  const destination = destinationFilter.value.trim().toLowerCase();
  return { maxPrice, ratings, amenities, types, destination };
}

function applyFilters(hotels, filters) {
  return hotels.filter((hotel) => {
    if (hotel.price > filters.maxPrice) return false;
    if (
      filters.ratings.length &&
      !filters.ratings.some((r) => hotel.rating >= r)
    )
      return false;
    if (
      filters.amenities.length &&
      !filters.amenities.every((a) => hotel.amenities.includes(a))
    )
      return false;
    if (filters.types.length && !filters.types.includes(hotel.type))
      return false;
    if (filters.destination) {
      const haystack = `${hotel.name} ${hotel.location}`.toLowerCase();
      if (!haystack.includes(filters.destination)) return false;
    }
    return true;
  });
}

function applySort(hotels, sortBy) {
  const sorted = [...hotels];
  if (sortBy === "price-asc") sorted.sort((a, b) => a.price - b.price);
  if (sortBy === "price-desc") sorted.sort((a, b) => b.price - a.price);
  if (sortBy === "rating") sorted.sort((a, b) => b.rating - a.rating);
  return sorted;
}

function render() {
  const filters = getActiveFilters();
  priceValue.textContent =
    filters.maxPrice >= 500 ? "Up to $500" : `Up to $${filters.maxPrice}`;

  let visible = applyFilters(HOTELS, filters);
  visible = applySort(visible, sortSelect.value);

  resultCount.textContent = visible.length;
  emptyState.hidden = visible.length !== 0;
  grid.innerHTML = visible.map(renderCard).join("");
}

document.querySelectorAll(".filters input, .filters select").forEach((el) => {
  el.addEventListener("input", render);
});

sortSelect.addEventListener("change", render);

clearButton.addEventListener("click", () => {
  document
    .querySelectorAll('.filters input[type="checkbox"]')
    .forEach((el) => (el.checked = false));
  destinationFilter.value = "";
  priceRange.value = 500;
  resultsContext.hidden = true;
  render();
});

// Pick up the search the user ran on the Home page
function applyIncomingSearch() {
  const params = new URLSearchParams(window.location.search);
  const destination = params.get("destination");
  const checkIn = params.get("checkIn");
  const checkOut = params.get("checkOut");
  const guests = params.get("guests");

  if (destination) destinationFilter.value = destination;

  if (destination || checkIn || checkOut) {
    const parts = [];
    if (destination) parts.push(`"${destination}"`);
    if (checkIn && checkOut) parts.push(`${checkIn} → ${checkOut}`);
    if (guests) parts.push(`${guests} guest${guests === "1" ? "" : "s"}`);
    resultsContext.textContent = `Showing results for ${parts.join(", ")}`;
    resultsContext.hidden = false;
  }
}

applyIncomingSearch();
render();
