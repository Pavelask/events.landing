import '../../core/network/api_client.dart';
import '../../core/storage/token_storage.dart';
import '../models/app_user.dart';

class AuthRepository {
  AuthRepository(this._client, this._tokenStorage);

  final ApiClient _client;
  final TokenStorage _tokenStorage;

  Future<AppUser> login({
    required String email,
    required String password,
    String? deviceName,
  }) async {
    final json = await _client.postJson('/auth/login', body: {
      'email': email,
      'password': password,
      'device_name': ?deviceName,
    });

    final data = json['data'] as Map<String, dynamic>;
    await _tokenStorage.write(data['token'] as String);

    return AppUser.fromJson(data['user'] as Map<String, dynamic>);
  }

  Future<void> logout() async {
    try {
      await _client.postJson('/auth/logout');
    } finally {
      await _tokenStorage.clear();
    }
  }

  Future<AppUser> me() async {
    final json = await _client.getJson('/me');
    return AppUser.fromJson(json['data'] as Map<String, dynamic>);
  }

  Future<bool> hasSession() async => (await _tokenStorage.read()) != null;
}
