<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "responsi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM lokasi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='homepage.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus data.'); window.location='homepage.php';</script>";
    }
} else {
    echo "<script>alert('ID yang ingin dihapus tidak ditemukan.'); window.location='homepage.php';</script>";
}

// Tutup koneksi
$conn->close();
?>
