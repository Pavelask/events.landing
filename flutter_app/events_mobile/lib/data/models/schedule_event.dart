import 'package:freezed_annotation/freezed_annotation.dart';

part 'schedule_event.freezed.dart';
part 'schedule_event.g.dart';

@freezed
abstract class ScheduleEvent with _$ScheduleEvent {
  const factory ScheduleEvent({
    required int id,
    String? startTime,
    String? endTime,
    String? title,
    String? description,
    String? location,
    @Default(false) bool isBreak,
    SpeakerBrief? speaker,
  }) = _ScheduleEvent;

  factory ScheduleEvent.fromJson(Map<String, dynamic> json) =>
      _$ScheduleEventFromJson(json);
}

@freezed
abstract class SpeakerBrief with _$SpeakerBrief {
  const factory SpeakerBrief({
    required int id,
    required String name,
    String? position,
    String? photo,
  }) = _SpeakerBrief;

  factory SpeakerBrief.fromJson(Map<String, dynamic> json) =>
      _$SpeakerBriefFromJson(json);
}
