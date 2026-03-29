<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $old_kode_mk = trim($_POST['old_kode_mk'] ?? '');
    $new_kode_mk = trim($_POST['kode_mk'] ?? '');
    $nama_mk = trim($_POST['nama_mk'] ?? '');
    $sks = (int)($_POST['sks'] ?? 0);
    $semester = (int)($_POST['semester'] ?? 0);

    // Server-side validation
    if (empty($old_kode_mk) || empty($new_kode_mk) || empty($nama_mk) || $sks <= 0 || $semester <= 0) {
        header("Location: index.php?err=" . urlencode("Semua field wajib diisi dengan benar."));
        exit;
    }

    try {
        // Cek jika ID berubah, pastikan ID baru tidak duplikat dengan record lain
        if ($old_kode_mk !== $new_kode_mk) {
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM matakuliah WHERE Kode_MK = ?");
            $check_stmt->execute([$new_kode_mk]);
            if ($check_stmt->fetchColumn() > 0) {
                header("Location: index.php?err=" . urlencode("Gagal! Kode MK '$new_kode_mk' sudah digunakan oleh record lain."));
                exit;
            }
        }

        // Start transaction
        $pdo->beginTransaction();

        // Prepared statement for update
        $stmt = $pdo->prepare("UPDATE matakuliah SET Kode_MK = ?, Nama_MK = ?, SKS = ?, Semester = ? WHERE Kode_MK = ?");
        $stmt->execute([$new_kode_mk, $nama_mk, $sks, $semester, $old_kode_mk]);

        // Commit transaction
        $pdo->commit();

        header("Location: index.php?msg=" . urlencode("Data Matakuliah '$nama_mk' berhasil diperbarui."));
        exit;

    } catch (Exception $e) {
        // Rollback if transaction failed
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // Error logging
        error_log("Update failed: " . $e->getMessage());
        header("Location: index.php?err=" . urlencode("Terjadi kesalahan sistem saat memperbarui data."));
        exit;
    }
} else {
    // Direct access to script not allowed
    header("Location: index.php");
    exit;
}
?>
