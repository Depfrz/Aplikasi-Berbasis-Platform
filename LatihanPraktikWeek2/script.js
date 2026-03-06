document.addEventListener('DOMContentLoaded', () => {
    const dataBarang = {
        'A001': { nama: 'Televisi Samsung 50"', harga: 5000000 },
        'A002': { nama: 'VCD Player Sony', harga: 750000 },
        'A003': { nama: 'Mini Compo', harga: 2300000 }
    };

    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
    };

    const cariBarang = () => {
        const kode = document.getElementById('kode-barang').value.toUpperCase();
        const errorMessage = document.getElementById('error-message');
        if (dataBarang[kode]) {
            document.getElementById('nama-barang').value = dataBarang[kode].nama;
            document.getElementById('harga-barang').value = formatRupiah(dataBarang[kode].harga);
            errorMessage.textContent = '';
        } else {
            errorMessage.textContent = 'Kode barang tidak valid.';
            document.getElementById('nama-barang').value = '';
            document.getElementById('harga-barang').value = '';
        }
    };

    const hitungTotal = () => {
        const kode = document.getElementById('kode-barang').value.toUpperCase();
        const jumlah = parseInt(document.getElementById('jumlah-barang').value);
        const errorMessage = document.getElementById('error-message');

        if (!dataBarang[kode]) {
            errorMessage.textContent = 'Silakan cari barang terlebih dahulu.';
            return;
        }

        if (isNaN(jumlah) || jumlah <= 0) {
            errorMessage.textContent = 'Jumlah barang harus berupa angka positif.';
            return;
        }

        const harga = dataBarang[kode].harga;
        const totalHarga = harga * jumlah;
        let diskon = 0;
        if (jumlah > 10) {
            diskon = 0.12 * totalHarga;
        }
        const totalBayar = totalHarga - diskon;

        document.getElementById('total-harga').textContent = formatRupiah(totalHarga);
        document.getElementById('diskon').textContent = `${diskon > 0 ? '12% (' + formatRupiah(diskon) + ')' : '0%'}`;
        document.getElementById('total-bayar').textContent = formatRupiah(totalBayar);
        errorMessage.textContent = '';
    };

    const cetakStruk = () => {
        window.print();
    };

    document.getElementById('cari-btn').addEventListener('click', cariBarang);
    document.getElementById('hitung-btn').addEventListener('click', hitungTotal);
    document.getElementById('cetak-btn').addEventListener('click', cetakStruk);
});