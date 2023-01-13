<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>.submited{
        font-family: roboto;
        font-size: large;
        font-weight: bold;
        color: black;
        text-align: center;
        color: #280080;
        padding: 12px 8px;
    }</style>
</head>
<body>
<?php
ini_set('upload_max_filesize','10M');
ini_set('post_max_size','10M');

$FirstName = $_POST['First_name'];
$gender = $_POST['gender'];
$LastName = $_POST['Last_name'];
$Standard = $_POST['Standard'];
$Division = $_POST['Division'];
$image = $_FILES['image_file'];

$allowed_file_types = array("image/jpeg", "image/jpg", "image/png");

if ($image['error'] !== UPLOAD_ERR_OK) {
    echo "Upload error: " . $image['error'];
    exit;
}

if (!in_array($image['type'], $allowed_file_types)) {
    echo "Invalid file type. Only jpeg, png, and gif files are allowed.";
    exit;
}

$file_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$new_file_name = uniqid() . "." . $file_extension;
$destination = "uploads/" . $new_file_name;

if (move_uploaded_file($image['tmp_name'], $destination)) {
    $conn = new mysqli('localhost', 'root', '', 'schoolform');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (First_name, gender, Last_name, Standard, Division, image_file) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $FirstName, $gender, $LastName, $Standard, $Division, $new_file_name);
        $stmt->execute();

        echo "<div class=submited>Form Submited</div>";

        $stmt->close();
        $conn->close();
    }
} else {
    echo "Error moving file to destination.";
}
?>
</body>
</html>
