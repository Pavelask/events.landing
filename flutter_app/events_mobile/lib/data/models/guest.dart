import 'package:freezed_annotation/freezed_annotation.dart';

part 'guest.freezed.dart';
part 'guest.g.dart';

@freezed
abstract class Guest with _$Guest {
  const factory Guest({
    required int id,
    required String name,
    String? position,
    String? organization,
    String? description,
    String? photo,
  }) = _Guest;

  factory Guest.fromJson(Map<String, dynamic> json) => _$GuestFromJson(json);
}
