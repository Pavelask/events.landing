import 'package:flutter_dotenv/flutter_dotenv.dart';

class Env {
  const Env._();

  static String get apiBaseUrl =>
      dotenv.env['API_BASE_URL'] ?? 'http://landing.test/api/v1';

  static String get appEnv => dotenv.env['APP_ENV'] ?? 'development';

  static bool get isDevelopment => appEnv == 'development';
}
