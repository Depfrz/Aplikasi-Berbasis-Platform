<?php
require_once 'db.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search setup
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = "";
$params = [];

if ($search) {
    $where = "WHERE Kode_MK LIKE :search OR Nama_MK LIKE :search";
    $params['search'] = "%$search%";
}

// Get total records for pagination
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM matakuliah $where");
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Get records with limit
$stmt = $pdo->prepare("SELECT * FROM matakuliah $where LIMIT $start, $limit");
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Kampus - Matakuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Matakuliah</h4>
            <a href="form_tambah.php" class="btn btn-light btn-sm"><i class="bi bi-plus-circle"></i> Tambah Data</a>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= e($_GET['msg']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['err'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= e($_GET['err']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="index.php" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Kode atau Nama Matakuliah..." value="<?= e($search) ?>">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                    <?php if ($search): ?>
                        <a href="index.php" class="btn btn-outline-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped border">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Matakuliah</th>
                            <th>SKS</th>
                            <th>Semester</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= e($row['Kode_MK']) ?></td>
                                    <td><?= e($row['Nama_MK']) ?></td>
                                    <td><?= e($row['SKS']) ?></td>
                                    <td><?= e($row['Semester']) ?></td>
                                    <td class="text-center">
                                        <a href="form_edit.php?id=<?= e($row['Kode_MK']) ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete('<?= e($row['Kode_MK']) ?>')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?page=<?= $page - 1 ?>&search=<?= e($search) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="index.php?page=<?= $i ?>&search=<?= e($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?page=<?= $page + 1 ?>&search=<?= e($search) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data dengan Kode MK: ' + id + '?')) {
        window.location.href = 'hapus.php?id=' + id;
    }
}
</script>
</body>
</html>
