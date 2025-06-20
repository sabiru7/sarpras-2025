import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../model/user_model.dart';
import '../services/auth_service.dart';

class AuthProvider extends ChangeNotifier {
  UserModel? user;
  bool isLoggedIn = false;
  bool isLoading = true;

  String? _token;

  String? get token => _token;

  final AuthService _authService = AuthService();

  AuthProvider() {
    checkLoginStatus(); // Auto-check saat provider diinisialisasi
  }

  Future<void> setToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
    _token = token;
  }

  Future<void> checkLoginStatus() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('token');

    if (_token != null) {
      try {
        final data = await _authService.getProfile();
        user = UserModel.fromJson(data['user']);
        isLoggedIn = true;
      } catch (_) {
        isLoggedIn = false;
        _token = null;
        prefs.remove('token');
      }
    } else {
      isLoggedIn = false;
    }

    isLoading = false;
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    try {
      final result = await _authService.login(email, password);

      final accessToken = result['token'];
      final userData = result['user'];

      await setToken(accessToken);
      user = UserModel.fromJson(userData);
      isLoggedIn = true;
      notifyListeners();
      return true;
    } catch (e) {
      // Bisa ditambahkan debugPrint jika perlu
      return false;
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    _token = null;
    user = null;
    isLoggedIn = false;
    notifyListeners();
  }
}
