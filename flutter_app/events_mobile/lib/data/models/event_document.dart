import 'package:freezed_annotation/freezed_annotation.dart';

part 'event_document.freezed.dart';
part 'event_document.g.dart';

@freezed
abstract class EventDocument with _$EventDocument {
  const factory EventDocument({
    required int id,
    required String title,
    String? filePath,
    String? fileType,
  }) = _EventDocument;

  factory EventDocument.fromJson(Map<String, dynamic> json) =>
      _$EventDocumentFromJson(json);
}
