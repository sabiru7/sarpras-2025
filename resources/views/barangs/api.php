<?php
header('Content-Type: application/json');

$host = 'localhost'; // Your database host
$db = 'sarpras'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM items");
        if (!$result) {
            die(json_encode(['error' => 'Query error: ' . $conn->error]));
        }
        $items = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($items);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nama = $conn->real_escape_string($data['nama']);
        $jumlah = (int)$data['jumlah'];
        $foto = $conn->real_escape_string($data['foto'] ?? '');

        $stmt = $conn->prepare("INSERT INTO items (nama, jumlah, foto) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $nama, $jumlah, $foto);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Insert error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int)$data['id'];
        $nama = $conn->real_escape_string($data['nama']);
        $jumlah = (int)$data['jumlah'];
        $foto = $conn->real_escape_string($data['foto'] ?? '');

        $stmt = $conn->prepare("UPDATE items SET nama=?, jumlah=?, foto=? WHERE id=?");
        $stmt->bind_param("sisi", $nama, $jumlah, $foto, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Update error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int)$data['id'];

        $stmt = $conn->prepare("DELETE FROM items WHERE id=?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Delete error: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

$conn->close();
?>
