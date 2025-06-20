import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../services/loan_service.dart';
import '../providers/auth_provider.dart';

class LoanFormPage extends StatefulWidget {
  final int itemId;
  final String itemName;

  const LoanFormPage({super.key, required this.itemId, required this.itemName});

  @override
  State<LoanFormPage> createState() => _LoanFormPageState();
}

class _LoanFormPageState extends State<LoanFormPage> {
  final _formKey = GlobalKey<FormState>();
  int quantity = 1;
  DateTime? borrowedAt;
  DateTime? returnedAt;
  bool isLoading = false;

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate() ||
        borrowedAt == null ||
        returnedAt == null) {
      return;
    }

    setState(() => isLoading = true);

    try {
      final token = Provider.of<AuthProvider>(context, listen: false).token;
      final success = await LoanService().createLoan(
        token: token!,
        itemId: widget.itemId,
        quantity: quantity,
        borrowedAt: DateFormat('yyyy-MM-dd').format(borrowedAt!),
        returnedAt: DateFormat('yyyy-MM-dd').format(returnedAt!),
      );

      if (!mounted) return;
      setState(() => isLoading = false);

      if (success) {
        _showSnackbar('Peminjaman berhasil dikirim', Colors.green);
        Navigator.pop(context);
      } else {
        _showSnackbar('Gagal mengirim peminjaman', Colors.red);
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => isLoading = false);
      _showSnackbar('Terjadi kesalahan: ${e.toString()}', Colors.red);
    }
  }

  void _showSnackbar(String message, Color color) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: color,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  Future<void> _pickDate(BuildContext context, bool isBorrowed) async {
    final initialDate = DateTime.now().add(Duration(days: isBorrowed ? 0 : 1));
    final firstDate =
        isBorrowed ? DateTime.now() : borrowedAt ?? DateTime.now();

    final picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: firstDate,
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );

    if (picked != null) {
      setState(() {
        if (isBorrowed) {
          borrowedAt = picked;
          if (returnedAt != null && returnedAt!.isBefore(picked)) {
            returnedAt = null;
          }
        } else {
          returnedAt = picked;
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final _ = DateFormat('dd MMMM yyyy');

    return Scaffold(
      appBar: AppBar(title: Text('Pinjam ${widget.itemName}')),
      body:
          isLoading
              ? const Center(child: CircularProgressIndicator())
              : SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Item Info
                      ListTile(
                        leading: Icon(
                          Icons.inventory,
                          color: theme.primaryColor,
                        ),
                        title: const Text('Anda meminjam:'),
                        subtitle: Text(
                          widget.itemName,
                          style: theme.textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      const Divider(),

                      // Quantity Input
                      TextFormField(
                        decoration: const InputDecoration(
                          labelText: 'Jumlah Barang',
                          prefixIcon: Icon(Icons.format_list_numbered),
                        ),
                        initialValue: '1',
                        keyboardType: TextInputType.number,
                        validator: (val) {
                          if (val == null ||
                              int.tryParse(val) == null ||
                              int.parse(val) < 1) {
                            return 'Masukkan jumlah valid (minimal 1)';
                          }
                          return null;
                        },
                        onChanged: (val) => quantity = int.tryParse(val) ?? 1,
                      ),
                      const SizedBox(height: 16),

                      // Date Pickers
                      Text(
                        'Periode Peminjaman',
                        style: theme.textTheme.titleMedium,
                      ),
                      const SizedBox(height: 8),
                      _buildDateTile(
                        context,
                        title: 'Tanggal Pinjam',
                        date: borrowedAt,
                        isBorrowed: true,
                      ),
                      _buildDateTile(
                        context,
                        title: 'Tanggal Kembali',
                        date: returnedAt,
                        isBorrowed: false,
                      ),
                      const SizedBox(height: 24),

                      // Submit Button
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: _submit,
                          child: const Text('AJUKAN PEMINJAMAN'),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
    );
  }

  Widget _buildDateTile(
    BuildContext context, {
    required String title,
    required DateTime? date,
    required bool isBorrowed,
  }) {
    final dateFormat = DateFormat('dd MMMM yyyy');

    return ListTile(
      leading: Icon(
        isBorrowed ? Icons.calendar_today : Icons.event_available,
        color: Theme.of(context).primaryColor,
      ),
      title: Text(title),
      subtitle: Text(
        date != null ? dateFormat.format(date) : 'Pilih tanggal',
        style: TextStyle(color: date != null ? null : Colors.grey),
      ),
      trailing: const Icon(Icons.chevron_right),
      onTap: () => _pickDate(context, isBorrowed),
    );
  }
}
