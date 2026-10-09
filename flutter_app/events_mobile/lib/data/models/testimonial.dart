import 'package:freezed_annotation/freezed_annotation.dart';

part 'testimonial.freezed.dart';
part 'testimonial.g.dart';

@freezed
abstract class Testimonial with _$Testimonial {
  const factory Testimonial({
    required int id,
    String? authorName,
    String? content,
    String? photo,
  }) = _Testimonial;

  factory Testimonial.fromJson(Map<String, dynamic> json) =>
      _$TestimonialFromJson(json);
}
