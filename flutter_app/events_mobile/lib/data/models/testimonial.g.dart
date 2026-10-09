// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'testimonial.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_Testimonial _$TestimonialFromJson(Map<String, dynamic> json) => _Testimonial(
  id: (json['id'] as num).toInt(),
  authorName: json['author_name'] as String?,
  content: json['content'] as String?,
  photo: json['photo'] as String?,
);

Map<String, dynamic> _$TestimonialToJson(_Testimonial instance) =>
    <String, dynamic>{
      'id': instance.id,
      'author_name': instance.authorName,
      'content': instance.content,
      'photo': instance.photo,
    };
