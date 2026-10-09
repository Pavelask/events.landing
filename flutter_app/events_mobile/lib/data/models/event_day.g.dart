// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'event_day.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_EventDay _$EventDayFromJson(Map<String, dynamic> json) => _EventDay(
  id: (json['id'] as num).toInt(),
  date: json['date'] == null ? null : DateTime.parse(json['date'] as String),
  label: json['label'] as String?,
  description: json['description'] as String?,
  sortOrder: (json['sort_order'] as num?)?.toInt(),
  events:
      (json['events'] as List<dynamic>?)
          ?.map((e) => ScheduleEvent.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
);

Map<String, dynamic> _$EventDayToJson(_EventDay instance) => <String, dynamic>{
  'id': instance.id,
  'date': instance.date?.toIso8601String(),
  'label': instance.label,
  'description': instance.description,
  'sort_order': instance.sortOrder,
  'events': instance.events.map((e) => e.toJson()).toList(),
};
