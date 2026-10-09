import 'package:freezed_annotation/freezed_annotation.dart';

import 'event_day.dart';
import 'event_document.dart';
import 'faq.dart';
import 'guest.dart';
import 'speaker.dart';
import 'testimonial.dart';

part 'event.freezed.dart';
part 'event.g.dart';

@freezed
abstract class Event with _$Event {
  const factory Event({
    required int id,
    required String slug,
    required String title,
    String? description,
    required String status,
    DateTime? startDate,
    DateTime? endDate,
    String? dailyStartTime,
    String? dailyEndTime,
    String? venueName,
    String? venueAddress,
    String? posterImage,
    String? logo,
    String? videoUrl,
    @Default(false) bool isRegistrationOpen,
    String? registrationType,
    String? registrationUrl,
    String? yandexFormId,
    String? mediaImage,
    String? mediaDescription,
    @Default(false) bool isMediaVisible,
    @Default(<String>[]) List<String> gallery,
    String? galleryExternalUrl,
    String? galleryExternalDescription,
    @Default(false) bool isGalleryExternalVisible,
    @Default([]) List<EventDay> days,
    @Default([]) List<Speaker> speakers,
    @Default([]) List<Guest> guests,
    @Default([]) List<Testimonial> testimonials,
    @Default([]) List<Faq> faqs,
    @Default([]) List<EventDocument> documents,
  }) = _Event;

  factory Event.fromJson(Map<String, dynamic> json) => _$EventFromJson(json);
}
