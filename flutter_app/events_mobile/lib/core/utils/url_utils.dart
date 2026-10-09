import '../config/env.dart';

/// Rewrites a media URL so it points to the API origin. Handles both relative
/// paths (e.g. "/storage/events/x.jpg") and absolute URLs served from a host
/// different from the API base (e.g. server APP_URL).
String mediaUrl(String? url) {
  if (url == null || url.isEmpty) return '';

  final base = Uri.tryParse(Env.apiBaseUrl);
  final parsed = Uri.tryParse(url);
  if (base == null || parsed == null) return url;

  final path = parsed.path.startsWith('/') ? parsed.path : '/${parsed.path}';
  return Uri(
    scheme: base.scheme,
    host: base.host,
    port: base.hasPort ? base.port : null,
    path: path,
    query: parsed.query.isEmpty ? null : parsed.query,
  ).toString();
}
