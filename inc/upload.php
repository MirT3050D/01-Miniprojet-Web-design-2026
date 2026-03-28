<?php
function upload($fileToUpload, $target_dir)
{
    $error_message = '';
    $target_file = $target_dir . basename($_FILES[$fileToUpload]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$fileToUpload]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error_message = "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $error_message = "File already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$fileToUpload]["size"] > 300000) {
        $error_message = "File is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        if ($error_message === '') {
            $error_message = "File was not uploaded.";
        }
        return $error_message;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], $target_file)) {
            return '';
        } else {
            return "Error uploading your file.";
        }
    }
}
