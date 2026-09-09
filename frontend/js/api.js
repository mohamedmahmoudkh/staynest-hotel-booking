// Adjust this if your project folder inside htdocs is not named "staynest"
const API_BASE = "/staynest/backend";

async function apiRequest(endpoint, method = "GET", body = null) {
    const options = {
        method,
        headers: { "Content-Type": "application/json" },
        credentials: "include"
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, options);
    return response.json();
}

// ----- Bookings (Task 5) -----

function createBooking(bookingData) {
    return apiRequest("/bookings/create-booking.php", "POST", bookingData);
}

function checkAvailability(roomId, checkIn, checkOut) {
    const query = `room_id=${roomId}&check_in=${checkIn}&check_out=${checkOut}`;
    return apiRequest(`/bookings/check-availability.php?${query}`, "GET");
}

function getMyBookings() {
    return apiRequest("/bookings/my-bookings.php", "GET");
}

function cancelBooking(bookingId) {
    return apiRequest("/bookings/cancel-booking.php", "POST", { booking_id: bookingId });
}

function getAllBookingsAdmin() {
    return apiRequest("/bookings/all-bookings.php", "GET");
}

function updateBookingStatusAdmin(bookingId, status) {
    return apiRequest("/bookings/update-status.php", "POST", { booking_id: bookingId, status });
}

function getDashboardStats() {
    return apiRequest("/admin/get-stats.php", "GET");
}

// ----- Hotels (Task 4 APIs, reused here) -----

function getHotels() {
    return apiRequest("/hotels/get-hotels.php", "GET");
}

function addHotel(hotelData) {
    return apiRequest("/hotels/add-hotel.php", "POST", hotelData);
}

function updateHotel(hotelData) {
    return apiRequest("/hotels/update-hotel.php", "POST", hotelData);
}

function deleteHotel(hotelId) {
    return apiRequest("/hotels/delete-hotel.php", "POST", { id: hotelId });
}

// ----- Rooms (Task 4 APIs, reused here) -----

function getRooms() {
    return apiRequest("/rooms/get-rooms.php", "GET");
}

function addRoom(roomData) {
    return apiRequest("/rooms/add-room.php", "POST", roomData);
}

function updateRoom(roomData) {
    return apiRequest("/rooms/update-room.php", "POST", roomData);
}

function deleteRoom(roomId) {
    return apiRequest("/rooms/delete-room.php", "POST", { id: roomId });
}
