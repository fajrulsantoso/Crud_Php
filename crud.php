<?php
session_start();

// Mengatur koneksi ke database
$serverName = "LAPTOP-5JBNMES8"; // Ganti dengan nama server Anda
$connectionInfo = ["Database" => "mahasiswa", "UID" => "", "PWD" => ""];
$conn = sqlsrv_connect($serverName, $connectionInfo);

// Memeriksa koneksi ke database
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Memproses form jika disubmit
$row = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';

    // Menangani aksi tambah, hapus, edit, dan update
    if (isset($_POST['submit']) && $nim && $nama && $alamat && $jurusan) {
        sqlsrv_query($conn, "INSERT INTO mahasiswa (nim, nama, alamat, jurusan) VALUES (?, ?, ?, ?)", [$nim, $nama, $alamat, $jurusan]);
    } elseif (isset($_POST['delete'])) {
        sqlsrv_query($conn, "DELETE FROM mahasiswa WHERE nim = ?", [$nim]);
    } elseif (isset($_POST['edit'])) {
        $stmt = sqlsrv_query($conn, "SELECT * FROM mahasiswa WHERE nim = ?", [$nim]);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    } elseif (isset($_POST['update'])) {
        sqlsrv_query($conn, "UPDATE mahasiswa SET nama = ?, alamat = ?, jurusan = ? WHERE nim = ?", [$nama, $alamat, $jurusan, $nim]);
    }

    // Redirect setelah pemrosesan form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Mengambil semua data mahasiswa
$stmt = sqlsrv_query($conn, "SELECT * FROM mahasiswa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Menyertakan file CSS -->
</head>
<body>
<div class="container">
    <h2 class="text-center">Data Mahasiswa</h2>

    <form method="post" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="nim" class="form-control" placeholder="NIM" value="<?= htmlspecialchars($row['nim'] ?? '') ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="<?= htmlspecialchars($row['alamat'] ?? '') ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="jurusan" class="form-control" placeholder="Jurusan" value="<?= htmlspecialchars($row['jurusan'] ?? '') ?>" required>
            </div>
        </div>
        <button type="submit" name="<?= isset($row) ? 'update' : 'submit' ?>" class="btn btn-primary"><?= isset($row) ? 'Update' : 'Tambah' ?></button>
    </form>

    <h3 class="text-center">Daftar Mahasiswa</h3>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($data['nim']) ?></td>
                <td><?= htmlspecialchars($data['nama']) ?></td>
                <td><?= htmlspecialchars($data['alamat']) ?></td>
                <td><?= htmlspecialchars($data['jurusan']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="nim" value="<?= htmlspecialchars($data['nim']) ?>">
                        <button type="submit" name="delete" class="btn btn-danger">Hapus</button>
                        <button type="submit" name="edit" class="btn btn-info">update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</div>
</body>
</html>
