// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'faq.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_Faq _$FaqFromJson(Map<String, dynamic> json) => _Faq(
  id: (json['id'] as num).toInt(),
  question: json['question'] as String,
  answer: json['answer'] as String?,
);

Map<String, dynamic> _$FaqToJson(_Faq instance) => <String, dynamic>{
  'id': instance.id,
  'question': instance.question,
  'answer': instance.answer,
};
