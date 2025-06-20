import 'package:flutter/material.dart';
import '../model/loan_model.dart';
import 'return_form_view.dart';

class LoanDetailView extends StatelessWidget {
  final LoanModel loan;

  const LoanDetailView({super.key, required this.loan});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final _ = theme.colorScheme;
    final statusColor = _getStatusColor(loan.status);

    return Scaffold(
      appBar: AppBar(title: const Text('Detail Peminjaman')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Status Card
            Card(
              color: statusColor.withOpacity(0.1),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  children: [
                    Icon(_getStatusIcon(loan.status), color: statusColor),
                    const SizedBox(width: 12),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Status Peminjaman',
                          style: theme.textTheme.bodySmall,
                        ),
                        Text(
                          loan.status.toUpperCase(),
                          style: theme.textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: statusColor,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Item Name
            Text(
              loan.item.name,
              style: theme.textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),

            // Loan Details Card
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    _buildDetailRow(
                      context,
                      icon: Icons.confirmation_number,
                      label: 'ID Peminjaman',
                      value: loan.id.toString(),
                    ),
                    const Divider(height: 24),
                    _buildDetailRow(
                      context,
                      icon: Icons.format_list_numbered,
                      label: 'Jumlah',
                      value: '${loan.quantity} unit',
                    ),
                    const Divider(height: 24),
                    _buildDetailRow(
                      context,
                      icon: Icons.calendar_today,
                      label: 'Tanggal Pinjam',
                      value: loan.borrowedAt,
                    ),
                    ...[
                    const Divider(height: 24),
                    _buildDetailRow(
                      context,
                      icon: Icons.calendar_today,
                      label: 'Tanggal Kembali',
                      value: loan.returnedAt,
                    ),
                  ],
                    ...[
                    const Divider(height: 24),
                    _buildDetailRow(
                      context,
                      icon: Icons.timer,
                      label: 'Batas Pengembalian',
                      value: loan.returnedAt,
                    ),
                  ],
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      floatingActionButton:
          loan.status.toLowerCase() == 'dipinjam'
              ? FloatingActionButton.extended(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => ReturnFormView(loan: loan),
                    ),
                  );
                },
                icon: const Icon(Icons.keyboard_return),
                label: const Text('Ajukan Pengembalian'),
              )
              : null,
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }

  Widget _buildDetailRow(
    BuildContext context, {
    required IconData icon,
    required String label,
    required String value,
  }) {
    final theme = Theme.of(context);

    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 24, color: theme.colorScheme.primary),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: theme.textTheme.bodySmall),
              const SizedBox(height: 4),
              Text(value, style: theme.textTheme.bodyMedium),
            ],
          ),
        ),
      ],
    );
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'dipinjam':
        return Colors.orange;
      case 'dikembalikan':
        return Colors.green;
      case 'ditolak':
        return Colors.red;
      case 'menunggu':
        return Colors.blue;
      default:
        return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status.toLowerCase()) {
      case 'dipinjam':
        return Icons.inventory_2;
      case 'dikembalikan':
        return Icons.check_circle;
      case 'ditolak':
        return Icons.cancel;
      case 'menunggu':
        return Icons.access_time;
      default:
        return Icons.info;
    }
  }
}
