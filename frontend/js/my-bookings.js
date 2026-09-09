document.addEventListener("DOMContentLoaded", async () => {
  const body = document.querySelector("#bookings-body");
  if (!body) return;
  const user = UI.user();
  if (!user) {
    body.innerHTML =
      '<tr><td colspan="8"><div class="empty">Please log in to view your bookings.</div></td></tr>';
    return;
  }
  const result = await StayNestAPI.myBookings();
  const list = Array.isArray(result.data)
    ? result.data
    : Array.isArray(result.bookings)
      ? result.bookings
      : [];
  if (!result.success) {
    body.innerHTML = `<tr><td colspan="8">${UI.escape(result.message || "Could not load bookings.")}</td></tr>`;
    return;
  }
  if (!list.length) {
    body.innerHTML =
      '<tr><td colspan="8"><div class="empty">You have no bookings yet.</div></td></tr>';
    return;
  }
  body.innerHTML = list
    .map((b) => {
      const status = String(b.status || "pending").toLowerCase();
      const canCancel = status === "confirmed";
      return `<tr>
      <td><div class="hotel-cell"><img src="${UI.escape(b.hotel_image || "https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=300&q=75")}" alt=""><span><strong>${UI.escape(b.hotel_name || "Hotel")}</strong><small class="muted">${UI.escape(b.location || "")}</small></span></div></td>
      <td>${UI.escape(b.room_type || b.room_number || "Room")}</td><td>${UI.date(b.check_in)}</td><td>${UI.date(b.check_out)}</td>
      <td>${UI.escape(b.guests ?? "—")}</td><td>${UI.money(b.total_price)}</td>
      <td><span class="status status-${status}">${UI.escape(status)}</span></td>
      <td>${canCancel ? `<button class="btn btn-danger" data-cancel="${UI.escape(b.booking_id ?? b.id)}">Cancel</button>` : "—"}</td>
    </tr>`;
    })
    .join("");
  body.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-cancel]");
    if (!btn) return;
    if (!confirm("Cancel this booking?")) return;
    btn.disabled = true;
    const result = await StayNestAPI.cancelBooking(btn.dataset.cancel);
    if (result.success) {
      UI.toast("Booking cancelled.");
      location.reload();
    } else {
      UI.toast(result.message || "Could not cancel booking.");
      btn.disabled = false;
    }
  });
});
