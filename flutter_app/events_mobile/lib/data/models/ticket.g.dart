// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'ticket.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_ParticipantInfo _$ParticipantInfoFromJson(Map<String, dynamic> json) =>
    _ParticipantInfo(
      id: (json['id'] as num).toInt(),
      name: json['name'] as String?,
      email: json['email'] as String?,
    );

Map<String, dynamic> _$ParticipantInfoToJson(_ParticipantInfo instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'email': instance.email,
    };

_TicketEvent _$TicketEventFromJson(Map<String, dynamic> json) => _TicketEvent(
  slug: json['slug'] as String?,
  title: json['title'] as String?,
  startDate: json['start_date'] == null
      ? null
      : DateTime.parse(json['start_date'] as String),
  endDate: json['end_date'] == null
      ? null
      : DateTime.parse(json['end_date'] as String),
  venueName: json['venue_name'] as String?,
  venueAddress: json['venue_address'] as String?,
  posterImage: json['poster_image'] as String?,
);

Map<String, dynamic> _$TicketEventToJson(_TicketEvent instance) =>
    <String, dynamic>{
      'slug': instance.slug,
      'title': instance.title,
      'start_date': instance.startDate?.toIso8601String(),
      'end_date': instance.endDate?.toIso8601String(),
      'venue_name': instance.venueName,
      'venue_address': instance.venueAddress,
      'poster_image': instance.posterImage,
    };

_Ticket _$TicketFromJson(Map<String, dynamic> json) => _Ticket(
  type: json['type'] as String,
  token: json['token'] as String,
  status: json['status'] as String?,
  isCheckedIn: json['is_checked_in'] as bool? ?? false,
  alreadyCheckedIn: json['already_checked_in'] as bool?,
  checkedInAt: json['checked_in_at'] == null
      ? null
      : DateTime.parse(json['checked_in_at'] as String),
  participant: json['participant'] == null
      ? null
      : ParticipantInfo.fromJson(json['participant'] as Map<String, dynamic>),
  event: json['event'] == null
      ? null
      : TicketEvent.fromJson(json['event'] as Map<String, dynamic>),
  checkinUrl: json['checkin_url'] as String?,
  qrUrl: json['qr_url'] as String?,
  ticketUrl: json['ticket_url'] as String?,
);

Map<String, dynamic> _$TicketToJson(_Ticket instance) => <String, dynamic>{
  'type': instance.type,
  'token': instance.token,
  'status': instance.status,
  'is_checked_in': instance.isCheckedIn,
  'already_checked_in': instance.alreadyCheckedIn,
  'checked_in_at': instance.checkedInAt?.toIso8601String(),
  'participant': instance.participant?.toJson(),
  'event': instance.event?.toJson(),
  'checkin_url': instance.checkinUrl,
  'qr_url': instance.qrUrl,
  'ticket_url': instance.ticketUrl,
};
