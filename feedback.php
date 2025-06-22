<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $description = trim($_POST['description']);

    // Check if user exists in signup_table
    $checkUser = $conn->prepare("SELECT username FROM signup_table WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('You must be a registered user to give feedback. Please sign up first.'); window.location.href='home.html';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (username, email, text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again!');</script>";
    }

    $stmt->close();
    $checkUser->close();
    $conn->close();
}
?>


<!DOCTYPE html>    
<html>    
<head>    
<meta name="viewport" content="width=device-width, initial-scale=1">    
<style>    
* {    
  box-sizing: border-box;    
}    
    
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #fffce1ab;
  color: #333;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  animation: fadeIn 1.5s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

input[type=text], input[type=email], textarea {    
  width: 100%;    
  padding: 12px;    
  border: 1px solid #ccc;    
  border-radius: 4px;    
  resize: vertical;    
  font-size: 15px;
  margin-bottom: 13px;
  transition: box-shadow 0.3s ease-in-out;
}    

input[type=text]:focus, input[type=email]:focus, textarea:focus {
  box-shadow: 0 0 10px rgba(81, 203, 238, 1);
  border-color: rgba(81, 203, 238, 1);
}

label {    
  padding: 12px 12px 12px 0;    
  display: inline-block;    
  font-weight: bold;
}    
    
input[type=submit] {    
  background-color: #2574a1;    
  color: white;    
  padding: 12px 20px;    
  border: none; 
  width: 15%;
  margin-top: 20px;
  margin-left: 50%;
  border-radius: 4px;    
  cursor: pointer;    
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}    

input[type=submit]:hover {    
  background-color: #1e6bd7;    
  transform: translateY(-3px);
}    

.container {
        border-radius: 5px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 800px;
        height: 500px;
        margin: auto;
        margin-top: 40px;
        animation: slideIn 1s ease-out;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

.label-column {    
  float: left;    
  width: 25%;    
  margin-top: 6px;    
}    

.input-column {    
  float: left;    
  width: 75%;    
  margin-top: 6px;    
}    

.row:after {    
  content: "";    
  display: table;    
  clear: both;    
}    

nav {
        background-color: rgba(0, 0, 0, 0.8);
        padding: 10px;
        display: flex; /* Use flexbox for alignment */
        justify-content: center; /* Center the tabs horizontally */
        align-items: center; /* Center items vertically */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: fadeIn 1.5s ease-in-out;
    }

    nav a {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        font-size: 16px;
        transition: color 0.3s ease, transform 0.2s ease;
        animation: inOut 1.5s ease-in-out infinite alternate;
    }

    nav a:hover {
        color: red;
        transform: scale(1.1);
    }

nav table {
  width: 100%;
  text-align: center;
  color: white;
}

nav td {
  padding: 10px;
}

h2 {
  text-align: center;
  color: #2574a1;
  margin-bottom: 23px;
  animation: fadeIn 2s ease-in-out;
}

footer {
  margin-top: auto;
  text-align: center;
  padding: 10px;
  color: black;
  animation: fadeIn 1.5s ease-in-out;
}

@media (max-width: 600px) {    
  .label-column, .input-column, input[type=submit] {
    width: 100%;    
    margin-top: 0;
  }    

  nav table {
    font-size: 14px;
  }
}
</style>    
</head>    
<body>    
    <nav>
        <span style="color: cyan; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">Cafeteria Management System</span>
        <a href="home.html">HOME</a>
        <a href="items.php">PLACE_ORDER</a>
        <a href="contactus.html">CONTACT US</a>
        <a href="Feedback.php">FEEDBACK</a>
        <a href="logout.php">LOGOUT</a>
    </nav>

  <h2>GIVE YOUR VALUABLE FEEDBACK</h2>    

  <div class="container">    
    <form action="feedback.php" method="post">    
      <div class="row">    
        <div class="label-column">    
          <label for="username">Username</label>    
        </div>    
        <div class="input-column">    
          <input type="text" id="username" name="username" placeholder="Your username.." required>    
        </div>    
      </div>    

      <div class="row">    
        <div class="label-column">    
          <label for="email">Mail Id</label>    
        </div>    
        <div class="input-column">    
          <input type="email" id="email" name="email" placeholder="Your mail id.." required>    
        </div>    
      </div>     

      <div class="row">    
        <div class="label-column">    
          <label for="subject">Feedback</label>    
        </div>    
        <div class="input-column">    
          <textarea id="subject" name="description" placeholder="Write something.." style="height:170px" required></textarea>    
        </div>    
      </div>    

      <div class="row">    
        <input type="submit" value="Submit">    
      </div>    
    </form>    
  </div>    

  <footer>
    &copy; 2025 Cafeteria Management System. All Rights Reserved.
  </footer>

</body>    
</html>

