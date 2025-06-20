import 'dart:convert';
import 'package:http/http.dart' as http;
import '../model/loan_model.dart';

class LoanService {
  final String baseUrl = 'http://127.0.0.1:8000/api';

  // ✅ FETCH LOANS
  Future<List<LoanModel>> fetchLoans(String token) async {
    final url = Uri.parse('$baseUrl/loans');

    final response = await http.get(
      url,
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      final jsonData = json.decode(response.body);
      // cek response

      final List<dynamic> loanList = jsonData['data'];

      try {
        return loanList.map((e) => LoanModel.fromJson(e)).toList();
      } catch (e, st) {
        print('Error parsing loans: $e');
        print(st);
        throw Exception('Error parsing loans: $e');
      }
    } else {
      throw Exception('Gagal mengambil data loans: ${response.statusCode}');
    }
  }

  // ✅ CREATE LOAN
  Future<bool> createLoan({
    required String token,
    required int itemId,
    required int quantity,
    required String borrowedAt,
    required String returnedAt,
  }) async {
    final url = Uri.parse('$baseUrl/loans');

    final response = await http.post(
      url,
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
      body: {
        'item_id': itemId.toString(),
        'quantity': quantity.toString(),
        'borrowed_at': borrowedAt,
        'returned_at': returnedAt,
      },
    );

    if (response.statusCode == 201) {
      return true;
    } else {
      print('Gagal membuat loan: ${response.body}');
      return false;
    }
  }
}
