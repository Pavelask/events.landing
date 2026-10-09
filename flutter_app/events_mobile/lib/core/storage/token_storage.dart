import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class TokenStorage {
  TokenStorage([FlutterSecureStorage? storage])
      : _storage = storage ?? const FlutterSecureStorage();

  static const _tokenKey = 'api_token';

  final FlutterSecureStorage _storage;

  String? _cached;

  Future<String?> read() async {
    if (_cached != null) return _cached;
    try {
      _cached = await _storage.read(key: _tokenKey);
    } catch (_) {
      _cached = null;
    }
    return _cached;
  }

  Future<void> write(String token) async {
    _cached = token;
    try {
      await _storage.write(key: _tokenKey, value: token);
    } catch (_) {
      // Secure storage может быть недоступен на web — оставляем in-memory.
    }
  }

  Future<void> clear() async {
    _cached = null;
    try {
      await _storage.delete(key: _tokenKey);
    } catch (_) {
      // ignore
    }
  }

  bool get hasToken => _cached != null;
}
