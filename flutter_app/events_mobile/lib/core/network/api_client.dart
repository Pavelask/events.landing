import 'package:dio/dio.dart';

import '../config/env.dart';
import '../storage/token_storage.dart';
import 'api_exception.dart';

class ApiClient {
  ApiClient(this._tokenStorage)
      : dio = Dio(
          BaseOptions(
            baseUrl: Env.apiBaseUrl,
            connectTimeout: const Duration(seconds: 15),
            receiveTimeout: const Duration(seconds: 20),
            headers: {'Accept': 'application/json'},
            responseType: ResponseType.json,
          ),
        ) {
    dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _tokenStorage.read();
          if (token != null && token.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          handler.next(options);
        },
      ),
    );
  }

  final TokenStorage _tokenStorage;
  final Dio dio;

  Future<Map<String, dynamic>> getJson(
    String path, {
    Map<String, dynamic>? query,
  }) async {
    try {
      final response = await dio.get<Map<String, dynamic>>(
        path,
        queryParameters: query,
      );
      return response.data ?? <String, dynamic>{};
    } on DioException catch (e) {
      throw _asApiException(e);
    }
  }

  Future<Map<String, dynamic>> postJson(
    String path, {
    Object? body,
  }) async {
    try {
      final response = await dio.post<Map<String, dynamic>>(path, data: body);
      return response.data ?? <String, dynamic>{};
    } on DioException catch (e) {
      throw _asApiException(e);
    }
  }

  ApiException _asApiException(DioException e) {
    final data = e.response?.data;
    String message = 'Не удалось выполнить запрос';

    if (data is Map && data['message'] is String) {
      message = data['message'] as String;
    } else if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.connectionError) {
      message = 'Нет соединения с сервером';
    }

    return ApiException(message, statusCode: e.response?.statusCode);
  }
}
