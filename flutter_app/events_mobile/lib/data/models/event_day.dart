import 'package:freezed_annotation/freezed_annotation.dart';

import 'schedule_event.dart';

part 'event_day.freezed.dart';
part 'event_day.g.dart';

@freezed
abstract class EventDay with _$EventDay {
  const factory EventDay({
    required int id,
    DateTime? date,
    String? label,
    String? description,
    int? sortOrder,
    @Default([]) List<ScheduleEvent> events,
  }) = _EventDay;

  factory EventDay.fromJson(Map<String, dynamic> json) =>
      _$EventDayFromJson(json);
}
