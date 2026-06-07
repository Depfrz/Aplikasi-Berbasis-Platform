import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/mahasiswa.dart';

class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  static Future<String?> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'username': username, 'password': password}),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['token'];
    }
    return null;
  }

  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  static Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  static Future<http.Response> _authenticatedRequest(
    String method,
    String endpoint, {
    Map<String, dynamic>? body,
  }) async {
    final token = await getToken();
    if (token == null) {
      throw Exception('Unauthorized');
    }

    final headers = {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    };

    http.Response response;
    final uri = Uri.parse('$baseUrl$endpoint');

    switch (method.toUpperCase()) {
      case 'GET':
        response = await http.get(uri, headers: headers);
        break;
      case 'POST':
        response = await http.post(
          uri,
          headers: headers,
          body: body != null ? jsonEncode(body) : null,
        );
        break;
      case 'PUT':
        response = await http.put(
          uri,
          headers: headers,
          body: body != null ? jsonEncode(body) : null,
        );
        break;
      case 'DELETE':
        response = await http.delete(uri, headers: headers);
        break;
      default:
        throw Exception('Unsupported method');
    }

    return response;
  }

  static Future<List<Mahasiswa>> getMahasiswa() async {
    final response = await _authenticatedRequest('GET', '/mahasiswa');
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => Mahasiswa.fromJson(json)).toList();
    } else if (response.statusCode == 401) {
      await clearToken();
      throw Exception('Unauthorized');
    }
    throw Exception('Failed to load data');
  }

  static Future<Mahasiswa> createMahasiswa(Mahasiswa mahasiswa) async {
    final response = await _authenticatedRequest(
      'POST',
      '/mahasiswa',
      body: mahasiswa.toJson(),
    );
    if (response.statusCode == 201) {
      return Mahasiswa.fromJson(jsonDecode(response.body));
    } else if (response.statusCode == 401) {
      await clearToken();
      throw Exception('Unauthorized');
    }
    throw Exception('Failed to create data');
  }

  static Future<Mahasiswa> updateMahasiswa(Mahasiswa mahasiswa) async {
    final response = await _authenticatedRequest(
      'PUT',
      '/mahasiswa/${mahasiswa.id}',
      body: mahasiswa.toJson(),
    );
    if (response.statusCode == 200) {
      return Mahasiswa.fromJson(jsonDecode(response.body));
    } else if (response.statusCode == 401) {
      await clearToken();
      throw Exception('Unauthorized');
    }
    throw Exception('Failed to update data');
  }

  static Future<void> deleteMahasiswa(int id) async {
    final response = await _authenticatedRequest('DELETE', '/mahasiswa/$id');
    if (response.statusCode != 200) {
      if (response.statusCode == 401) {
        await clearToken();
        throw Exception('Unauthorized');
      }
      throw Exception('Failed to delete data');
    }
  }

  static Future<List<Mahasiswa>> searchMahasiswa(String keyword) async {
    final response = await _authenticatedRequest(
      'GET',
      '/mahasiswa/search?nama=$keyword',
    );
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => Mahasiswa.fromJson(json)).toList();
    } else if (response.statusCode == 401) {
      await clearToken();
      throw Exception('Unauthorized');
    }
    throw Exception('Failed to search data');
  }
}
