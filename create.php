<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// hanya admin yg boleh
$is_admin = isset($_SESSION['role']) ? ($_SESSION['role']==='admin') : (isset($_SESSION['username']) && $_SESSION['username']==='admin');
if (!$is_admin) { 
    header("Location: index.php"); 
    exit; 
}

require 'includes/db.php';

define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image = "";

    $allowed_categories = ['Ide','Bertanya-tanya','Random'];
    if (!in_array($category, $allowed_categories)) {
        die("<p style='color:red;'>Kategori tidak valid!</p>");
    }

    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        if ($file['error'] !== 0) {
            die("<p style='color:red;'>Upload gagal! Error code: {$file['error']}</p>");
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            die("<p style='color:red;'>File terlalu besar! Maksimal 5 MB</p>");
        }
        $allowed_types = ['image/jpeg','image/jpg','image/png','image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            die("<p style='color:red;'>File bukan gambar yang valid!</p>");
        }

        $name = time() . "_" . preg_replace("/[^A-Za-z0-9_.-]/", "", $file['name']);
        $tmp  = $file['tmp_name'];

        $target_dir = __DIR__ . '/uploads/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (!is_writable($target_dir)) {
            die("<p style='color:red;'>Folder uploads/ tidak writable. Cek permission!</p>");
        }

        $target_file = $target_dir . $name;
        if (move_uploaded_file($tmp, $target_file)) {
            $image = $name;
        } else {
            die("<p style='color:red;'>Upload gagal! Cek permission folder uploads/ dan php.ini upload_max_filesize.</p>");
        }
    }

    $query = "INSERT INTO posts (title, category, content, image) VALUES ('$title','$category','$content','$image')";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Gagal simpan ke database: " . mysqli_error($conn) . "</p>";
    }
}

include 'includes/header.php';
?>

<!-- Summernote CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<style>
.editor-container {
    width: 100%;
    padding: 20px 5%;
}
.editor-title input {
    font-size: 2.5rem;
    font-weight: bold;
    border: none;
    outline: none;
    width: 100%;
    margin-bottom: 15px;
}
.editor-title input:focus {
    border-bottom: 2px solid #007bff;
}
.image-preview-container {
    margin: 20px 0;
}
.image-preview-container img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 8px;
    display: none;
}
.note-editor.note-frame {
    border-radius: 8px;
}
</style>

<div class="editor-container">
    <form method="post" enctype="multipart/form-data">
        
        <!-- Judul -->
        <div class="editor-title">
            <input type="text" name="title" placeholder="Tambahkan Judul di Sini..." required>
        </div>

        <!-- Upload & Preview Gambar -->
        <div class="mb-3">
            <label class="form-label">Upload Gambar Utama:</label>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
        </div>
        <div class="image-preview-container">
            <img id="preview" alt="Preview Gambar">
        </div>

        <!-- Editor Konten -->
        <div class="mb-3">
            <textarea id="summernote" name="content" placeholder="Tulis konten Anda di sini..."></textarea>
        </div>

        <!-- Pilih Kategori -->
        <div class="mb-3" style="max-width: 300px;">
            <select name="category" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <option value="Ide">Ide</option>
                <option value="Bertanya-tanya">Bertanya-tanya</option>
                <option value="Random">Random</option>
            </select>
        </div>

        <!-- Tombol -->
        <button class="btn btn-primary btn-lg px-5">Publikasikan</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 400,
        placeholder: 'Tulis artikel kamu di sini...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include 'includes/footer.php'; ?>
