// Expects a page with:
// <table><tbody id="bookings-table-body"></tbody></table>

document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("bookings-table-body");
    if (!tableBody) return;

    loadBookings();

    async function loadBookings() {
        const response = await getMyBookings();

        if (!response.success) {
            tableBody.innerHTML = `<tr><td colspan="8">${response.message}</td></tr>`;
            return;
        }

        if (response.data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8">You have no bookings yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = "";

        response.data.forEach((booking) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${booking.hotel_name}</td>
                <td>${booking.room_number} (${booking.room_type})</td>
                <td>${booking.check_in}</td>
                <td>${booking.check_out}</td>
                <td>${booking.guests}</td>
                <td>${booking.total_price}</td>
                <td>${booking.status}</td>
                <td>
                    ${booking.status === "confirmed"
                        ? `<button onclick="handleCancel(${booking.booking_id})">Cancel</button>`
                        : "-"}
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    window.handleCancel = async (bookingId) => {
        if (!confirm("Cancel this booking?")) return;

        const result = await cancelBooking(bookingId);
        alert(result.message);

        if (result.success) {
            loadBookings();
        }
    };
});
