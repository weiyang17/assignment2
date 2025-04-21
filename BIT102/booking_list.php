<?php
include 'config.php';
session_start();

// 删除提示处理
$successMessage = '';
if (isset($_SESSION['delete_success'])) {
    $successMessage = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']);
}

$result = mysqli_query($conn, "SELECT * FROM book ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Records</title>
  <style>
    body {
      background-color: #111;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      text-align: center;
      margin: 0;
    }

    h1 {
      color: gold;
      margin-top: 30px;
      font-size: 32px;
      text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .success {
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      padding: 15px;
      margin: 20px auto 10px;
      border-radius: 8px;
      width: 80%;
      font-size: 18px;
    }

    .return-btn {
      display: inline-block;
      margin-bottom: 25px;
      padding: 10px 20px;
      background-color: gold;
      color: black;
      font-weight: bold;
      border: 2px solid gold;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s ease;
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
    }

    .return-btn:hover {
      background-color: #ffcc00;
    }

    table {
      width: 95%;
      margin: 0 auto 30px;
      border-collapse: collapse;
      box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
    }

    th, td {
      padding: 12px;
      border: 1px solid #444;
    }

    th {
      background-color: gold;
      color: black;
    }

    td {
      background-color: #222;
    }

    .action-btn {
      padding: 6px 12px;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .edit-btn {
      background: gold;
      color: black;
      text-decoration: none;
    }

    .delete-btn {
      background: #e74c3c;
      color: white;
      text-decoration: none;
    }

    .edit-btn:hover {
      background: #ffcc00;
    }

    .delete-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>
  <h1>Booking Records</h1>

  <?php if ($successMessage): ?>
    <div class="success"><?= $successMessage ?></div>
  <?php endif; ?>

  <!-- ✅ Return to Home Button -->
  <div>
    <a href="index.html" class="return-btn">← Return to Hotel Booking</a>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>City</th>
      <th>Room Type</th>
      <th>Date</th>
      <th>Price</th>
      <th>Payment</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['city'] ?></td>
        <td><?= $row['room_type'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['payment_method'] ?></td>
        <td><?= $row['create_time'] ?></td>
        <td>
          <a href="edit_booking.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
          <a href="delete_booking.php?id=<?= $row['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>