
<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsi";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
$id = $_GET['id'];
$sql = "SELECT * FROM lokasi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ada!";
    exit;
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kecamatan = $_POST["kecamatan"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $nama_faskes = $_POST["nama_faskes"];

    $sql = "UPDATE lokasi SET kecamatan = ?, longitude = ?, latitude = ?, nama_faskes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $kecamatan, $longitude, $latitude, $nama_faskes, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='homepage.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Fasilitas Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3f9ed;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            margin-top: 60px;
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            border: 3px solid #b6ffb1;
        }
        h3 {
            color: #33d674;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            color: #33d674;
            font-weight: 500;
        }
        .form-control {
            border: 2px solid #b6ffb1;
            border-radius: 10px;
        }
        .form-control:focus {
            border-color: #33d674;
            box-shadow: 0 0 5px rgba(51, 214, 116, 0.3);
        }
        .btn-custom {
            background-color: #33d674;
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #29c165;
        }
        .btn-secondary {
            border-radius: 10px;
            padding: 10px 20px;
        }
        .text-muted {
            font-size: 0.9em;
            text-align: center;
            margin-top: 20px;
        }
    </style>

</head>
<body>
    <div class="container">
        <h3>Edit Data Fasilitas Kesehatan</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="kecamatan" class="form-label">Nama Kecamatan</label>
                <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($data['kecamatan']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" value="<?= htmlspecialchars($data['longitude']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" value="<?= htmlspecialchars($data['latitude']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_faskes" class="form-label">Nama Fasilitas Kesehatan</label>
                <textarea class="form-control" id="nama_faskes" name="nama_faskes" rows="3" required><?= htmlspecialchars($data['nama_faskes']) ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
                <a href="homepage.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
        <p class="text-muted">Pastikan semua informasi yang dimasukkan sudah benar sebelum menyimpan perubahan.</p>
    </div>
</body>
</html>
