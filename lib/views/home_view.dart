import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/item_service.dart';
import '../model/item_model.dart';
import '../providers/auth_provider.dart';
import 'item_detail_view.dart';
import 'profile_view.dart';

class HomeView extends StatefulWidget {
  const HomeView({super.key});

  @override
  State<HomeView> createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  late Future<List<ItemModel>> _futureItems;

  @override
  void initState() {
    super.initState();
    _refreshData();
  }

  Future<void> _refreshData() async {
    final token = Provider.of<AuthProvider>(context, listen: false).token;
    setState(() {
      _futureItems = ItemService().fetchItems(token!);
    });
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final _ = theme.brightness == Brightness.dark;
    final colors = theme.colorScheme;

    return Scaffold(
      appBar: AppBar(
        title: const Text('SISFO SARPRAS'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person),
            onPressed:
                () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const ProfileView()),
                ),
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed:
                () =>
                    Provider.of<AuthProvider>(context, listen: false).logout(),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refreshData,
        child: FutureBuilder<List<ItemModel>>(
          future: _futureItems,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }

            if (snapshot.hasError) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.error_outline, size: 50, color: colors.error),
                    const SizedBox(height: 16),
                    Text(
                      'Gagal memuat data',
                      style: theme.textTheme.titleMedium,
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: _refreshData,
                      child: const Text('Coba Lagi'),
                    ),
                  ],
                ),
              );
            }

            final items = snapshot.data!;
            if (items.isEmpty) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.inventory, size: 60, color: colors.primary),
                    const SizedBox(height: 16),
                    Text(
                      'Tidak ada barang tersedia',
                      style: theme.textTheme.titleMedium,
                    ),
                  ],
                ),
              );
            }

            return ListView.separated(
              padding: const EdgeInsets.all(12),
              itemCount: items.length,
              separatorBuilder: (context, index) => const SizedBox(height: 8),
              itemBuilder: (context, index) {
                final item = items[index];
                return ListTile(
                  leading: Container(
                    width: 48,
                    height: 48,
                    decoration: BoxDecoration(
                      color: colors.surfaceContainerHighest,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child:
                        item.photo != null
                            ? ClipRRect(
                              borderRadius: BorderRadius.circular(8),
                              child: Image.network(
                                'http://localhost:8000/storage/${item.photo}',
                                fit: BoxFit.cover,
                                errorBuilder:
                                    (context, error, stackTrace) => Icon(
                                      Icons.broken_image,
                                      color: colors.primary,
                                    ),
                              ),
                            )
                            : Icon(Icons.inventory, color: colors.primary),
                  ),
                  title: Text(item.name, style: theme.textTheme.titleMedium),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(item.category?.name ?? 'Tanpa Kategori'),
                      Text('Stok: ${item.quantity}'),
                    ],
                  ),
                  onTap:
                      () => Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => ItemDetailView(item: item),
                        ),
                      ),
                );
              },
            );
          },
        ),
      ),
    );
  }
}
