// This single file powers all 4 admin pages.
// Each function checks whether the elements it needs exist on the
// current page, and simply does nothing if they don't.

document.addEventListener("DOMContentLoaded", () => {
    loadDashboardStats();
    loadHotelsAdmin();
    setupHotelForm();
    loadRoomsAdmin();
    setupRoomForm();
    loadBookingsAdmin();
});

// ----- Dashboard -----

async function loadDashboardStats() {
    const statsBox = document.getElementById("dashboard-stats");
    if (!statsBox) return;

    const response = await getDashboardStats();
    if (!response.success) {
        statsBox.textContent = response.message;
        return;
    }

    const data = response.data;
    statsBox.innerHTML = `
        <div class="stat-card"><h3>${data.total_hotels}</h3><p>Total Hotels</p></div>
        <div class="stat-card"><h3>${data.total_rooms}</h3><p>Total Rooms</p></div>
        <div class="stat-card"><h3>${data.total_bookings}</h3><p>Total Bookings</p></div>
        <div class="stat-card"><h3>${data.total_users}</h3><p>Total Users</p></div>
    `;
}

// ----- Hotels -----

async function loadHotelsAdmin() {
    const tableBody = document.getElementById("hotels-table-body");
    if (!tableBody) return;

    const response = await getHotels();
    if (!response.success) return;

    tableBody.innerHTML = "";
    response.data.forEach((hotel) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${hotel.id}</td>
            <td>${hotel.name}</td>
            <td>${hotel.location}</td>
            <td>
                <button onclick='editHotel(${JSON.stringify(hotel)})'>Edit</button>
                <button onclick="deleteHotelAdmin(${hotel.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function setupHotelForm() {
    const form = document.getElementById("hotel-form");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("hotel_id").value;
        const hotelData = {
            name: document.getElementById("hotel_name").value,
            location: document.getElementById("hotel_location").value,
            description: document.getElementById("hotel_description").value,
            image: document.getElementById("hotel_image").value
        };

        let result;
        if (id) {
            hotelData.id = parseInt(id);
            result = await updateHotel(hotelData);
        } else {
            result = await addHotel(hotelData);
        }

        alert(result.message);
        if (result.success) {
            form.reset();
            document.getElementById("hotel_id").value = "";
            loadHotelsAdmin();
        }
    });
}

window.editHotel = (hotel) => {
    document.getElementById("hotel_id").value = hotel.id;
    document.getElementById("hotel_name").value = hotel.name;
    document.getElementById("hotel_location").value = hotel.location;
    document.getElementById("hotel_description").value = hotel.description || "";
    document.getElementById("hotel_image").value = hotel.image || "";
};

window.deleteHotelAdmin = async (id) => {
    if (!confirm("Delete this hotel?")) return;
    const result = await deleteHotel(id);
    alert(result.message);
    if (result.success) loadHotelsAdmin();
};

// ----- Rooms -----

async function loadRoomsAdmin() {
    const tableBody = document.getElementById("rooms-table-body");
    if (!tableBody) return;

    const response = await getRooms();
    if (!response.success) return;

    tableBody.innerHTML = "";
    response.data.forEach((room) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${room.id}</td>
            <td>${room.hotel_id}</td>
            <td>${room.room_number}</td>
            <td>${room.room_type}</td>
            <td>${room.price}</td>
            <td>${room.capacity}</td>
            <td>${room.status}</td>
            <td>
                <button onclick='editRoom(${JSON.stringify(room)})'>Edit</button>
                <button onclick="deleteRoomAdmin(${room.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function setupRoomForm() {
    const form = document.getElementById("room-form");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("room_id").value;
        const roomData = {
            hotel_id: parseInt(document.getElementById("room_hotel_id").value),
            room_number: document.getElementById("room_number").value,
            room_type: document.getElementById("room_type").value,
            price: parseFloat(document.getElementById("room_price").value),
            capacity: parseInt(document.getElementById("room_capacity").value),
            status: document.getElementById("room_status").value
        };

        let result;
        if (id) {
            roomData.id = parseInt(id);
            result = await updateRoom(roomData);
        } else {
            result = await addRoom(roomData);
        }

        alert(result.message);
        if (result.success) {
            form.reset();
            document.getElementById("room_id").value = "";
            loadRoomsAdmin();
        }
    });
}

window.editRoom = (room) => {
    document.getElementById("room_id").value = room.id;
    document.getElementById("room_hotel_id").value = room.hotel_id;
    document.getElementById("room_number").value = room.room_number;
    document.getElementById("room_type").value = room.room_type;
    document.getElementById("room_price").value = room.price;
    document.getElementById("room_capacity").value = room.capacity;
    document.getElementById("room_status").value = room.status;
};

window.deleteRoomAdmin = async (id) => {
    if (!confirm("Delete this room?")) return;
    const result = await deleteRoom(id);
    alert(result.message);
    if (result.success) loadRoomsAdmin();
};

// ----- Bookings -----

async function loadBookingsAdmin() {
    const tableBody = document.getElementById("bookings-table-body-admin");
    if (!tableBody) return;

    const response = await getAllBookingsAdmin();
    if (!response.success) return;

    tableBody.innerHTML = "";
    response.data.forEach((booking) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${booking.booking_id}</td>
            <td>${booking.user_name}</td>
            <td>${booking.hotel_name}</td>
            <td>${booking.room_number}</td>
            <td>${booking.check_in}</td>
            <td>${booking.check_out}</td>
            <td>${booking.guests}</td>
            <td>${booking.total_price}</td>
            <td>${booking.status}</td>
            <td>
                <button onclick="toggleBookingStatus(${booking.booking_id}, '${booking.status}')">
                    ${booking.status === "confirmed" ? "Cancel" : "Confirm"}
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

window.toggleBookingStatus = async (bookingId, currentStatus) => {
    const newStatus = currentStatus === "confirmed" ? "cancelled" : "confirmed";
    const result = await updateBookingStatusAdmin(bookingId, newStatus);
    alert(result.message);
    if (result.success) loadBookingsAdmin();
};
