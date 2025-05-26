<?php
// pinjam.php

// Database connection settings
$host = 'localhost'; // Your database host
$db = 'sarpras'; // Your database name
$user = 'root'; // Your database username
$password = ''; // Your database password

// Create a connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the item name from the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $namaBarang = isset($_POST['nama']) ? trim($_POST['nama']) : '';

    if (empty($namaBarang)) {
        echo json_encode(['message' => 'Nama barang tidak boleh kosong']);
        exit;
    }

    // Check if the item is available
    $checkSql = "SELECT stok FROM barang WHERE nama = ?";
    $stmt = $conn->prepare($checkSql);
    
    if ($stmt) {
        $stmt->bind_param("s", $namaBarang);
        $stmt->execute();
        $stmt->bind_result($stok);
        $stmt->fetch();
        $stmt->close();

        if ($stok > 0) {
            // Update the stock in the database
            $updateSql = "UPDATE barang SET stok = stok - 1 WHERE nama = ?";
            $stmt = $conn->prepare($updateSql);
            
            if ($stmt) {
                $stmt->bind_param("s", $namaBarang);
                
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Barang berhasil dipinjam']);
                } else {
                    echo json_encode(['message' => 'Terjadi kesalahan saat meminjam barang']);
                }
                
                $stmt->close();
            } else {
                echo json_encode(['message' => 'Terjadi kesalahan saat mempersiapkan query']);
            }
        } else {
            echo json_encode(['message' => 'Barang tidak tersedia']);
        }
    } else {
        echo json_encode(['message' => 'Terjadi kesalahan saat mempersiapkan query']);
    }
}

$conn->close();
?>
