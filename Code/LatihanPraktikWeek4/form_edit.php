<?php
require_once 'db.php';

// Get ID from parameter
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?err=" . urlencode("ID Matakuliah tidak valid."));
    exit;
}

try {
    // Get existing data
    $stmt = $pdo->prepare("SELECT * FROM matakuliah WHERE Kode_MK = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        header("Location: index.php?err=" . urlencode("Data Matakuliah tidak ditemukan."));
        exit;
    }
} catch (Exception $e) {
    error_log("Fetch edit data failed: " . $e->getMessage());
    header("Location: index.php?err=" . urlencode("Terjadi kesalahan saat memuat data."));
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Matakuliah - Sistem Informasi Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-dark"><i class="bi bi-pencil-square"></i> Edit Data Matakuliah</h4>
                    <a href="index.php" class="btn btn-dark btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
                <div class="card-body">
                    
                    <form id="formEdit" action="edit.php" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="old_kode_mk" value="<?= e($row['Kode_MK']) ?>">

                        <div class="mb-3">
                            <label for="kode_mk" class="form-label">Kode Matakuliah</label>
                            <input type="text" class="form-control bg-light" id="kode_mk" name="kode_mk" 
                                   value="<?= e($row['Kode_MK']) ?>" required maxlength="10">
                            <div class="invalid-feedback">Kode MK wajib diisi (Maks. 10 karakter).</div>
                            <small class="text-muted">ID Utama (Kode MK) sebaiknya tidak diubah kecuali diperlukan.</small>
                        </div>

                        <div class="mb-3">
                            <label for="nama_mk" class="form-label">Nama Matakuliah</label>
                            <input type="text" class="form-control" id="nama_mk" name="nama_mk" 
                                   value="<?= e($row['Nama_MK']) ?>" required>
                            <div class="invalid-feedback">Nama Matakuliah wajib diisi.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="sks" class="form-label">SKS</label>
                                <input type="number" class="form-control" id="sks" name="sks" 
                                       value="<?= e($row['SKS']) ?>" required min="1" max="6">
                                <div class="invalid-feedback">SKS harus berupa angka (1-6).</div>
                            </div>
                            <div class="col-md-6">
                                <label for="semester" class="form-label">Semester</label>
                                <input type="number" class="form-control" id="semester" name="semester" 
                                       value="<?= e($row['Semester']) ?>" required min="1" max="8">
                                <div class="invalid-feedback">Semester harus berupa angka (1-8).</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg"><i class="bi bi-save"></i> Update Data</button>
                            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Real-time validation
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
    
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.checkValidity()) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        });
    });
  })
})()
</script>
</body>
</html>
