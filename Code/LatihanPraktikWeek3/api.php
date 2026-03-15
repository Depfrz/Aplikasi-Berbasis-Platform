<?php
header('Content-Type: application/json');

// Data Barang
$items = [
    'A001' => ['name' => 'Mouse', 'price' => 5000000],
    'A002' => ['name' => 'Headphone', 'price' => 750000],
    'A003' => ['name' => 'CPU', 'price' => 2300000]
];

// Handle Request
$action = $_POST['action'] ?? '';

if ($action === 'search') {
    $code = $_POST['code'] ?? '';
    
    if (isset($items[$code])) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'name' => $items[$code]['name'],
                'price' => $items[$code]['price'],
                'formatted_price' => 'Rp ' . number_format($items[$code]['price'], 0, ',', '.')
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Barang tidak ditemukan']);
    }
    exit;
}

if ($action === 'calculate') {
    $code = $_POST['code'] ?? '';
    $qty = (int)($_POST['qty'] ?? 0);
    $method = $_POST['method'] ?? '';

    if (!isset($items[$code])) {
        echo json_encode(['status' => 'error', 'message' => 'Kode barang tidak valid']);
        exit;
    }

    if ($qty <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Jumlah barang harus positif']);
        exit;
    }

    $price = $items[$code]['price'];
    $total_price = $price * $qty;
    $discount = 0;

    // Diskon Logic
    // 1. Jika metode bayar = “Cash” atau jumlah barang > 5 → diskon 12% × harga barang
    // 2. Jika metode bayar = “Kredit” dan jumlah barang > 20 → diskon 5% × total harga
    // 3. Selain kondisi di atas → diskon = 0

    if ($method === 'Cash' || $qty > 5) {
        // Diskon 12% dari HARGA BARANG (satuan)
        $discount = 0.12 * $price;
    }
    
    // Aturan kedua: Jika Kredit dan > 20, timpa aturan pertama jika memenuhi
    if ($method === 'Kredit' && $qty > 20) {
        // Diskon 5% dari TOTAL HARGA
        $discount = 0.05 * $total_price;
    }

    $total_pay = $total_price - $discount;

    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_price' => $total_price,
            'discount' => $discount,
            'total_pay' => $total_pay,
            'formatted_total_price' => 'Rp ' . number_format($total_price, 0, ',', '.'),
            'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'formatted_total_pay' => 'Rp ' . number_format($total_pay, 0, ',', '.')
        ]
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
