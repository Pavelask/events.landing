import '../models/event.dart';
import '../../core/network/api_client.dart';

class EventsRepository {
  EventsRepository(this._client);

  final ApiClient _client;

  Future<List<Event>> fetchEvents({
    String? status,
    bool upcoming = false,
    bool active = false,
    int perPage = 15,
  }) async {
    final json = await _client.getJson('/events', query: {
      'status': ?status,
      if (upcoming) 'upcoming': 1,
      if (active) 'active': 1,
      'per_page': perPage,
    });

    final data = (json['data'] as List?) ?? const [];
    return data
        .cast<Map<String, dynamic>>()
        .map(Event.fromJson)
        .toList(growable: false);
  }

  Future<Event> show(String slug) async {
    final json = await _client.getJson('/events/$slug');
    return Event.fromJson(json['data'] as Map<String, dynamic>);
  }
}
