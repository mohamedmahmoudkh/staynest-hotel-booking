document.addEventListener("DOMContentLoaded", () => {
  const box = document.querySelector("#confirmation-card");
  let bookingData = {};

  try {
    bookingData = JSON.parse(
      sessionStorage.getItem("staynest_last_booking") || "{}",
    );
  } catch {
    bookingData = {};
  }

  const booking = bookingData.booking || {};
  const hotel = booking.hotel_name || bookingData.hotel?.name || "Your hotel";
  const room =
    booking.room_type || bookingData.room?.room_type || "Selected room";
  const bookingId =
    booking.booking_id || booking.id || UI.query("booking_id") || "—";
  const total = booking.total_price ?? "";

  box.innerHTML = `
    <div class="confirm-head">
      <img
        src="${UI.escape(
          booking.hotel_image ||
            bookingData.hotel?.image ||
            "https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=500&q=80",
        )}"
        alt=""
      >
      <div>
        <h2>${UI.escape(hotel)}</h2>
        <p class="muted">Booking ID: #${UI.escape(bookingId)}</p>
      </div>
    </div>

    <div class="confirm-grid">
      <div>
        <small>Room</small>
        <b>${UI.escape(room)}</b>
      </div>
      <div>
        <small>Check-in</small>
        <b>${UI.date(booking.check_in || bookingData.checkIn)}</b>
      </div>
      <div>
        <small>Check-out</small>
        <b>${UI.date(booking.check_out || bookingData.checkOut)}</b>
      </div>
      <div>
        <small>Guests</small>
        <b>${UI.escape(booking.guests || bookingData.guests || "—")}</b>
      </div>
    </div>

    <div class="summary-row summary-total">
      <span>Total</span>
      <span>
        ${total !== "" ? UI.money(total) : "Calculated by booking system"}
      </span>
    </div>
  `;
});
