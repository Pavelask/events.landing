import '../../core/network/api_client.dart';
import '../models/ticket.dart';

class TicketRepository {
  TicketRepository(this._client);

  final ApiClient _client;

  Future<Ticket> show(String token) async {
    final json = await _client.getJson('/ticket/$token');
    return Ticket.fromJson(json['data'] as Map<String, dynamic>);
  }

  Future<Ticket> checkIn(String token) async {
    final json = await _client.postJson('/checkin/by-token', body: {
      'token': token,
    });
    return Ticket.fromJson(json['data'] as Map<String, dynamic>);
  }
}
