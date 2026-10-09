import 'package:flutter/foundation.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

class Env {
  const Env._();

  static const _defaultBaseUrl = 'http://landing.test/api/v1';
  static const _androidHost = '10.0.2.2';
  static const _androidPort = 8080;

  static String get apiBaseUrl {
    const override = String.fromEnvironment('API_BASE_URL');
    final raw = override.isNotEmpty
        ? override
        : (dotenv.env['API_BASE_URL'] ?? _defaultBaseUrl);
    return _normalizeForPlatform(raw);
  }

  static String get appEnv => dotenv.env['APP_ENV'] ?? 'development';

  static bool get isDevelopment => appEnv == 'development';

  static String _normalizeForPlatform(String url) {
    if (kIsWeb) return url;
    final uri = Uri.tryParse(url);
    if (uri == null) return url;
    final isLoopback = uri.host == 'landing.test' ||
        uri.host == 'localhost' ||
        uri.host == '127.0.0.1';
    if (defaultTargetPlatform == TargetPlatform.android && isLoopback) {
      return uri.replace(host: _androidHost, port: _androidPort).toString();
    }
    return url;
  }
}
