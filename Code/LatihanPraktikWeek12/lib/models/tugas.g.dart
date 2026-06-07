// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'tugas.dart';

// **************************************************************************
// TypeAdapterGenerator
// **************************************************************************

class TugasAdapter extends TypeAdapter<Tugas> {
  @override
  final int typeId = 0;

  @override
  Tugas read(BinaryReader reader) {
    final numOfFields = reader.readByte();
    final fields = <int, dynamic>{
      for (int i = 0; i < numOfFields; i++) reader.readByte(): reader.read(),
    };
    return Tugas(
      namaTugas: fields[0] as String,
      mataKuliah: fields[1] as String,
      deadline: fields[2] as DateTime,
      isSelesai: fields[3] as bool,
    );
  }

  @override
  void write(BinaryWriter writer, Tugas obj) {
    writer
      ..writeByte(4)
      ..writeByte(0)
      ..write(obj.namaTugas)
      ..writeByte(1)
      ..write(obj.mataKuliah)
      ..writeByte(2)
      ..write(obj.deadline)
      ..writeByte(3)
      ..write(obj.isSelesai);
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is TugasAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}
