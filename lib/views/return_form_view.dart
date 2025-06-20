import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../model/loan_model.dart';
import '../services/return_service.dart';
import '../providers/auth_provider.dart';

class ReturnFormView extends StatefulWidget {
  final LoanModel loan;

  const ReturnFormView({super.key, required this.loan});

  @override
  State<ReturnFormView> createState() => _ReturnFormViewState();
}

class _ReturnFormViewState extends State<ReturnFormView> {
  final _formKey = GlobalKey<FormState>();
  final _quantityController = TextEditingController();
  final _notesController = TextEditingController();
  final _dateFormat = DateFormat('dd MMMM yyyy');

  DateTime? _selectedDate;
  File? _selectedPhoto;
  bool _isSubmitting = false;

  Future<void> _pickImage() async {
    try {
      final pickedFile = await ImagePicker().pickImage(
        source: ImageSource.gallery,
        imageQuality: 85,
      );
      if (pickedFile != null) {
        setState(() => _selectedPhoto = File(pickedFile.path));
      }
    } catch (e) {
      _showSnackbar('Gagal memilih gambar', isError: true);
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate() || _selectedDate == null) {
      _showSnackbar('Harap lengkapi semua data', isError: true);
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      final token = Provider.of<AuthProvider>(context, listen: false).token!;
      final success = await ReturnRequestService().storeReturnRequest(
        token: token,
        loanId: widget.loan.id,
        quantity: int.parse(_quantityController.text),
        returnDate: _selectedDate!,
        photo: _selectedPhoto,
      );

      if (!mounted) return;
      setState(() => _isSubmitting = false);

      if (success) {
        _showSnackbar('Pengembalian berhasil diajukan');
        Navigator.pop(context);
      } else {
        _showSnackbar('Gagal mengirim pengembalian', isError: true);
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isSubmitting = false);
      _showSnackbar('Terjadi kesalahan', isError: true);
    }
  }

  void _showSnackbar(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: isError ? Colors.red : Colors.green,
      ),
    );
  }

  Future<void> _selectDate(BuildContext context) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now().subtract(const Duration(days: 30)),
      lastDate: DateTime.now().add(const Duration(days: 30)),
    );
    if (picked != null && mounted) {
      setState(() => _selectedDate = picked);
    }
  }

  @override
  void dispose() {
    _quantityController.dispose();
    _notesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(title: const Text('Form Pengembalian')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Item Info
              ListTile(
                leading: Icon(Icons.inventory, color: theme.primaryColor),
                title: const Text('Barang yang dipinjam'),
                subtitle: Text(
                  widget.loan.item.name,
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                trailing: Text('${widget.loan.quantity} unit'),
              ),
              const Divider(),

              // Quantity Field
              TextFormField(
                controller: _quantityController,
                decoration: const InputDecoration(
                  labelText: 'Jumlah Dikembalikan',
                  prefixIcon: Icon(Icons.format_list_numbered),
                ),
                keyboardType: TextInputType.number,
                validator: (value) {
                  final qty = int.tryParse(value ?? '');
                  if (qty == null || qty <= 0) return 'Jumlah tidak valid';
                  if (qty > widget.loan.quantity) {
                    return 'Maksimal ${widget.loan.quantity} unit';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),

              // Return Date
              ListTile(
                leading: Icon(Icons.calendar_today, color: theme.primaryColor),
                title: const Text('Tanggal Pengembalian'),
                subtitle: Text(
                  _selectedDate != null
                      ? _dateFormat.format(_selectedDate!)
                      : 'Pilih tanggal',
                  style: TextStyle(
                    color: _selectedDate != null ? null : Colors.grey,
                  ),
                ),
                trailing: const Icon(Icons.chevron_right),
                onTap: () => _selectDate(context),
              ),
              const Divider(),

              // Photo Section
              const Text('Foto Barang (Opsional)'),
              const SizedBox(height: 8),
              if (_selectedPhoto != null)
                Padding(
                  padding: const EdgeInsets.only(bottom: 8),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: Image.file(
                      _selectedPhoto!,
                      height: 150,
                      width: double.infinity,
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
              OutlinedButton.icon(
                onPressed: _pickImage,
                icon: const Icon(Icons.photo_library),
                label: const Text('Pilih Foto'),
              ),
              const SizedBox(height: 16),

              // Notes Field
              TextFormField(
                controller: _notesController,
                decoration: const InputDecoration(
                  labelText: 'Catatan (Opsional)',
                  alignLabelWithHint: true,
                ),
                maxLines: 3,
              ),
              const SizedBox(height: 24),

              // Submit Button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submit,
                  child:
                      _isSubmitting
                          ? const CircularProgressIndicator()
                          : const Text('AJUKAN PENGEMBALIAN'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
