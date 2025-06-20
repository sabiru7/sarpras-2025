import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../model/loan_model.dart';
import '../model/return_request_model.dart';
import '../services/loan_service.dart';
import '../services/return_service.dart';
import 'loan_detail_view.dart';

class ProfileView extends StatefulWidget {
  const ProfileView({super.key});

  @override
  State<ProfileView> createState() => _ProfileViewState();
}

class _ProfileViewState extends State<ProfileView> {
  late Future<List<LoanModel>> _futureLoans;
  late Future<List<ReturnRequestModel>> _futureReturns;

  @override
  void initState() {
    super.initState();
    _refreshData();
  }

  Future<void> _refreshData() async {
    final token = Provider.of<AuthProvider>(context, listen: false).token!;
    setState(() {
      _futureLoans = LoanService().fetchLoans(token);
      _futureReturns = ReturnRequestService().fetchReturnRequests(token);
    });
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final auth = Provider.of<AuthProvider>(context);
    final user = auth.user;

    return Scaffold(
      appBar: AppBar(title: const Text('Profil Saya')),
      body: RefreshIndicator(
        onRefresh: _refreshData,
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // User Profile
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      CircleAvatar(
                        backgroundColor: theme.colorScheme.primaryContainer,
                        child: Icon(
                          Icons.person,
                          color: theme.colorScheme.onPrimaryContainer,
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              user?.name ?? 'Pengguna',
                              style: theme.textTheme.titleMedium,
                            ),
                            Text(
                              user?.email ?? '-',
                              style: theme.textTheme.bodySmall,
                            ),
                            const SizedBox(height: 4),
                            Chip(
                              label: Text(
                                user?.role.toUpperCase() ?? 'ROLE',
                                style: theme.textTheme.labelSmall,
                              ),
                              backgroundColor:
                                  theme.colorScheme.primaryContainer,
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Loans Section
              _buildSectionHeader('Riwayat Peminjaman', Icons.history),
              FutureBuilder<List<LoanModel>>(
                future: _futureLoans,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (snapshot.hasError) {
                    return _buildErrorState(
                      'Gagal memuat peminjaman',
                      onRetry: _refreshData,
                    );
                  }
                  if (snapshot.data!.isEmpty) {
                    return _buildEmptyState('Belum ada data peminjaman');
                  }
                  return Column(
                    children:
                        snapshot.data!
                            .map((loan) => _buildLoanItem(context, loan))
                            .toList(),
                  );
                },
              ),
              const SizedBox(height: 24),

              // Returns Section
              _buildSectionHeader(
                'Riwayat Pengembalian',
                Icons.keyboard_return,
              ),
              FutureBuilder<List<ReturnRequestModel>>(
                future: _futureReturns,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (snapshot.hasError) {
                    return _buildErrorState(
                      'Gagal memuat pengembalian',
                      onRetry: _refreshData,
                    );
                  }
                  if (snapshot.data!.isEmpty) {
                    return _buildEmptyState('Belum ada data pengembalian');
                  }
                  return Column(
                    children:
                        snapshot.data!
                            .map((ret) => _buildReturnItem(context, ret))
                            .toList(),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title, IconData icon) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, color: Theme.of(context).colorScheme.primary),
          const SizedBox(width: 8),
          Text(
            title,
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  Widget _buildLoanItem(BuildContext context, LoanModel loan) {
    final statusColor = _getStatusColor(loan.status);

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: statusColor.withOpacity(0.1),
            shape: BoxShape.circle,
          ),
          child: Icon(Icons.inventory, color: statusColor),
        ),
        title: Text(loan.item.name),
        subtitle: Text(
          '${loan.quantity} unit • ${loan.status.toUpperCase()}',
          style: TextStyle(color: statusColor),
        ),
        trailing: const Icon(Icons.chevron_right),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => LoanDetailView(loan: loan)),
          );
        },
      ),
    );
  }

  Widget _buildReturnItem(BuildContext context, ReturnRequestModel ret) {
    final detail = ret.details.first;
    final statusColor = _getStatusColor(ret.status);

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: statusColor.withOpacity(0.1),
            shape: BoxShape.circle,
          ),
          child: Icon(Icons.keyboard_return, color: statusColor),
        ),
        title: Text(detail.item.name),
        subtitle: Text(
          ret.status.toUpperCase(),
          style: TextStyle(color: statusColor),
        ),
      ),
    );
  }

  Widget _buildErrorState(String message, {VoidCallback? onRetry}) {
    return Card(
      color: Theme.of(context).colorScheme.errorContainer,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                Icon(
                  Icons.error_outline,
                  color: Theme.of(context).colorScheme.error,
                ),
                const SizedBox(width: 8),
                Text(
                  message,
                  style: TextStyle(color: Theme.of(context).colorScheme.error),
                ),
              ],
            ),
            if (onRetry != null) ...[
              const SizedBox(height: 8),
              OutlinedButton(
                onPressed: onRetry,
                child: const Text('Coba Lagi'),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(String message) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Center(
          child: Text(message, style: Theme.of(context).textTheme.bodyMedium),
        ),
      ),
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
}
