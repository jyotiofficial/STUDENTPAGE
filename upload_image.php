<?php
// Assuming you have stored the uploaded image URL in a variable named $uploadedImageUrl
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profileImage"])) {
    $targetDir = "internship-portal/pages/student/Profile_Images/"; // Replace with the path to your desired upload folder
    $targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if the image file is a actual image or fake image
    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profileImage"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
            echo "The file " . basename($_FILES["profileImage"]["name"]) . " has been uploaded.";
            $profileImageUrl = $targetFile; // Update the profile image URL variable

            // Assuming you have stored the user's ID in the session
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                // Replace 'your_host', 'your_username', 'your_password', and 'your_database' with your actual database credentials
                $con = mysqli_connect('localhost', 'root', '', 'internship_portal');
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    exit();
                }

                // Update the 'profile_image_url' in the 'users' table for the specific user
                $queryUpdateProfileImage = "UPDATE users SET profile_image_url = '$profileImageUrl' WHERE id = '$user_id'";

                if (mysqli_query($con, $queryUpdateProfileImage)) {
                    echo "Profile image updated successfully!";
                } else {
                    echo "Error updating profile image: " . mysqli_error($con);
                }

                mysqli_close($con);
            } else {
                echo "User ID not found in session.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
