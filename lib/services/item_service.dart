import 'dart:convert';
import 'package:http/http.dart' as http;
import '../model/item_model.dart';

class ItemService {
  final String baseUrl = 'http://127.0.0.1:8000/api';

  Future<List<ItemModel>> fetchItems(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/items'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      final List data =
          body['data'];
      return data.map((e) => ItemModel.fromJson(e)).toList();
    } else {
      throw Exception('Gagal memuat data item');
    }
  }
}
