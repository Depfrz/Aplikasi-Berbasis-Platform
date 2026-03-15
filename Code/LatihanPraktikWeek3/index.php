<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjualan Barang Elektronik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            width: 600px;
            border: 1px solid #000;
            background-color: yellow;
        }
        .header {
            background-color: blue;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .form-group {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .form-group label {
            width: 150px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            flex: 1;
            padding: 5px;
            border: 1px solid #ccc;
        }
        .form-group button {
            margin-left: 10px;
            padding: 5px 15px;
            background-color: #ccc;
            border: 1px solid #999;
            cursor: pointer;
            font-weight: bold;
        }
        .form-group button:hover {
            background-color: #bbb;
        }
        .action-buttons {
            margin-top: 20px;
        }
        /* Hide print button when printing */
        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: white;
            }
            .container {
                border: none;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        FORM PENJUALAN BARANG
    </div>
    <div class="content">
        <form id="salesForm" onsubmit="return false;">
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" id="kode_barang" name="kode_barang" placeholder="Contoh: A001">
                <button type="button" onclick="cariBarang()">Cari</button>
            </div>

            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" readonly>
            </div>

            <div class="form-group">
                <label for="harga_barang">Harga Barang</label>
                <input type="text" id="harga_barang" name="harga_barang" readonly>
            </div>

            <div class="form-group">
                <label for="jumlah_barang">Jumlah Barang</label>
                <input type="number" id="jumlah_barang" name="jumlah_barang" min="1">
            </div>

            <div class="form-group">
                <label for="metode_bayar">Metode Bayar</label>
                <select id="metode_bayar" name="metode_bayar">
                    <option value="Cash">Cash</option>
                    <option value="Kredit">Kredit</option>
                </select>
                <button type="button" onclick="prosesHitung()">Proses</button>
            </div>

            <div class="form-group">
                <label for="total_harga">Total Harga</label>
                <input type="text" id="total_harga" name="total_harga" readonly>
            </div>

            <div class="form-group">
                <label for="diskon">Diskon</label>
                <input type="text" id="diskon" name="diskon" readonly>
            </div>

            <div class="form-group">
                <label for="total_bayar">Total Bayar</label>
                <input type="text" id="total_bayar" name="total_bayar" readonly>
            </div>

            <div class="form-group action-buttons">
                <label></label> <!-- Spacer -->
                <button type="button" onclick="cetakStruk()" class="no-print">Cetak</button>
            </div>
        </form>
    </div>
</div>

<script>
    function cariBarang() {
        const kode = document.getElementById('kode_barang').value;
        if (!kode) {
            alert('Masukkan kode barang!');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'search');
        formData.append('code', kode);

        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('nama_barang').value = data.data.name;
                // Store raw price in a data attribute for calculation if needed, but we calculate server-side
                document.getElementById('harga_barang').value = data.data.formatted_price;
            } else {
                alert(data.message);
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga_barang').value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari barang.');
        });
    }

    function prosesHitung() {
        const kode = document.getElementById('kode_barang').value;
        const jumlah = document.getElementById('jumlah_barang').value;
        const metode = document.getElementById('metode_bayar').value;

        if (!kode || !jumlah) {
            alert('Lengkapi data barang dan jumlah!');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'calculate');
        formData.append('code', kode);
        formData.append('qty', jumlah);
        formData.append('method', metode);

        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('total_harga').value = data.data.formatted_total_price;
                document.getElementById('diskon').value = data.data.formatted_discount;
                document.getElementById('total_bayar').value = data.data.formatted_total_pay;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung.');
        });
    }

    function cetakStruk() {
        // Simple window print
        window.print();
    }
</script>

</body>
</html>
