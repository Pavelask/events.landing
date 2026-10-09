import 'package:freezed_annotation/freezed_annotation.dart';

part 'speaker.freezed.dart';
part 'speaker.g.dart';

@freezed
abstract class Speaker with _$Speaker {
  const factory Speaker({
    required int id,
    required String name,
    String? position,
    String? organization,
    String? description,
    String? photo,
  }) = _Speaker;

  factory Speaker.fromJson(Map<String, dynamic> json) => _$SpeakerFromJson(json);
}
