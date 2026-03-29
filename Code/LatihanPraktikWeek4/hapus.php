<?php
require_once 'db.php';

// Get ID from parameter
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?err=" . urlencode("ID Matakuliah tidak valid."));
    exit;
}

try {
    // 1. Cek keberadaan data
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM matakuliah WHERE Kode_MK = ?");
    $check_stmt->execute([$id]);
    if ($check_stmt->fetchColumn() == 0) {
        header("Location: index.php?err=" . urlencode("Data Matakuliah tidak ditemukan."));
        exit;
    }

    // 2. Simulasi pengecekan tabel relasi (misal: tabel KRS atau Nilai)
    // Jika ada tabel relasi, Anda harus cek di sini sebelum menghapus.
    // Contoh:
    /*
    $relasi_stmt = $pdo->prepare("SELECT COUNT(*) FROM krs WHERE Kode_MK = ?");
    $relasi_stmt->execute([$id]);
    if ($relasi_stmt->fetchColumn() > 0) {
        header("Location: index.php?err=" . urlencode("Data tidak dapat dihapus karena masih digunakan di tabel KRS."));
        exit;
    }
    */

    // Start transaction
    $pdo->beginTransaction();

    // 3. Proses hapus
    $stmt = $pdo->prepare("DELETE FROM matakuliah WHERE Kode_MK = ?");
    $stmt->execute([$id]);

    // Commit transaction
    $pdo->commit();

    header("Location: index.php?msg=" . urlencode("Data Matakuliah dengan Kode MK '$id' berhasil dihapus."));
    exit;

} catch (Exception $e) {
    // Rollback if transaction failed
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Error logging
    error_log("Delete failed for ID $id: " . $e->getMessage());
    header("Location: index.php?err=" . urlencode("Gagal menghapus data. Terjadi kesalahan sistem."));
    exit;
}
?>
