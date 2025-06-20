import 'package:flutter/material.dart';
import '../model/item_model.dart';
import 'loan_form_page.dart';

class ItemDetailView extends StatelessWidget {
  final ItemModel item;

  const ItemDetailView({super.key, required this.item});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colors = theme.colorScheme;

    return Scaffold(
      appBar: AppBar(title: Text(item.name)),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Item Image
            Container(
              height: 200,
              width: double.infinity,
              decoration: BoxDecoration(
                color: colors.surfaceContainerHighest,
                borderRadius: BorderRadius.circular(12),
              ),
              child:
                  item.photo != null
                      ? ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: Image.network(
                          'http://localhost:8000/storage/${item.photo}',
                          fit: BoxFit.cover,
                          errorBuilder:
                              (context, error, stackTrace) => Center(
                                child: Icon(
                                  Icons.broken_image,
                                  size: 60,
                                  color: colors.primary,
                                ),
                              ),
                        ),
                      )
                      : Center(
                        child: Icon(
                          Icons.inventory,
                          size: 60,
                          color: colors.primary,
                        ),
                      ),
            ),
            const SizedBox(height: 16),

            // Category Chip
            if (item.category != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 16),
                child: Chip(
                  label: Text(item.category!.name),
                  backgroundColor: colors.primaryContainer,
                ),
              ),

            // Stock Information
            ListTile(
              leading: Icon(Icons.storage, color: colors.primary),
              title: const Text('Stok Tersedia'),
              subtitle: Text(
                '${item.quantity} unit',
                style: theme.textTheme.titleMedium,
              ),
            ),
            const Divider(),

            // Description Section
            Text(
              'Deskripsi',
              style: theme.textTheme.titleMedium?.copyWith(
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              item.description?.isNotEmpty == true
                  ? item.description!
                  : 'Tidak ada deskripsi tersedia',
              style: theme.textTheme.bodyMedium,
            ),
            const SizedBox(height: 24),

            // Additional Information
            Text(
              'Informasi Tambahan',
              style: theme.textTheme.titleMedium?.copyWith(
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  children: [
                    _buildInfoRow(
                      context,
                      icon: Icons.calendar_today,
                      label: 'Terakhir Diperbarui',
                      value: '12 Juni 2023', // Replace with actual data
                    ),
                    const SizedBox(height: 8),
                    _buildInfoRow(
                      context,
                      icon: Icons.person,
                      label: 'Penanggung Jawab',
                      value: 'Admin Sarpras', // Replace with actual data
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder:
                  (_) => LoanFormPage(itemId: item.id, itemName: item.name),
            ),
          );
        },
        icon: const Icon(Icons.send),
        label: const Text('Ajukan Peminjaman'),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }

  Widget _buildInfoRow(
    BuildContext context, {
    required IconData icon,
    required String label,
    required String value,
  }) {
    final theme = Theme.of(context);

    return Row(
      children: [
        Icon(icon, size: 18, color: theme.colorScheme.primary),
        const SizedBox(width: 8),
        Text('$label: ', style: theme.textTheme.bodyMedium),
        Text(value, style: theme.textTheme.bodyMedium),
      ],
    );
  }
}
