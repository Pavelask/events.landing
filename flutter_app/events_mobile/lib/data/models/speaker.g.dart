// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'speaker.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_Speaker _$SpeakerFromJson(Map<String, dynamic> json) => _Speaker(
  id: (json['id'] as num).toInt(),
  name: json['name'] as String,
  position: json['position'] as String?,
  organization: json['organization'] as String?,
  description: json['description'] as String?,
  photo: json['photo'] as String?,
);

Map<String, dynamic> _$SpeakerToJson(_Speaker instance) => <String, dynamic>{
  'id': instance.id,
  'name': instance.name,
  'position': instance.position,
  'organization': instance.organization,
  'description': instance.description,
  'photo': instance.photo,
};
