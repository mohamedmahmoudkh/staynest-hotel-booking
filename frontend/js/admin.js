const Admin = {
  list(r, key) {
    return Array.isArray(r.data) ? r.data : Array.isArray(r[key]) ? r[key] : [];
  },
  async init() {
    const user = UI.user();
    if (!user || user.role !== "admin") {
      location.href = `../login.html?next=${encodeURIComponent(location.href)}`;
      return;
    }
    if (document.querySelector("#dashboard-stats")) await this.dashboard();
    if (document.querySelector("#hotels-table-body")) await this.hotels();
    if (document.querySelector("#rooms-table-body")) await this.rooms();
    if (document.querySelector("#bookings-table-body-admin")) await this.bookings();
  },
  async dashboard() {
    const box = document.querySelector("#dashboard-stats");
    const r = await StayNestAPI.stats();
    if (!r.success) {
      box.innerHTML = `<div class="alert alert-error">${UI.escape(r.message || "Could not load dashboard statistics.")}</div>`;
      return;
    }
    const d = r.data || {};
    const stats = [
      ["Users", d.total_users ?? 0, "👤"],
      ["Hotels", d.total_hotels ?? 0, "🏨"],
      ["Rooms", d.total_rooms ?? 0, "🛏"],
      ["Bookings", d.total_bookings ?? 0, "📋"],
    ];
    box.innerHTML = stats.map(([label, value, icon]) =>
      `<div class="card admin-stat"><div class="admin-stat-icon">${icon}</div><div class="admin-stat-value">${UI.escape(value)}</div><div class="admin-stat-label">${label}</div></div>`
    ).join("");

    const recent = document.querySelector("#recent-bookings");
    if (recent) {
      const bookingsResult = await StayNestAPI.allBookings();
      const bookings = this.list(bookingsResult, "bookings").slice(0, 5);
      if (!bookingsResult.success) {
        recent.innerHTML = `<tr><td colspan="5">${UI.escape(bookingsResult.message || "Could not load recent bookings.")}</td></tr>`;
      } else if (!bookings.length) {
        recent.innerHTML = '<tr><td colspan="5" class="empty">No bookings yet.</td></tr>';
      } else {
        recent.innerHTML = bookings.map(b => `
          <tr>
            <td><strong>${UI.escape(b.user_name || b.name || "Guest")}</strong><div class="small muted">${UI.escape(b.user_email || "")}</div></td>
            <td>${UI.escape(b.hotel_name || "Hotel")}</td>
            <td>${UI.date(b.check_in)} → ${UI.date(b.check_out)}</td>
            <td>${UI.money(b.total_price)}</td>
            <td><span class="status-pill">${UI.escape(b.status || "confirmed")}</span></td>
          </tr>`).join("");
      }
    }
  },
  async hotels() {
    const body = document.querySelector("#hotels-table-body"),
      r = await StayNestAPI.hotels(),
      list = this.list(r, "hotels");
    if (!r.success) {
      body.innerHTML = `<tr><td colspan="5">${UI.escape(r.message || "Failed to load hotels.")}</td></tr>`;
      return;
    }
    body.innerHTML = list
      .map(
        (h) =>
          `<tr><td>${UI.escape(h.id)}</td><td>${UI.escape(h.name)}</td><td>${UI.escape(h.location || "")}</td><td>${UI.escape(h.rating ?? "—")}</td><td><button class="btn btn-secondary" data-edit-hotel='${UI.escape(JSON.stringify(h))}'>Edit</button> <button class="btn btn-danger" data-delete-hotel="${UI.escape(h.id)}">Delete</button></td></tr>`,
      )
      .join("");
    document
      .querySelector("#hotel-form")
      ?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const f = e.currentTarget;
        const payload = {
          name: f.name.value.trim(),
          location: f.location.value.trim(),
          description: f.description.value.trim(),
          image: f.image.value.trim(),
          rating: Number(f.rating?.value || 0),
        };
        const id = f.id.value;
        const r = id
          ? await StayNestAPI.updateHotel({ ...payload, id: Number(id) })
          : await StayNestAPI.addHotel(payload);
        UI.toast(r.message || (r.success ? "Saved." : "Failed."));
        if (r.success) {
          f.reset();
          f.id.value = "";
          document.querySelector("#hotel-form-title").textContent = "Add hotel";
          this.hotels();
        }
      });
    body.onclick = async (e) => {
      const edit = e.target.closest("[data-edit-hotel]"),
        del = e.target.closest("[data-delete-hotel]");
      if (edit) {
        const h = JSON.parse(edit.dataset.editHotel);
        const f = document.querySelector("#hotel-form");
        f.id.value = h.id;
        f.name.value = h.name || "";
        f.location.value = h.location || "";
        f.description.value = h.description || "";
        f.image.value = h.image || "";
        if (f.rating) f.rating.value = h.rating ?? 0;
        document.querySelector("#hotel-form-title").textContent = "Edit hotel";
        scrollTo({ top: 0, behavior: "smooth" });
      }
      if (del && confirm("Delete this hotel?")) {
        const r = await StayNestAPI.deleteHotel(del.dataset.deleteHotel);
        UI.toast(r.message || "Done");
        if (r.success) this.hotels();
      }
    };
  },
  async rooms() {
    const body = document.querySelector("#rooms-table-body"),
      r = await StayNestAPI.rooms(),
      list = this.list(r, "rooms");
    if (!r.success) {
      body.innerHTML = `<tr><td colspan="8">${UI.escape(r.message || "Failed to load rooms.")}</td></tr>`;
      return;
    }
    body.innerHTML = list
      .map(
        (x) =>
          `<tr><td>${UI.escape(x.id)}</td><td>${UI.escape(x.hotel_id)}</td><td>${UI.escape(x.room_number ?? "—")}</td><td>${UI.escape(x.room_type)}</td><td>${UI.money(x.price)}</td><td>${UI.escape(x.capacity)}</td><td>${x.available === "0" || x.available === 0 ? "Unavailable" : "Available"}</td><td><button class="btn btn-secondary" data-edit-room='${UI.escape(JSON.stringify(x))}'>Edit</button> <button class="btn btn-danger" data-delete-room="${UI.escape(x.id)}">Delete</button></td></tr>`,
      )
      .join("");
    document
      .querySelector("#room-form")
      ?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const f = e.currentTarget;
        const payload = {
          hotel_id: Number(f.hotel_id.value),
          room_number: f.room_number.value.trim(),
          room_type: f.room_type.value.trim(),
          price: Number(f.price.value),
          capacity: Number(f.capacity.value),
          description: f.description.value.trim(),
          image: f.image.value.trim(),
          available: f.available.value,
        };
        const id = f.id.value;
        const r = id
          ? await StayNestAPI.updateRoom({ ...payload, id: Number(id) })
          : await StayNestAPI.addRoom(payload);
        UI.toast(r.message || (r.success ? "Saved." : "Failed."));
        if (r.success) {
          f.reset();
          f.id.value = "";
          document.querySelector("#room-form-title").textContent = "Add room";
          this.rooms();
        }
      });
    body.onclick = async (e) => {
      const edit = e.target.closest("[data-edit-room]"),
        del = e.target.closest("[data-delete-room]");
      if (edit) {
        const x = JSON.parse(edit.dataset.editRoom),
          f = document.querySelector("#room-form");
        f.id.value = x.id;
        f.hotel_id.value = x.hotel_id || "";
        f.room_number.value = x.room_number || "";
        f.room_type.value = x.room_type || "";
        f.price.value = x.price || "";
        f.capacity.value = x.capacity || "";
        f.description.value = x.description || "";
        f.image.value = x.image || "";
        f.available.value =
          x.available === "0" || x.available === 0 ? "0" : "1";
        document.querySelector("#room-form-title").textContent = "Edit room";
        scrollTo({ top: 0, behavior: "smooth" });
      }
      if (del && confirm("Delete this room?")) {
        const r = await StayNestAPI.deleteRoom(del.dataset.deleteRoom);
        UI.toast(r.message || "Done");
        if (r.success) this.rooms();
      }
    };
  },
  async bookings() {
    const body = document.querySelector("#bookings-table-body-admin"),
      r = await StayNestAPI.allBookings(),
      list = this.list(r, "bookings");
    if (!r.success) {
      body.innerHTML = `<tr><td colspan="10">${UI.escape(r.message || "Failed to load bookings.")}</td></tr>`;
      return;
    }
    body.innerHTML = list
      .map(
        (b) =>
          `<tr><td>${UI.escape(b.booking_id ?? b.id)}</td><td>${UI.escape(b.user_name || b.name || b.user_email || "User")}</td><td>${UI.escape(b.hotel_name || "Hotel")}</td><td>${UI.escape(b.room_number || b.room_type || "Room")}</td><td>${UI.date(b.check_in)}</td><td>${UI.date(b.check_out)}</td><td>${UI.escape(b.guests)}</td><td>${UI.money(b.total_price)}</td><td><select data-status="${UI.escape(b.booking_id ?? b.id)}"><option value="pending" ${b.status === "pending" ? "selected" : ""}>Pending</option><option value="confirmed" ${b.status === "confirmed" ? "selected" : ""}>Confirmed</option><option value="cancelled" ${b.status === "cancelled" ? "selected" : ""}>Cancelled</option><option value="completed" ${b.status === "completed" ? "selected" : ""}>Completed</option></select></td><td><button class="btn btn-primary" data-save-status="${UI.escape(b.booking_id ?? b.id)}">Save</button></td></tr>`,
      )
      .join("");
    body.addEventListener("click", async (e) => {
      const btn = e.target.closest("[data-save-status]");
      if (!btn) return;
      const id = btn.dataset.saveStatus,
        status = body.querySelector(
          `select[data-status="${CSS.escape(id)}"]`,
        ).value;
      const r = await StayNestAPI.updateBookingStatus(id, status);
      UI.toast(r.message || "Updated.");
    });
  },
};
document.addEventListener("DOMContentLoaded", () => Admin.init());
