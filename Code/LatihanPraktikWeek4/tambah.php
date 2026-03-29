<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $kode_mk = trim($_POST['kode_mk'] ?? '');
    $nama_mk = trim($_POST['nama_mk'] ?? '');
    $sks = (int)($_POST['sks'] ?? 0);
    $semester = (int)($_POST['semester'] ?? 0);

    // Server-side validation
    if (empty($kode_mk) || empty($nama_mk) || $sks <= 0 || $semester <= 0) {
        header("Location: index.php?err=" . urlencode("Semua field wajib diisi dengan benar."));
        exit;
    }

    try {
        // Cek duplikasi
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM matakuliah WHERE Kode_MK = ?");
        $check_stmt->execute([$kode_mk]);
        if ($check_stmt->fetchColumn() > 0) {
            header("Location: index.php?err=" . urlencode("Gagal! Kode MK '$kode_mk' sudah ada dalam database."));
            exit;
        }

        // Start transaction
        $pdo->beginTransaction();

        // Prepared statement for insert
        $stmt = $pdo->prepare("INSERT INTO matakuliah (Kode_MK, Nama_MK, SKS, Semester) VALUES (?, ?, ?, ?)");
        $stmt->execute([$kode_mk, $nama_mk, $sks, $semester]);

        // Commit transaction
        $pdo->commit();

        header("Location: index.php?msg=" . urlencode("Data Matakuliah '$nama_mk' berhasil ditambahkan."));
        exit;

    } catch (Exception $e) {
        // Rollback if transaction failed
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // Error logging
        error_log("Insert failed: " . $e->getMessage());
        header("Location: index.php?err=" . urlencode("Terjadi kesalahan sistem saat menyimpan data."));
        exit;
    }
} else {
    // Direct access to script not allowed
    header("Location: index.php");
    exit;
}
?>
