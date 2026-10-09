// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'testimonial.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$Testimonial {

 int get id; String? get authorName; String? get content; String? get photo;
/// Create a copy of Testimonial
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$TestimonialCopyWith<Testimonial> get copyWith => _$TestimonialCopyWithImpl<Testimonial>(this as Testimonial, _$identity);

  /// Serializes this Testimonial to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as Testimonial;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is Testimonial&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.authorName, _this.authorName) || other.authorName == _this.authorName)&&(identical(other.content, _this.content) || other.content == _this.content)&&(identical(other.photo, _this.photo) || other.photo == _this.photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as Testimonial;
  return Object.hash(runtimeType,_this.id,_this.authorName,_this.content,_this.photo);
}

@override
String toString() {
  final _this = this as Testimonial;
  return 'Testimonial(id: ${_this.id}, authorName: ${_this.authorName}, content: ${_this.content}, photo: ${_this.photo})';
}


}

/// @nodoc
abstract mixin class $TestimonialCopyWith<$Res>  {
  factory $TestimonialCopyWith(Testimonial value, $Res Function(Testimonial) _then) = _$TestimonialCopyWithImpl;
@useResult
$Res call({
 int id, String? authorName, String? content, String? photo
});




}
/// @nodoc
class _$TestimonialCopyWithImpl<$Res>
    implements $TestimonialCopyWith<$Res> {
  _$TestimonialCopyWithImpl(this._self, this._then);

  final Testimonial _self;
  final $Res Function(Testimonial) _then;

/// Create a copy of Testimonial
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? authorName = freezed,Object? content = freezed,Object? photo = freezed,}) {
  return _then(Testimonial(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,authorName: freezed == authorName ? _self.authorName : authorName // ignore: cast_nullable_to_non_nullable
as String?,content: freezed == content ? _self.content : content // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [Testimonial].
extension TestimonialPatterns on Testimonial {
/// A variant of `map` that fallback to returning `orElse`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _Testimonial value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _Testimonial() when $default != null:
return $default(_that);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// Callbacks receives the raw object, upcasted.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case final Subclass2 value:
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _Testimonial value)  $default,){
final _that = this;
switch (_that) {
case _Testimonial():
return $default(_that);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `map` that fallback to returning `null`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _Testimonial value)?  $default,){
final _that = this;
switch (_that) {
case _Testimonial() when $default != null:
return $default(_that);case _:
  return null;

}
}
/// A variant of `when` that fallback to an `orElse` callback.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String? authorName,  String? content,  String? photo)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _Testimonial() when $default != null:
return $default(_that.id,_that.authorName,_that.content,_that.photo);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// As opposed to `map`, this offers destructuring.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case Subclass2(:final field2):
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String? authorName,  String? content,  String? photo)  $default,) {final _that = this;
switch (_that) {
case _Testimonial():
return $default(_that.id,_that.authorName,_that.content,_that.photo);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `when` that fallback to returning `null`
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String? authorName,  String? content,  String? photo)?  $default,) {final _that = this;
switch (_that) {
case _Testimonial() when $default != null:
return $default(_that.id,_that.authorName,_that.content,_that.photo);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _Testimonial implements Testimonial {
  const _Testimonial({required this.id, this.authorName, this.content, this.photo});
  factory _Testimonial.fromJson(Map<String, dynamic> json) => _$TestimonialFromJson(json);

@override final  int id;
@override final  String? authorName;
@override final  String? content;
@override final  String? photo;

/// Create a copy of Testimonial
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$TestimonialCopyWith<_Testimonial> get copyWith => __$TestimonialCopyWithImpl<_Testimonial>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$TestimonialToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _Testimonial&&(identical(other.id, id) || other.id == id)&&(identical(other.authorName, authorName) || other.authorName == authorName)&&(identical(other.content, content) || other.content == content)&&(identical(other.photo, photo) || other.photo == photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,authorName,content,photo);
}

@override
String toString() {
    return 'Testimonial(id: $id, authorName: $authorName, content: $content, photo: $photo)';
}


}

/// @nodoc
abstract mixin class _$TestimonialCopyWith<$Res> implements $TestimonialCopyWith<$Res> {
  factory _$TestimonialCopyWith(_Testimonial value, $Res Function(_Testimonial) _then) = __$TestimonialCopyWithImpl;
@override @useResult
$Res call({
 int id, String? authorName, String? content, String? photo
});




}
/// @nodoc
class __$TestimonialCopyWithImpl<$Res>
    implements _$TestimonialCopyWith<$Res> {
  __$TestimonialCopyWithImpl(this._self, this._then);

  final _Testimonial _self;
  final $Res Function(_Testimonial) _then;

/// Create a copy of Testimonial
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? authorName = freezed,Object? content = freezed,Object? photo = freezed,}) {
  return _then(_Testimonial(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,authorName: freezed == authorName ? _self.authorName : authorName // ignore: cast_nullable_to_non_nullable
as String?,content: freezed == content ? _self.content : content // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}

// dart format on
