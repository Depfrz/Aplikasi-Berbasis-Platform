
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'email': email, 'password': password}),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', data['token']);
      return {'success': true, 'data': data};
    } else {
      return {'success': false, 'message': 'Username/Password salah'};
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    if (token != null) {
      await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
    }
    await prefs.remove('token');
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Future<Map<String, String>> _getHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  Future<List<dynamic>> getMahasiswa({String? search}) async {
    try {
      final headers = await _getHeaders();
      String url = '$baseUrl/mahasiswa';
      if (search != null && search.isNotEmpty) {
        url += '?search=$search';
      }
      final response = await http.get(Uri.parse(url), headers: headers);

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        throw Exception('Gagal mengambil data');
      }
    } catch (e) {
      throw Exception('Gagal terhubung ke server, periksa koneksi Anda');
    }
  }

  Future<Map<String, dynamic>> createMahasiswa(Map<String, dynamic> data) async {
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/mahasiswa'),
        headers: headers,
        body: jsonEncode(data),
      );

      if (response.statusCode == 201) {
        return {'success': true, 'data': jsonDecode(response.body)};
      } else {
        return {'success': false, 'message': 'Gagal menambah data'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server, periksa koneksi Anda'};
    }
  }

  Future<Map<String, dynamic>> updateMahasiswa(int id, Map<String, dynamic> data) async {
    try {
      final headers = await _getHeaders();
      final response = await http.put(
        Uri.parse('$baseUrl/mahasiswa/$id'),
        headers: headers,
        body: jsonEncode(data),
      );

      if (response.statusCode == 200) {
        return {'success': true, 'data': jsonDecode(response.body)};
      } else {
        return {'success': false, 'message': 'Gagal mengupdate data'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server, periksa koneksi Anda'};
    }
  }

  Future<Map<String, dynamic>> deleteMahasiswa(int id) async {
    try {
      final headers = await _getHeaders();
      final response = await http.delete(
        Uri.parse('$baseUrl/mahasiswa/$id'),
        headers: headers,
      );

      if (response.statusCode == 200) {
        return {'success': true};
      } else {
        return {'success': false, 'message': 'Gagal menghapus data'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server, periksa koneksi Anda'};
    }
  }
}
