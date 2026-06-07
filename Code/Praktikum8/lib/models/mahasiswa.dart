class Mahasiswa {
  final int? id;
  final String nama;
  final String nim;
  final String jurusan;
  final String prodi;
  final String? createdAt;
  final String? updatedAt;

  Mahasiswa({
    this.id,
    required this.nama,
    required this.nim,
    required this.jurusan,
    required this.prodi,
    this.createdAt,
    this.updatedAt,
  });

  factory Mahasiswa.fromJson(Map<String, dynamic> json) {
    return Mahasiswa(
      id: json['id'],
      nama: json['nama'],
      nim: json['nim'],
      jurusan: json['jurusan'],
      prodi: json['prodi'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'nama': nama,
      'nim': nim,
      'jurusan': jurusan,
      'prodi': prodi,
    };
  }
}
