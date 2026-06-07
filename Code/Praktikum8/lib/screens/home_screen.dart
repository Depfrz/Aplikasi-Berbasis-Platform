import 'dart:async';
import 'package:flutter/material.dart';
import '../models/mahasiswa.dart';
import '../services/api_service.dart';
import 'login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  List<Mahasiswa> _mahasiswaList = [];
  List<Mahasiswa> _filteredList = [];
  bool _isLoading = true;
  bool _isError = false;
  String _errorMessage = '';
  final _searchController = TextEditingController();
  Timer? _debounce;

  @override
  void initState() {
    super.initState();
    _fetchData();
    _searchController.addListener(_onSearchChanged);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _debounce?.cancel();
    super.dispose();
  }

  void _onSearchChanged() {
    if (_debounce?.isActive ?? false) _debounce!.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () {
      _searchMahasiswa(_searchController.text);
    });
  }

  Future<void> _searchMahasiswa(String keyword) async {
    if (keyword.isEmpty) {
      setState(() {
        _filteredList = List.from(_mahasiswaList);
      });
      return;
    }
    try {
      final results = await ApiService.searchMahasiswa(keyword);
      setState(() {
        _filteredList = results;
      });
    } catch (e) {
      // If search fails, fallback to local filtering
      setState(() {
        _filteredList = _mahasiswaList
            .where((m) => m.nama.toLowerCase().contains(keyword.toLowerCase()))
            .toList();
      });
    }
  }

  Future<void> _fetchData() async {
    setState(() {
      _isLoading = true;
      _isError = false;
    });
    try {
      final data = await ApiService.getMahasiswa();
      setState(() {
        _mahasiswaList = data;
        _filteredList = data;
        _isLoading = false;
      });
    } catch (e) {
      if (e.toString().contains('Unauthorized')) {
        if (mounted) {
          await ApiService.clearToken();
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (context) => const LoginScreen()),
          );
        }
        return;
      }
      setState(() {
        _isError = true;
        _errorMessage = 'Gagal terhubung ke server, periksa koneksi Anda';
        _isLoading = false;
      });
    }
  }

  Future<void> _showForm({Mahasiswa? mahasiswa}) async {
    final namaController = TextEditingController(text: mahasiswa?.nama);
    final nimController = TextEditingController(text: mahasiswa?.nim);
    final jurusanController = TextEditingController(text: mahasiswa?.jurusan);
    final prodiController = TextEditingController(text: mahasiswa?.prodi);
    final formKey = GlobalKey<FormState>();

    return showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) => Padding(
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
          top: 16,
          left: 16,
          right: 16,
        ),
        child: Form(
          key: formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                mahasiswa == null ? 'Tambah Mahasiswa' : 'Edit Mahasiswa',
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: namaController,
                decoration: const InputDecoration(
                  labelText: 'Nama',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Masukkan nama';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: nimController,
                decoration: const InputDecoration(
                  labelText: 'NIM',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Masukkan NIM';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: jurusanController,
                decoration: const InputDecoration(
                  labelText: 'Jurusan',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Masukkan jurusan';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: prodiController,
                decoration: const InputDecoration(
                  labelText: 'Prodi',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Masukkan prodi';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: () async {
                    if (formKey.currentState!.validate()) {
                      final mhs = Mahasiswa(
                        id: mahasiswa?.id,
                        nama: namaController.text,
                        nim: nimController.text,
                        jurusan: jurusanController.text,
                        prodi: prodiController.text,
                      );
                      try {
                        if (mahasiswa == null) {
                          await ApiService.createMahasiswa(mhs);
                        } else {
                          await ApiService.updateMahasiswa(mhs);
                        }
                        if (mounted) {
                          Navigator.pop(context);
                          _fetchData();
                        }
                      } catch (e) {
                        if (mounted) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(content: Text('Gagal: $e')),
                          );
                        }
                      }
                    }
                  },
                  child: Text(mahasiswa == null ? 'Simpan' : 'Update'),
                ),
              ),
              const SizedBox(height: 16),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _confirmDelete(int id) async {
    return showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Konfirmasi Hapus'),
        content: const Text(
          'Apakah Anda yakin ingin menghapus data ini? Tindakan tidak dapat dibatalkan.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () async {
              try {
                await ApiService.deleteMahasiswa(id);
                if (mounted) {
                  Navigator.pop(context);
                  _fetchData();
                }
              } catch (e) {
                if (mounted) {
                  Navigator.pop(context);
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Gagal: $e')),
                  );
                }
              }
            },
            child: const Text('Hapus', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  Future<void> _logout() async {
    await ApiService.clearToken();
    if (mounted) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (context) => const LoginScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Daftar Mahasiswa'),
        automaticallyImplyLeading: false,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _logout,
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showForm(),
        child: const Icon(Icons.add),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: TextField(
              controller: _searchController,
              decoration: const InputDecoration(
                hintText: 'Cari mahasiswa...',
                prefixIcon: Icon(Icons.search),
                border: OutlineInputBorder(),
              ),
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _isError
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(
                              Icons.wifi_off,
                              size: 80,
                              color: Colors.grey,
                            ),
                            const SizedBox(height: 16),
                            Text(
                              _errorMessage,
                              style: const TextStyle(fontSize: 16),
                              textAlign: TextAlign.center,
                            ),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: _fetchData,
                              child: const Text('Coba Lagi'),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _fetchData,
                        child: _filteredList.isEmpty
                            ? const Center(
                                child: Text('Tidak ada data'),
                              )
                            : ListView.builder(
                                itemCount: _filteredList.length,
                                itemBuilder: (context, index) {
                                  final mhs = _filteredList[index];
                                  return ListTile(
                                    title: Text(mhs.nama),
                                    subtitle: Text('NIM: ${mhs.nim}'),
                                    trailing: Row(
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        IconButton(
                                          icon: const Icon(Icons.edit),
                                          onPressed: () =>
                                              _showForm(mahasiswa: mhs),
                                        ),
                                        IconButton(
                                          icon: const Icon(
                                            Icons.delete,
                                            color: Colors.red,
                                          ),
                                          onPressed: () =>
                                              _confirmDelete(mhs.id!),
                                        ),
                                      ],
                                    ),
                                  );
                                },
                              ),
                      ),
          ),
        ],
      ),
    );
  }
}
