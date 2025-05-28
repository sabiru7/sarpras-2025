<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Barang dengan Foto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #eef2f7;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .floating-card {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      width: 460px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <div class="floating-card">
    <h3 class="mb-4 text-center text-primary">Manajemen Stok Barang dengan Foto</h3>
    <form id="barang-form" class="mb-4">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Barang</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama barang" required />
      </div>
      <div class="mb-3">
        <label for="jumlah" class="form-label">Stok (Jumlah)</label>
        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" placeholder="Jumlah stok" required />
      </div>
      <div class="mb-3">
        <label for="foto" class="form-label">Foto Barang (opsional)</label>
        <input class="form-control" type="file" id="foto" name="foto" accept="image/*" />
      </div>
      <button type="submit" class="btn btn-primary w-100">Tambah Barang</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
