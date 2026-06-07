import 'package:hive/hive.dart';

part 'tugas.g.dart';

@HiveType(typeId: 0)
class Tugas extends HiveObject {
  @HiveField(0)
  String namaTugas;

  @HiveField(1)
  String mataKuliah;

  @HiveField(2)
  DateTime deadline;

  @HiveField(3)
  bool isSelesai;

  Tugas({
    required this.namaTugas,
    required this.mataKuliah,
    required this.deadline,
    this.isSelesai = false,
  });
}
