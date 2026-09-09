const StayNestAPI = (() => {
  // Frontend pages live in /frontend; admin pages live in /frontend/admin.
  // This keeps the frontend aligned with the README project structure:
  // staynest-hotel-booking/{frontend,backend}.
  const isAdminPage = window.location.pathname
    .replaceAll("\\", "/")
    .includes("/frontend/admin/");
  const base =
    window.location.protocol === "file:"
      ? null
      : isAdminPage
        ? "../../backend"
        : "../backend";

  async function request(endpoint, options = {}) {
    if (!base) {
      return {
        success: false,
        message:
          "Open StayNest through XAMPP: http://localhost/staynest-hotel-booking/ — do not open the HTML files directly from File Explorer.",
      };
    }

    const config = {
      method: options.method || "GET",
      credentials: "include",
      headers: {
        ...(options.body ? { "Content-Type": "application/json" } : {}),
        ...(options.headers || {}),
      },
      ...options,
    };

    if (config.body && typeof config.body !== "string") {
      config.body = JSON.stringify(config.body);
    }

    try {
      const response = await fetch(`${base}${endpoint}`, config);
      const text = await response.text();

      let data;
      try {
        data = text ? JSON.parse(text) : {};
      } catch {
        data = {
          success: response.ok,
          message: text || `Request failed (${response.status})`,
        };
      }

      if (!response.ok && typeof data.success === "undefined")
        data.success = false;
      return data;
    } catch (error) {
      return {
        success: false,
        message:
          "Cannot connect to the PHP backend. Start Apache in XAMPP and open the project through localhost.",
        error,
      };
    }
  }

  return {
    base,
    request,

    // README Authentication endpoints
    register: (body) => request("/auth/register.php", { method: "POST", body }),
    login: (body) => request("/auth/login.php", { method: "POST", body }),
    logout: () => request("/auth/logout.php", { method: "POST" }),

    // README Users endpoints
    profile: () => request("/users/profile.php"),
    getUser: () => request("/users/get-user.php"),

    // README Hotels endpoints
    hotels: (params = {}) => {
      const query = new URLSearchParams();
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== "") query.set(key, value);
      });
      return request(`/hotels/get-hotels.php${query.toString() ? `?${query}` : ""}`);
    },
    hotel: (id) =>
      request(`/hotels/get-hotel.php?id=${encodeURIComponent(id)}`),
    addHotel: (body) =>
      request("/hotels/add-hotel.php", { method: "POST", body }),
    updateHotel: (body) =>
      request("/hotels/update-hotel.php", { method: "POST", body }),
    deleteHotel: (id) =>
      request("/hotels/delete-hotel.php", { method: "POST", body: { id } }),

    // README Rooms endpoints
    rooms: (hotelId) =>
      request(
        `/rooms/get-rooms.php${hotelId ? `?hotel_id=${encodeURIComponent(hotelId)}` : ""}`,
      ),
    availableRooms: (hotelId, checkIn, checkOut) =>
      request(`/rooms/get-available-rooms.php?hotel_id=${encodeURIComponent(hotelId)}&check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}`),
    addRoom: (body) => request("/rooms/add-room.php", { method: "POST", body }),
    updateRoom: (body) =>
      request("/rooms/update-room.php", { method: "POST", body }),
    deleteRoom: (id) =>
      request("/rooms/delete-room.php", { method: "POST", body: { id } }),

    // README Bookings endpoints
    createBooking: (body) =>
      request("/bookings/create-booking.php", { method: "POST", body }),
    myBookings: () => request("/bookings/my-bookings.php"),
    cancelBooking: (id) =>
      request("/bookings/cancel-booking.php", {
        method: "POST",
        body: { booking_id: id },
      }),
    allBookings: () => request("/bookings/all-bookings.php"),
    stats: () => request("/admin/get-stats.php"),

    // Optional Task-5 extensions. The core README endpoints above remain the source of truth.
    availability: (roomId, checkIn, checkOut) =>
      request(
        `/bookings/check-availability.php?room_id=${encodeURIComponent(roomId)}&check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}`,
      ),
    updateBookingStatus: (id, status) =>
      request("/bookings/update-status.php", {
        method: "POST",
        body: { booking_id: id, status },
      }),
  };
})();
