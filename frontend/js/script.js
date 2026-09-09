/* =========================
   SELECT ROOM
========================= */

function selectRoom(roomName, roomPrice) {

    localStorage.setItem("roomName", roomName);

    localStorage.setItem("roomPrice", roomPrice);

    window.location.href = "booking.html";
}


/* =========================
   LOAD SELECTED ROOM
========================= */

document.addEventListener("DOMContentLoaded", function () {

    const roomElement =
        document.getElementById("selectedRoom");

    const priceElement =
        document.getElementById("totalPrice");


    const roomName =
        localStorage.getItem("roomName");

    const roomPrice =
        localStorage.getItem("roomPrice");


    if (roomElement && roomName) {

        roomElement.textContent = roomName;

    }


    if (priceElement && roomPrice) {

        const nights = 4;

        const total =
            Number(roomPrice) * nights;

        priceElement.textContent =
            "$" + total;

    }

});


/* =========================
   CONFIRM BOOKING
========================= */

function confirmBooking(event) {

    event.preventDefault();


    const name =
        document.getElementById("name").value;

    const email =
        document.getElementById("email").value;


    localStorage.setItem(
        "guestName",
        name
    );

    localStorage.setItem(
        "guestEmail",
        email
    );


    window.location.href =
        "confirmation.html";
}


/* =========================
   CANCEL BOOKING
========================= */

function cancelBooking(button) {

    const answer =
        confirm(
            "Are you sure you want to cancel this booking?"
        );


    if (answer) {

        const row =
            button.closest("tr");


        row.style.opacity = "0.5";


        button.disabled = true;


        button.textContent =
            "Cancelled";


        button.style.color =
            "#777";


        button.style.borderColor =
            "#aaa";

    }

}