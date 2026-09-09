// Expects a page with:
// - a form: <form id="booking-form">
// - inputs: #check_in, #check_out, #guests
// - a status element: #booking-message
// - room_id passed in the URL, e.g. book.html?room_id=4

document.addEventListener("DOMContentLoaded", () => {
    const bookingForm = document.getElementById("booking-form");
    const messageBox = document.getElementById("booking-message");

    if (!bookingForm) return;

    const urlParams = new URLSearchParams(window.location.search);
    const roomId = urlParams.get("room_id");

    bookingForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (!roomId) {
            messageBox.textContent = "No room selected.";
            return;
        }

        const checkIn = document.getElementById("check_in").value;
        const checkOut = document.getElementById("check_out").value;
        const guests = document.getElementById("guests").value;

        messageBox.textContent = "Checking availability...";

        const availability = await checkAvailability(roomId, checkIn, checkOut);

        if (!availability.available) {
            messageBox.textContent = availability.message;
            return;
        }

        const result = await createBooking({
            room_id: parseInt(roomId),
            check_in: checkIn,
            check_out: checkOut,
            guests: parseInt(guests)
        });

        if (result.success) {
            messageBox.textContent = `Booking confirmed! Booking ID: ${result.booking_id}`;
            bookingForm.reset();
        } else {
            messageBox.textContent = result.message;
        }
    });
});
