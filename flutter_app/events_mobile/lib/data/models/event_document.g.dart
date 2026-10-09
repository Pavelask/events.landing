// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'event_document.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_EventDocument _$EventDocumentFromJson(Map<String, dynamic> json) =>
    _EventDocument(
      id: (json['id'] as num).toInt(),
      title: json['title'] as String,
      filePath: json['file_path'] as String?,
      fileType: json['file_type'] as String?,
    );

Map<String, dynamic> _$EventDocumentToJson(_EventDocument instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'file_path': instance.filePath,
      'file_type': instance.fileType,
    };
