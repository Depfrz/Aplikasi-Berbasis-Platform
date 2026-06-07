import 'package:flutter/material.dart';
import '../models/tugas.dart';
import '../services/hive_service.dart';
import '../screens/tugas_form_screen.dart';

class TugasCard extends StatelessWidget {
  final Tugas tugas;

  const TugasCard({super.key, required this.tugas});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ListTile(
        leading: Checkbox(
          value: tugas.isSelesai,
          onChanged: (value) {
            tugas.isSelesai = value!;
            HiveService.updateTugas(tugas);
          },
        ),
        title: Text(
          tugas.namaTugas,
          style: TextStyle(
            decoration: tugas.isSelesai ? TextDecoration.lineThrough : null,
          ),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(tugas.mataKuliah),
            Text(
              '${tugas.deadline.day}/${tugas.deadline.month}/${tugas.deadline.year} ${tugas.deadline.hour}:${tugas.deadline.minute.toString().padLeft(2, '0')}',
            ),
          ],
        ),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            IconButton(
              icon: const Icon(Icons.edit),
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => TugasFormScreen(tugas: tugas),
                  ),
                );
              },
            ),
            IconButton(
              icon: const Icon(Icons.delete, color: Colors.red),
              onPressed: () async {
                final confirm = await showDialog<bool>(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: const Text('Konfirmasi'),
                    content: const Text('Yakin ingin menghapus tugas ini?'),
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
                  await HiveService.deleteTugas(tugas);
                }
              },
            ),
          ],
        ),
      ),
    );
  }
}
