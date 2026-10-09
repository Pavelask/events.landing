import 'package:freezed_annotation/freezed_annotation.dart';

part 'ticket.freezed.dart';
part 'ticket.g.dart';

@freezed
abstract class ParticipantInfo with _$ParticipantInfo {
  const factory ParticipantInfo({
    required int id,
    String? name,
    String? email,
  }) = _ParticipantInfo;

  factory ParticipantInfo.fromJson(Map<String, dynamic> json) =>
      _$ParticipantInfoFromJson(json);
}

@freezed
abstract class TicketEvent with _$TicketEvent {
  const factory TicketEvent({
    String? slug,
    String? title,
    DateTime? startDate,
    DateTime? endDate,
    String? venueName,
    String? venueAddress,
    String? posterImage,
  }) = _TicketEvent;

  factory TicketEvent.fromJson(Map<String, dynamic> json) =>
      _$TicketEventFromJson(json);
}

@freezed
abstract class Ticket with _$Ticket {
  const factory Ticket({
    required String type,
    required String token,
    String? status,
    @Default(false) bool isCheckedIn,
    bool? alreadyCheckedIn,
    DateTime? checkedInAt,
    ParticipantInfo? participant,
    TicketEvent? event,
    String? checkinUrl,
    String? qrUrl,
    String? ticketUrl,
  }) = _Ticket;

  factory Ticket.fromJson(Map<String, dynamic> json) => _$TicketFromJson(json);
}
