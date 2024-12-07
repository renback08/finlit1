  <?php
  include 'connect.php';

  if (isset($_GET['id'])) {
      $userId = $_GET['id'];
      $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $result = $stmt->get_result();
    
      if ($user = $result->fetch_assoc()) {
          $user['profile_picture'] = $user['profile_picture'] ?: 'default.jpg';
          echo json_encode($user);
      } else {
          echo json_encode(['error' => 'User not found']);
      }
  } 
  $conn->close();
  ?>
