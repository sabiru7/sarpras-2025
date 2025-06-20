import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  final String baseUrl = 'http://127.0.0.1:8000/api/users';

  Future<Map<String, dynamic>> login(String email, String password) async {
    final url = Uri.parse('http://localhost:8000/api/users');

    final response = await http.post(
      url,
      headers: {'Accept': 'application/json'},
      body: {'email': email, 'password': password},
    );


    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return {
        'success': true,
        'token': data['access_token'],
        'user': data['user'],
      };
    } else {
      // Kalau status bukan 200, parse error-nya
      try {
        final error = json.decode(response.body);
        return {'success': false, 'message': error['message'] ?? 'Login gagal'};
      } catch (_) {
        return {
          'success': false,
          'message': 'Login gagal: tidak dapat parsing response',
        };
      }
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/logout'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      await prefs.remove('token');
    } else {
      throw Exception('Logout gagal');
    }
  }

  Future<Map<String, dynamic>> getProfile() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/profile'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return json['data'];
    } else {
      throw Exception('Gagal memuat profil');
    }
  }
}
