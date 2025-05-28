 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Status Peminjaman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-3xl w-full bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-4 text-center text-blue-700">Cek Status Peminjaman</h1>
        <form method="POST" class="mb-6" novalidate>
            <div class="mb-3">
                <label for="loan_id" class="form-label fw-bold">Masukkan ID Peminjaman</label>
                <input type="text" id="loan_id" name="loan_id" required class="form-control" placeholder="Contoh: 12345"
                value="<?php echo isset($_POST['loan_id']) ? htmlspecialchars($_POST['loan_id']) : ''; ?>" />
            </div>
            <button type="submit" class="btn btn-primary w-full hover:bg-blue-800 transition-colors">Cek Status</button>
        </form>

<?php
// Configuration - Please change accordingly
$host = "localhost";
$dbname = "sarpras";
$username = "root";
$password = "";

// Function to safely connect to database
function getDbConnection($host, $dbname, $username, $password) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Koneksi database gagal: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['loan_id'])) {
    $loanId = trim($_POST['loan_id']);
    
    $pdo = getDbConnection($host, $dbname, $username, $password);
    
    // Query loan info
    $stmtLoan = $pdo->prepare("SELECT id, borrower_name, loan_date, return_date, status FROM loans WHERE id = ?");
    $stmtLoan->execute([$loanId]);
    $loan = $stmtLoan->fetch();

    if (!$loan) {
        echo '<div class="alert alert-warning">Peminjaman dengan ID tersebut tidak ditemukan.</div>';
    } else {
        echo '<div class="mb-4 p-3 rounded bg-blue-50 border border-blue-300">';
        echo '<h2 class="text-xl font-semibold mb-2">Informasi Peminjaman</h2>';
        echo '<p><strong>ID Peminjaman:</strong> ' . htmlspecialchars($loan['id']) . '</p>';
        echo '<p><strong>Nama Peminjam:</strong> ' . htmlspecialchars($loan['borrower_name']) . '</p>';
        echo '<p><strong>Tanggal Pinjam:</strong> ' . htmlspecialchars($loan['loan_date']) . '</p>';
        echo '<p><strong>Tanggal Kembali:</strong> ' . htmlspecialchars($loan['return_date']) . '</p>';
        echo '<p><strong>Status Peminjaman:</strong> <span class="fw-bold">' . htmlspecialchars($loan['status']) . '</span></p>';
        echo '</div>';

        // Query related items including image
        $stmtItems = $pdo->prepare("SELECT id, name, status, image_url FROM items WHERE loan_id = ?");
        $stmtItems->execute([$loanId]);
        $items = $stmtItems->fetchAll();

        if (count($items) === 0) {
            echo '<div class="alert alert-info">Tidak ada barang yang terkait dengan peminjaman ini.</div>';
        } else {
            echo '<h3 class="mb-3 text-lg font-semibold">Status Barang</h3>';
            echo '<table class="table table-striped table-bordered align-middle">';
            echo '<thead class="table-primary">';
            echo '<tr>';
            echo '<th scope="col">Gambar</th>';
            echo '<th scope="col">ID Barang</th>';
            echo '<th scope="col">Nama Barang</th>';
            echo '<th scope="col">Status Barang</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($items as $item) {
                echo '<tr>';
                echo '<td class="text-center" style="width: 120px; max-width:120px;">';
                if (!empty($item['image_url']) && filter_var($item['image_url'], FILTER_VALIDATE_URL)) {
                    echo '<img src="' . htmlspecialchars($item['image_url']) . '" alt="Gambar Barang" class="rounded" style="max-width:100px; max-height:80px; object-fit:contain;">';
                } else {
                    echo '<span class="text-muted">Tidak ada gambar</span>';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($item['id']) . '</td>';
                echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                echo '<td>' . htmlspecialchars($item['status']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
    }
}
?>

        <footer class="mt-6 text-center text-gray-500 text-sm">
            &copy; <?php echo date('Y'); ?> Sistem Peminjaman Modern
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

   