<?php
function compress_and_save_image($tmp_path, $target_path, $max_width = 1200, $jpeg_quality = 70)
{
    $info = getimagesize($tmp_path);
    if ($info === false) {
        return "File is not an image.";
    }

    $mime = $info["mime"];
    $width = $info[0];
    $height = $info[1];

    switch ($mime) {
        case "image/jpeg":
        case "image/jpg":
            $src = imagecreatefromjpeg($tmp_path);
            break;
        case "image/png":
            $src = imagecreatefrompng($tmp_path);
            break;
        case "image/gif":
            $src = imagecreatefromgif($tmp_path);
            break;
        default:
            return "Only JPG, JPEG, PNG & GIF files are allowed.";
    }

    if (!$src) {
        return "Error uploading your file.";
    }

    if ($width > $max_width) {
        $ratio = $max_width / $width;
        $new_width = $max_width;
        $new_height = (int)($height * $ratio);
    } else {
        $new_width = $width;
        $new_height = $height;
    }

    $dst = imagecreatetruecolor($new_width, $new_height);

    if ($mime === "image/png" || $mime === "image/gif") {
        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $saved = false;
    if ($mime === "image/jpeg" || $mime === "image/jpg") {
        $saved = imagejpeg($dst, $target_path, $jpeg_quality);
    } elseif ($mime === "image/png") {
        $saved = imagepng($dst, $target_path, 6);
    } elseif ($mime === "image/gif") {
        $saved = imagegif($dst, $target_path);
    }

    imagedestroy($src);
    imagedestroy($dst);

    if (!$saved) {
        return "Error uploading your file.";
    }

    return '';
}

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
        return compress_and_save_image($_FILES[$fileToUpload]["tmp_name"], $target_file, 1200, 70);
    }
}
