// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'schedule_event.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_ScheduleEvent _$ScheduleEventFromJson(Map<String, dynamic> json) =>
    _ScheduleEvent(
      id: (json['id'] as num).toInt(),
      startTime: json['start_time'] as String?,
      endTime: json['end_time'] as String?,
      title: json['title'] as String?,
      description: json['description'] as String?,
      location: json['location'] as String?,
      isBreak: json['is_break'] as bool? ?? false,
      speaker: json['speaker'] == null
          ? null
          : SpeakerBrief.fromJson(json['speaker'] as Map<String, dynamic>),
    );

Map<String, dynamic> _$ScheduleEventToJson(_ScheduleEvent instance) =>
    <String, dynamic>{
      'id': instance.id,
      'start_time': instance.startTime,
      'end_time': instance.endTime,
      'title': instance.title,
      'description': instance.description,
      'location': instance.location,
      'is_break': instance.isBreak,
      'speaker': instance.speaker?.toJson(),
    };

_SpeakerBrief _$SpeakerBriefFromJson(Map<String, dynamic> json) =>
    _SpeakerBrief(
      id: (json['id'] as num).toInt(),
      name: json['name'] as String,
      position: json['position'] as String?,
      photo: json['photo'] as String?,
    );

Map<String, dynamic> _$SpeakerBriefToJson(_SpeakerBrief instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'position': instance.position,
      'photo': instance.photo,
    };
