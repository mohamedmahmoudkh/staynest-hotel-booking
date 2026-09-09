document.addEventListener("DOMContentLoaded", async () => {
  const form = document.querySelector("#booking-form");
  if (!form) return;
  const roomId = UI.query("room_id"),
    hotelId = UI.query("hotel_id"),
    message = form.querySelector(".form-message");
  if (!roomId) {
    UI.message(message, "No room was selected.");
    form.querySelector("button").disabled = true;
    return;
  }
  const user = UI.user();
  if (!user) {
    UI.message(message, "Please log in before making a booking.", "info");
    setTimeout(
      () =>
        (location.href = `login.html?next=${encodeURIComponent(location.href)}`),
      500,
    );
    return;
  }

  const roomResult = await StayNestAPI.rooms(hotelId);
  const rooms = Array.isArray(roomResult.data) ? roomResult.data : [];
  const room = rooms.find((r) => String(r.id) === String(roomId));
  if (room) {
    document.querySelector("#room-name").textContent =
      room.room_type || "Selected room";
    document.querySelector("#room-price").textContent = UI.money(room.price);
    document.querySelector("#room-capacity").textContent =
      `Up to ${room.capacity ?? "—"} guests`;
    form.guests.max = room.capacity || 20;
  }

  const today = new Date().toISOString().slice(0, 10);
  form.check_in.min = today;
  form.check_in.addEventListener("change", () => {
    form.check_out.min = form.check_in.value;
  });
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const checkIn = form.check_in.value,
      checkOut = form.check_out.value,
      guests = Number(form.guests.value);
    const nights = UI.nights(checkIn, checkOut);
    if (!checkIn || !checkOut || nights <= 0) {
      UI.message(message, "Check-out must be after check-in.");
      return;
    }
    if (!guests || guests < 1) {
      UI.message(message, "Enter a valid number of guests.");
      return;
    }
    const btn = form.querySelector("button[type=submit]");
    btn.disabled = true;
    UI.message(message, "Checking room availability…", "info");
    const availability = await StayNestAPI.availability(roomId, checkIn, checkOut);
    if (!availability.success || availability.data?.available !== true) {
      UI.message(message, availability.message || "Room is not available for the selected dates.");
      btn.disabled = false;
      return;
    }

    UI.message(message, "Creating your booking…", "info");
    const result = await StayNestAPI.createBooking({
      room_id: Number(roomId),
      check_in: checkIn,
      check_out: checkOut,
      guests,
    });
    if (result.success) {
      const booking =
        result.data?.booking || result.data || result.booking || {};
      sessionStorage.setItem(
        "staynest_last_booking",
        JSON.stringify({
          booking,
          room,
          roomId,
          hotelId,
          checkIn,
          checkOut,
          guests,
        }),
      );
      location.href = `confirmation.html${result.booking_id || booking.booking_id ? `?booking_id=${encodeURIComponent(result.booking_id || booking.booking_id)}` : ""}`;
    } else {
      UI.message(message, result.message || "Booking could not be completed.");
      btn.disabled = false;
    }
  });
});
