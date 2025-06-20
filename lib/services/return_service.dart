import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:mime/mime.dart';
import '../model/return_request_model.dart';

class ReturnRequestService {
  final baseUrl = 'http://127.0.0.1:8000/api'; // Ganti sesuai base API-mu

  Future<List<ReturnRequestModel>> fetchReturnRequests(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/returns'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final List<dynamic> jsonData = json.decode(response.body);
      return jsonData.map((data) => ReturnRequestModel.fromJson(data)).toList();
    } else {
      throw Exception('Gagal memuat data pengembalian');
    }
  }

  Future<bool> storeReturnRequest({
    required String token,
    required int loanId,
    required int quantity,
    required DateTime returnDate,
    File? photo,
  }) async {
    final uri = Uri.parse('$baseUrl/returns');
    final request =
        http.MultipartRequest('POST', uri)
          ..headers['Authorization'] = 'Bearer $token'
          ..fields['loan_id'] = loanId.toString()
          ..fields['quantity'] = quantity.toString()
          ..fields['return_date'] = returnDate.toIso8601String().split('T')[0];

    if (photo != null) {
      final mime = lookupMimeType(photo.path)!.split('/');
      request.files.add(
        await http.MultipartFile.fromPath(
          'photo',
          photo.path,
          contentType: MediaType(mime[0], mime[1]),
        ),
      );
    }

    final response = await request.send();
    return response.statusCode == 200 || response.statusCode == 201;
  }
}
