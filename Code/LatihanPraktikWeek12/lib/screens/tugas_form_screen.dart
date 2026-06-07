import 'package:flutter/material.dart';
import '../models/tugas.dart';
import '../services/hive_service.dart';

class TugasFormScreen extends StatefulWidget {
  final Tugas? tugas;

  const TugasFormScreen({super.key, this.tugas});

  @override
  State<TugasFormScreen> createState() => _TugasFormScreenState();
}

class _TugasFormScreenState extends State<TugasFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _namaTugasController = TextEditingController();
  final _mataKuliahController = TextEditingController();
  DateTime? _deadline;

  @override
  void initState() {
    super.initState();
    if (widget.tugas != null) {
      _namaTugasController.text = widget.tugas!.namaTugas;
      _mataKuliahController.text = widget.tugas!.mataKuliah;
      _deadline = widget.tugas!.deadline;
    }
  }

  Future<void> _pickDateTime() async {
    final date = await showDatePicker(
      context: context,
      initialDate: _deadline ?? DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
    );
    if (date != null) {
      if (!mounted) return;
      final time = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.fromDateTime(_deadline ?? DateTime.now()),
      );
      if (time != null) {
        setState(() {
          _deadline = DateTime(
            date.year,
            date.month,
            date.day,
            time.hour,
            time.minute,
          );
        });
      }
    }
  }

  void _saveTugas() async {
    if (_formKey.currentState!.validate() && _deadline != null) {
      final tugas = Tugas(
        namaTugas: _namaTugasController.text,
        mataKuliah: _mataKuliahController.text,
        deadline: _deadline!,
      );
      if (widget.tugas != null) {
        widget.tugas!.namaTugas = _namaTugasController.text;
        widget.tugas!.mataKuliah = _mataKuliahController.text;
        widget.tugas!.deadline = _deadline!;
        await HiveService.updateTugas(widget.tugas!);
      } else {
        await HiveService.addTugas(tugas);
      }
      if (!mounted) return;
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.tugas != null ? 'Edit Tugas' : 'Tambah Tugas'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _namaTugasController,
                decoration: const InputDecoration(labelText: 'Nama Tugas'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter nama tugas';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _mataKuliahController,
                decoration: const InputDecoration(labelText: 'Mata Kuliah'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter mata kuliah';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              ListTile(
                title: Text(
                  _deadline == null
                      ? 'Pilih Deadline'
                      : '${_deadline!.day}/${_deadline!.month}/${_deadline!.year} ${_deadline!.hour}:${_deadline!.minute.toString().padLeft(2, '0')}',
                ),
                trailing: const Icon(Icons.calendar_today),
                onTap: _pickDateTime,
              ),
              if (_deadline == null)
                const Padding(
                  padding: EdgeInsets.only(left: 16.0, top: 8.0),
                  child: Text(
                    'Please pick a deadline',
                    style: TextStyle(color: Colors.red),
                  ),
                ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: _saveTugas,
                child: const Text('Simpan'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
