// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'event.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_Event _$EventFromJson(Map<String, dynamic> json) => _Event(
  id: (json['id'] as num).toInt(),
  slug: json['slug'] as String,
  title: json['title'] as String,
  description: json['description'] as String?,
  status: json['status'] as String,
  startDate: json['start_date'] == null
      ? null
      : DateTime.parse(json['start_date'] as String),
  endDate: json['end_date'] == null
      ? null
      : DateTime.parse(json['end_date'] as String),
  dailyStartTime: json['daily_start_time'] as String?,
  dailyEndTime: json['daily_end_time'] as String?,
  venueName: json['venue_name'] as String?,
  venueAddress: json['venue_address'] as String?,
  posterImage: json['poster_image'] as String?,
  logo: json['logo'] as String?,
  videoUrl: json['video_url'] as String?,
  isRegistrationOpen: json['is_registration_open'] as bool? ?? false,
  registrationType: json['registration_type'] as String?,
  registrationUrl: json['registration_url'] as String?,
  yandexFormId: json['yandex_form_id'] as String?,
  mediaImage: json['media_image'] as String?,
  mediaDescription: json['media_description'] as String?,
  isMediaVisible: json['is_media_visible'] as bool? ?? false,
  gallery:
      (json['gallery'] as List<dynamic>?)?.map((e) => e as String).toList() ??
      const <String>[],
  galleryExternalUrl: json['gallery_external_url'] as String?,
  galleryExternalDescription: json['gallery_external_description'] as String?,
  isGalleryExternalVisible:
      json['is_gallery_external_visible'] as bool? ?? false,
  days:
      (json['days'] as List<dynamic>?)
          ?.map((e) => EventDay.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  speakers:
      (json['speakers'] as List<dynamic>?)
          ?.map((e) => Speaker.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  guests:
      (json['guests'] as List<dynamic>?)
          ?.map((e) => Guest.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  testimonials:
      (json['testimonials'] as List<dynamic>?)
          ?.map((e) => Testimonial.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  faqs:
      (json['faqs'] as List<dynamic>?)
          ?.map((e) => Faq.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  documents:
      (json['documents'] as List<dynamic>?)
          ?.map((e) => EventDocument.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
);

Map<String, dynamic> _$EventToJson(_Event instance) => <String, dynamic>{
  'id': instance.id,
  'slug': instance.slug,
  'title': instance.title,
  'description': instance.description,
  'status': instance.status,
  'start_date': instance.startDate?.toIso8601String(),
  'end_date': instance.endDate?.toIso8601String(),
  'daily_start_time': instance.dailyStartTime,
  'daily_end_time': instance.dailyEndTime,
  'venue_name': instance.venueName,
  'venue_address': instance.venueAddress,
  'poster_image': instance.posterImage,
  'logo': instance.logo,
  'video_url': instance.videoUrl,
  'is_registration_open': instance.isRegistrationOpen,
  'registration_type': instance.registrationType,
  'registration_url': instance.registrationUrl,
  'yandex_form_id': instance.yandexFormId,
  'media_image': instance.mediaImage,
  'media_description': instance.mediaDescription,
  'is_media_visible': instance.isMediaVisible,
  'gallery': instance.gallery,
  'gallery_external_url': instance.galleryExternalUrl,
  'gallery_external_description': instance.galleryExternalDescription,
  'is_gallery_external_visible': instance.isGalleryExternalVisible,
  'days': instance.days.map((e) => e.toJson()).toList(),
  'speakers': instance.speakers.map((e) => e.toJson()).toList(),
  'guests': instance.guests.map((e) => e.toJson()).toList(),
  'testimonials': instance.testimonials.map((e) => e.toJson()).toList(),
  'faqs': instance.faqs.map((e) => e.toJson()).toList(),
  'documents': instance.documents.map((e) => e.toJson()).toList(),
};
