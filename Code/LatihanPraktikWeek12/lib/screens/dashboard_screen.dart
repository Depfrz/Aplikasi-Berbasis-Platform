import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../models/tugas.dart';
import '../services/hive_service.dart';
import '../services/shared_preferences_service.dart';
import '../widgets/tugas_card.dart';
import 'login_screen.dart';
import 'tugas_form_screen.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard Tugas'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await SharedPreferencesService.logout();
              if (!context.mounted) return;
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(builder: (context) => const LoginScreen()),
              );
            },
          ),
        ],
      ),
      body: ValueListenableBuilder(
        valueListenable: HiveService.box.listenable(),
        builder: (context, Box<Tugas> box, child) {
          final allTugas = box.values.toList();
          final totalTugas = allTugas.length;
          final selesai = allTugas.where((t) => t.isSelesai).length;
          final belumSelesai = totalTugas - selesai;

          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  children: [
                    _StatCard(title: 'Total', value: totalTugas, color: Colors.blue),
                    _StatCard(title: 'Selesai', value: selesai, color: Colors.green),
                    _StatCard(title: 'Belum', value: belumSelesai, color: Colors.orange),
                  ],
                ),
              ),
              Expanded(
                child: allTugas.isEmpty
                    ? const Center(child: Text('Tidak ada tugas'))
                    : ListView.builder(
                        itemCount: allTugas.length,
                        itemBuilder: (context, index) => TugasCard(tugas: allTugas[index]),
                      ),
              ),
            ],
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const TugasFormScreen()),
          );
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}

class _StatCard extends StatelessWidget {
  final String title;
  final int value;
  final Color color;

  const _StatCard({
    required this.title,
    required this.value,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      color: color,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text(
              title,
              style: const TextStyle(color: Colors.white, fontSize: 16),
            ),
            Text(
              '$value',
              style: const TextStyle(
                color: Colors.white,
                fontSize: 32,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
