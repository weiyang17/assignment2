<?php
include 'config.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM book WHERE id=$id");
    $booking = mysqli_fetch_assoc($query);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $city = $_POST['city'];
        $room_type = $_POST['room_type'];
        $date = $_POST['date'];
        $price = $_POST['price'];
        $payment_method = $_POST['payment_method'];

        $update = "UPDATE book SET city='$city', room_type='$room_type', date='$date', price='$price', payment_method='$payment_method' WHERE id=$id";
        if (mysqli_query($conn, $update)) {
            $_SESSION['success'] = "Booking ID #$id updated successfully.";
            header('Location: edit_booking.php?id=' . $id);
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
} else {
    header('Location: booking_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <style>
    body {
      background: #111;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-box {
      background: #1e1e1e;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(255, 215, 0, 0.45);
      width: 400px;
    }

    h3 {
      text-align: center;
      color: gold;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    select, input[type="date"], input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 2px solid gold;
      border-radius: 6px;
      background: #2a2a2a;
      color: #fff;
      font-size: 16px;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(90%) sepia(80%) saturate(700%) hue-rotate(10deg) brightness(115%) contrast(105%);
    }

    button, .cancel {
      width: 48%;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
    }

    button {
      background: gold;
      color: black;
    }

    .cancel {
      background: #666;
      color: white;
    }

    button:hover {
      background: #ffcc00;
    }

    .cancel:hover {
      background: #888;
    }

    .success-box {
      background: #4CAF50;
      padding: 10px 15px;
      border-radius: 6px;
      color: white;
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(76, 175, 80, 0.4);
    }

    .back-home {
      text-align: center;
      margin-top: 20px;
    }

    .back-home a {
      display: inline-block;
      padding: 10px 20px;
      background: transparent;
      color: gold;
      border: 2px solid gold;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
      transition: 0.3s ease;
    }

    .back-home a:hover {
      background-color: gold;
      color: black;
    }
  </style>

  <script>
    function updatePrice() {
      const city = document.getElementById('city').value;
      const roomType = document.getElementById('room_type').value;
      const priceInput = document.getElementById('price');

      const priceMap = {
        "Paris": {
          "Single Bed Room": "$100",
          "Double Bed Room": "$150",
          "Standard Suite": "$200",
          "Presidential Suite": "$500"
        },
        "London": {
          "Single Bed Room": "$110",
          "Double Bed Room": "$160",
          "Standard Suite": "$210",
          "Presidential Suite": "$520"
        },
        "Rome": {
          "Single Bed Room": "$120",
          "Double Bed Room": "$170",
          "Standard Suite": "$220",
          "Presidential Suite": "$530"
        },
        "Beijing": {
          "Single Bed Room": "$90",
          "Double Bed Room": "$140",
          "Standard Suite": "$190",
          "Presidential Suite": "$480"
        },
        "Tokyo": {
          "Single Bed Room": "$130",
          "Double Bed Room": "$180",
          "Standard Suite": "$230",
          "Presidential Suite": "$540"
        },
        "Kuala Lumpur": {
          "Single Bed Room": "$80",
          "Double Bed Room": "$130",
          "Standard Suite": "$180",
          "Presidential Suite": "$470"
        }
      };

      priceInput.value = priceMap[city][roomType] || "";
    }

    window.addEventListener("DOMContentLoaded", () => {
      updatePrice();
      document.getElementById("city").addEventListener("change", updatePrice);
      document.getElementById("room_type").addEventListener("change", updatePrice);
    });
  </script>
</head>
<body>
  <div class="form-box">
    <h3>Edit Booking #<?= $booking['id'] ?></h3>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="success-box"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="post">
      <label>City:
        <select name="city" id="city" required>
          <?php
          $cities = ["Paris", "London", "Rome", "Beijing", "Tokyo", "Kuala Lumpur"];
          foreach ($cities as $city) {
            $selected = $booking['city'] === $city ? 'selected' : '';
            echo "<option value=\"$city\" $selected>$city</option>";
          }
          ?>
        </select>
      </label>

      <label>Room Type:
        <select name="room_type" id="room_type" required>
          <?php
          $types = ["Single Bed Room", "Double Bed Room", "Standard Suite", "Presidential Suite"];
          foreach ($types as $type) {
            $selected = $booking['room_type'] === $type ? 'selected' : '';
            echo "<option value=\"$type\" $selected>$type</option>";
          }
          ?>
        </select>
      </label>

      <label>Date:
        <input type="date" name="date" value="<?= $booking['date'] ?>" required>
      </label>

      <label>Price:
        <input type="text" name="price" id="price" value="<?= $booking['price'] ?>" required>
      </label>

      <label>Payment:
        <select name="payment_method" required>
          <?php
          $methods = ["Visa", "MasterCard", "PayPal", "AliPay"];
          foreach ($methods as $method) {
            $selected = $booking['payment_method'] === $method ? 'selected' : '';
            echo "<option value=\"$method\" $selected>$method</option>";
          }
          ?>
        </select>
      </label>

      <div style="display: flex; justify-content: space-between;">
        <button type="submit">Update</button>
        <a class="cancel" href="booking_list.php">Cancel</a>
      </div>
    </form>

    <div class="back-home">
      <a href="index.html">&larr; Return to Home</a>
    </div>
  </div>
</body>
</html>