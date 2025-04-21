document.addEventListener("DOMContentLoaded", function () {
    const countrySelect = document.getElementById("country");
    const hotelImage = document.getElementById("hotel-image");
    const roomTypeSpan = document.getElementById("room-type");
    const roomImage = document.getElementById("room-image");
    const roomPrice = document.getElementById("room-price");
    const prevRoomBtn = document.getElementById("prev-room");
    const nextRoomBtn = document.getElementById("next-room");
    const paymentBtn = document.getElementById("payment-btn");
    const selectedRoomTypeInput = document.getElementById("selected-room-type");
    const selectedRoomPriceInput = document.getElementById("selected-room-price");
    const bookingForm = document.getElementById("booking-form");

    const hotelImages = {
        "Paris": "images/Paris.jpg",
        "London": "images/London.jpg",
        "Rome": "images/Rome.jpg",
        "Beijing": "images/Beijing.jpg",
        "Tokyo": "images/Tokyo.jpg",
        "Kuala Lumpur": "images/Kuala Lumpur.jpg"
    };

    const roomTypes = ["Single Bed Room", "Double Bed Room", "Standard Suite", "Presidential Suite"];

    const priceTable = {
        "Paris": [100, 150, 200, 500],
        "London": [110, 160, 210, 520],
        "Rome": [120, 170, 220, 530],
        "Beijing": [90, 140, 190, 480],
        "Tokyo": [130, 180, 230, 540],
        "Kuala Lumpur": [80, 130, 180, 470]
    };

    let currentRoomIndex = 0;

    function getRoomImage(city, roomName) {
        const base = `images/${city}-${roomName}.png`;
        return base.replace(/\s/g, '%20'); // 防止空格导致路径错误
    }

    function updateRoomDisplay(country) {
        const name = roomTypes[currentRoomIndex];
        const price = priceTable[country][currentRoomIndex];
        roomTypeSpan.textContent = name;
        roomImage.src = getRoomImage(country, name);
        roomPrice.textContent = `$${price}`;
        selectedRoomTypeInput.value = name;
        selectedRoomPriceInput.value = `$${price}`;
    }

    countrySelect.addEventListener("change", function () {
        currentRoomIndex = 0;
        hotelImage.src = hotelImages[this.value] || "images/default-hotel.jpg";
        updateRoomDisplay(this.value);
    });

    prevRoomBtn.addEventListener("click", function () {
        currentRoomIndex = (currentRoomIndex - 1 + roomTypes.length) % roomTypes.length;
        updateRoomDisplay(countrySelect.value);
    });

    nextRoomBtn.addEventListener("click", function () {
        currentRoomIndex = (currentRoomIndex + 1) % roomTypes.length;
        updateRoomDisplay(countrySelect.value);
    });

    paymentBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const selectedCountry = countrySelect.value;
        const selectedDate = document.getElementById("date").value;
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

        if (selectedCountry && selectedDate && selectedPaymentMethod) {
            bookingForm.submit();
        } else {
            alert("Please fill in all required fields.");
        }
    });

    // 页面初始化
    updateRoomDisplay(countrySelect.value);
});