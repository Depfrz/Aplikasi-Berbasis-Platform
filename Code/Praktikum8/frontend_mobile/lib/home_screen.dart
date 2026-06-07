import 'package:flutter/material.dart';
import 'api_service.dart';
import 'login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _apiService = ApiService();
  List<dynamic> _mahasiswa = [];
  List<dynamic> _filteredMahasiswa = [];
  bool _isLoading = true;
  String _errorMessage = '';
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _loadMahasiswa();
    _searchController.addListener(_filterMahasiswa);
  }

  Future<void> _loadMahasiswa() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final data = await _apiService.getMahasiswa();
      setState(() {
        _mahasiswa = data;
        _filteredMahasiswa = data;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  void _filterMahasiswa() {
    final query = _searchController.text.toLowerCase();
    setState(() {
      _filteredMahasiswa = _mahasiswa.where((item) {
        final nama = item['nama']?.toString().toLowerCase() ?? '';
        final nim = item['nim']?.toString().toLowerCase() ?? '';
        return nama.contains(query) || nim.contains(query);
      }).toList();
    });
  }

  Future<void> _showFormDialog({Map<String, dynamic>? existingData}) async {
    final namaController = TextEditingController(text: existingData?['nama']);
    final nimController = TextEditingController(text: existingData?['nim']);
    final emailController = TextEditingController(text: existingData?['email']);
    final prodiController = TextEditingController(text: existingData?['prodi']);
    final ipkController =
        TextEditingController(text: existingData?['ipk']?.toString());
    final sksController =
        TextEditingController(text: existingData?['sks']?.toString());

    if (!mounted) return;
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title:
            Text(existingData == null ? 'Tambah Mahasiswa' : 'Edit Mahasiswa'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: namaController,
                decoration: const InputDecoration(labelText: 'Nama'),
              ),
              TextField(
                controller: nimController,
                decoration: const InputDecoration(labelText: 'NIM'),
              ),
              TextField(
                controller: emailController,
                decoration: const InputDecoration(labelText: 'Email'),
                keyboardType: TextInputType.emailAddress,
              ),
              TextField(
                controller: prodiController,
                decoration: const InputDecoration(labelText: 'Prodi'),
              ),
              TextField(
                controller: ipkController,
                decoration: const InputDecoration(labelText: 'IPK'),
                keyboardType:
                    const TextInputType.numberWithOptions(decimal: true),
              ),
              TextField(
                controller: sksController,
                decoration: const InputDecoration(labelText: 'SKS'),
                keyboardType: TextInputType.number,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () async {
              final data = {
                'nama': namaController.text,
                'nim': nimController.text,
                'email': emailController.text,
                'prodi': prodiController.text,
                'ipk': double.tryParse(ipkController.text) ?? 0.0,
                'sks': int.tryParse(sksController.text) ?? 0,
              };

              Map<String, dynamic> result;
              if (existingData == null) {
                result = await _apiService.createMahasiswa(data);
              } else {
                result =
                    await _apiService.updateMahasiswa(existingData['id'], data);
              }

              if (!mounted) return;
              Navigator.pop(context);

              if (result['success']) {
                _loadMahasiswa();
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                      content: Text(existingData == null
                          ? 'Data berhasil ditambahkan'
                          : 'Data berhasil diupdate')),
                );
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                      content:
                          Text(result['message'] ?? 'Gagal menyimpan data')),
                );
              }
            },
            child: Text(existingData == null ? 'Tambah' : 'Update'),
          ),
        ],
      ),
    );
  }

  Future<void> _deleteMahasiswa(int id) async {
    if (!mounted) return;
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Konfirmasi Hapus'),
        content: const Text('Apakah Anda yakin ingin menghapus data ini?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true) {
      final result = await _apiService.deleteMahasiswa(id);
      if (!mounted) return;
      if (result['success']) {
        _loadMahasiswa();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Data berhasil dihapus')),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'])),
        );
      }
    }
  }

  Future<void> _logout() async {
    await _apiService.logout();
    if (!mounted) return;
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (context) => const LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Data Mahasiswa'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _logout,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: TextField(
              controller: _searchController,
              decoration: const InputDecoration(
                labelText: 'Cari Mahasiswa',
                prefixIcon: Icon(Icons.search),
                border: OutlineInputBorder(),
              ),
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _errorMessage.isNotEmpty
                    ? Center(child: Text(_errorMessage))
                    : RefreshIndicator(
                        onRefresh: _loadMahasiswa,
                        child: ListView.builder(
                          itemCount: _filteredMahasiswa.length,
                          itemBuilder: (context, index) {
                            final item = _filteredMahasiswa[index];
                            return ListTile(
                              title:
                                  Text(item['nama'] ?? 'Nama tidak tersedia'),
                              subtitle: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text('NIM: ${item['nim'] ?? ''}'),
                                  Text('Email: ${item['email'] ?? ''}'),
                                  Text('Prodi: ${item['prodi'] ?? ''}'),
                                  Text(
                                      'IPK: ${item['ipk'] ?? ''} | SKS: ${item['sks'] ?? ''}'),
                                ],
                              ),
                              trailing: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  IconButton(
                                    icon: const Icon(Icons.edit),
                                    onPressed: () =>
                                        _showFormDialog(existingData: item),
                                  ),
                                  IconButton(
                                    icon: const Icon(Icons.delete,
                                        color: Colors.red),
                                    onPressed: () =>
                                        _deleteMahasiswa(item['id']),
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
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showFormDialog(),
        child: const Icon(Icons.add),
      ),
    );
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }
}
