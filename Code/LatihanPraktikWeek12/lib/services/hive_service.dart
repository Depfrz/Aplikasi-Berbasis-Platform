import 'package:hive_flutter/hive_flutter.dart';
import '../models/tugas.dart';

class HiveService {
  static const String _boxName = 'tugas_box';

  static Future<void> init() async {
    await Hive.initFlutter();
    Hive.registerAdapter(TugasAdapter());
    await Hive.openBox<Tugas>(_boxName);
  }

  static Box<Tugas> get box => Hive.box<Tugas>(_boxName);

  static List<Tugas> getAllTugas() {
    return box.values.toList();
  }

  static Future<void> addTugas(Tugas tugas) async {
    await box.add(tugas);
  }

  static Future<void> updateTugas(Tugas tugas) async {
    await tugas.save();
  }

  static Future<void> deleteTugas(Tugas tugas) async {
    await tugas.delete();
  }
}
